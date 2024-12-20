<?php


namespace App\Service;


class YandexWebDav
{
    const WEBDAV_HOST = 'https://webdav.yandex.ru/';
    private array $creds = [];
    private string $fields = '';
    private AppService $appService;

    public function __construct(AppService $appService)
    {
        $this->appService = $appService;
    }

    public function setAuthParams(string $username, string $password): void
    {
        $this->creds[0] = $username;
        $this->creds[1] = $password;
    }

    private function send($request, $url, $headers = [], $file = null):string
    {
        if ($file) {
            $option = [
                'http' => [
                    'method' => 'PUT',
                ]
            ];
            $header[] = ['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'];
            $header[] = ['Accept-Languange' => 'en-US,en;q=0.5'];
            $header[] = ['Authorization' => 'Basic '.base64_encode($this->creds[0] . ':' . $this->creds[1])];
            $fileData = file_get_contents($file);
            $header[] = ['Content-Type' => 'application/octet-stream'];
            $header[] = ['Content-Length' => strlen($fileData)];
            $option['http']['content'] = $fileData;
            $headerLine = [];
            foreach ($header as $headerItem) {
                foreach ($headerItem as $headerName => $headerValue) {
                    $headerLine[] = $headerName . ': ' . $headerValue;
                }
            }
            $headerText = implode("\r\n", $headerLine)."\r\n";
            $option['http']['header'] = $headerText;
            $context = stream_context_create($option);
            $responseText = file_get_contents(self::WEBDAV_HOST . $url, false, $context);

            return strval($responseText);
        }
        $ch = curl_init(self::WEBDAV_HOST . $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, implode(':', $this->creds));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields);

        $result = curl_exec($ch);
        curl_close($ch);
        $this->fields = '';
        return strval($result);
    }

    public function createFolder(string $folder): string
    {
        return $this->send('MKCOL', $folder);
    }

    public function listFolder(string $folder = '', bool $getXml = false)
    {
        $xml = $this->send('PROPFIND', $folder, [
            'Depth: 1'
        ]);
        if ($getXml) {
            return $xml;
        }
        preg_match_all('@<d:response><d:href>(.*?)</d:href><d:propstat><d:status>(.*?)</d:status><d:prop><d:creationdate>(.*?)</d:creationdate><d:displayname>(.*?)</d:displayname><d:getlastmodified>(.*?)</d:getlastmodified><d:resourcetype><d:collection/></d:resourcetype></d:prop></d:propstat></d:response>@', $xml, $folders);
        preg_match_all('@<d:response><d:href>(.*?)</d:href><d:propstat><d:status>(.*?)</d:status><d:prop><d:getetag>(.*?)</d:getetag><d:creationdate>(.*?)</d:creationdate><d:displayname>(.*?)</d:displayname><d:getlastmodified>(.*?)</d:getlastmodified><d:getcontenttype>(.*?)</d:getcontenttype><d:getcontentlength>(.*?)</d:getcontentlength><d:resourcetype/></d:prop></d:propstat></d:response>@', $xml, $files);
        return array_merge($this->formatLs($folders), $this->formatLs($files));
    }

    public function formatLs(array $lists): array
    {
        $arr = [];
        unset($lists[1][0]);
        foreach ($lists[1] as $key => $file) {
            $arr[] = [
                'path' => $file,
                'name' => $lists[5][$key],
                'create' => $lists[4][$key],
                'modified' => $lists[6][$key],
                'type' => isset($lists[7][$key]) ? $lists[7][$key] : null,
                'size' => isset($lists[8][$key]) ? $this->formatFilesize($lists[8][$key]) : null
            ];
        }
        return $arr;
    }

    public function download(string $path, string $local)
    {
        $result = $this->send('GET', $path);
        return file_put_contents($local, $result);
    }

    public function upload(string $local, string $remote)
    {
        return $this->send('PUT', $remote, [], $local);
    }

    public function move(string $curr_path, string $new_path)
    {
        return $this->send('MOVE', $curr_path, [
            'Destination: /' . $new_path,
            'Overwrite: F'
        ]);
    }

    public function delete(string $remote)
    {
        return $this->send('DELETE', $remote);
    }

    public function publish(string $path): string
    {
        $this->fields = '<propertyupdate xmlns="DAV:">
          <set>
            <prop>
              <public_url xmlns="urn:yandex:disk:meta">true</public_url>
            </prop>
          </set>
        </propertyupdate>';
        $xml = $this->send('PROPPATCH', $path);
        $link = '';
        $a = explode("<public_url", strval($xml));
        if (count($a) > 1) {
            $a = explode(">", $a[1]);
            if (count($a) > 1) {
                $a = explode("</public_url", $a[1]);
                $link = trim($a[0]);
            }
        }
        return $link;
    }

    public function unpublish(string $path):string
    {
        $this->fields = '<propertyupdate xmlns="DAV:">
          <remove>
            <prop>
              <public_url xmlns="urn:yandex:disk:meta" />
            </prop>
          </remove>
        </propertyupdate>';
        return $this->send('PROPPATCH', $path);
    }

    public function getUsage():array
    {
        $this->fields = '<D:propfind xmlns:D="DAV:">
          <D:prop>
            <D:quota-available-bytes/>
            <D:quota-used-bytes/>
          </D:prop>
        </D:propfind>';
        $xml = $this->send('PROPFIND', '', [
            'Depth: 0',
            'Content-Type: application/xml'
        ]);
        preg_match_all('@<d:quota-(available|used)-bytes>([0-9]+)</d:quota-(available|used)-bytes>@', $xml, $usages);
        return [
            'used' => $this->formatFilesize(intval($usages[2][0])),
            'available' => $this->formatFilesize(intval($usages[2][1])),
            'total' => $this->formatFilesize(intval(array_sum($usages[2])))
        ];
    }

    public function formatFilesize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, ',', ' ') . ' ' . $units[$power];
    }
}