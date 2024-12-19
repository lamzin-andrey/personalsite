<?php
namespace App\Stat\Service;

use App\Entity\Ausers;
use App\Entity\DrvUa;
use App\Entity\StatIp;
use App\Entity\StatScreen;
use App\Entity\StatViewport;
use App\Service\AppService;
use App\Stat\Repository\StatViewportRepository;

class StatService
{
    private AppService $appService;

    public function __construct(AppService $appService)
    {
        $this->appService = $appService;
    }

    public function write(?Ausers $user): void
    {
        $s = $this->appService;
        $request = $s->request();
        $ua = $request->server->get('HTTP_USER_AGENT');
        $ip = $request->server->get('REMOTE_ADDR');
        $uaId = $this->getId('drv_ua', $ua, 'ua');
        $ipId = $this->getId('stat_ip', $ip, 'ip');

        //screenw#screenh#viewporth#wiewporth
        $clientData = trim($request->get('h'));
        $url = $request->get('u');
        $url = $url ?? '';
        if (!$clientData) {
            $clientData = "0-0-0-0";
        }
        $aClient = explode('-', $clientData);
        if (count($aClient) != 4) {
            $aClient = [0,0,0,0];
        }
        $screen = hexdec($aClient[0]) . 'x' . hexdec($aClient[1]);
        $viewport = hexdec($aClient[2]) . 'x' . hexdec($aClient[3]);
        $screenId = $this->getId('stat_screen', $screen, 'screen');
        $viewportId = $this->getId('stat_viewport', $viewport, 'viewport');
        $userId = $user ? $user->getId() : 0;

        $insert = "INSERT INTO stat_ua_view 
            (`screen_id`, `viewport_id`, `user_id`, `iip`, `ua_id`, `url`, `created_time`)
            values(:screenId, :viewportId, :userId, :iip, :uaId, :url, :ct)
            ON DUPLICATE KEY UPDATE user_id = user_id;
            ";
        $nR = 0;
        $s->query($insert, $nR, [
            'screenId'   => $screenId,
            'viewportId' => $viewportId,
            'userId'     => $userId,
            'iip'        => $ipId,
            'uaId'       => $uaId,
            'url'        => $url,
            'ct'         => date('Y-m-d H:i:s')
        ], []);
    }

    private function getId(string $table, string $v, string $fname): int
    {
        $insert = "INSERT INTO $table 
            (`$fname`)
            values(:v)
            ON DUPLICATE KEY UPDATE $fname = $fname;
            ";
        $nR = 0;
        $n =  (int)$this->appService->query($insert, $nR, [
            'v'   => $v
        ], []);
        if (!$n) {
            $n = (int)$this->appService->dbvalue("SELECT id FROM $table WHERE $fname = :v",
                ['v' => $v],
                []
            );
        }
        return $n;
    }

}