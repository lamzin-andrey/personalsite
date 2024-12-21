<?php
// php bin/console app:pblwd

namespace App\Command;

use App\Entity\DrvFile;
use App\Entity\DrvFilePermissions;
use App\Service\AppService;
use App\Service\YandexWebDav;
use App\WebUSB\Service\WusbUploadService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class PublicWDCommand extends Command
{
    private const WD_APP_ROOT_DIR = 'WUSB';

    private AppService $appService;
    private YandexWebDav $webDav;
    private WusbUploadService $wusbUploadService;
    private string $lastWdError = '';

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:pblwd';

    public function __construct(string $name = 'app:mwd_dwd', AppService $appService, YandexWebDav $webDav, WusbUploadService $wusbUploadService)
    {
        $this->appService = $appService;
        $this->webDav = $webDav;
        $this->wusbUploadService = $wusbUploadService;

        parent::__construct(static::$defaultName);
    }


    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $procFile = sys_get_temp_dir() . '/mvwdpub.pid';
        if (file_exists($procFile)) {
            $processId = intval(file_get_contents($procFile));
            if (posix_kill($processId, 0)) {
                echo "Proc is run, exit\n";
                return 0;
            } else {
                unlink($procFile);
            }
        }
        $processId = posix_getpid();
        file_put_contents($procFile, $processId);


        $this->webDav->setAuthParams(
            $this->appService->getParameter('app.wd_username'),
            $this->appService->getParameter('app.wd_password')
        );
        //while (true) {
            $list = $this->getList();
            /**
             * @var DrvFile $ent
            */
            foreach ($list as $ent) {
                $po = $this->wusbUploadService->getFilePathObject($ent, $this->appService, null);
                if (!$po->error && file_exists($po->path)) {
                    $wdPath = $ent->getWdPath();
                    $link = '';
                    $dirname = pathinfo($wdPath)['dirname'];
                    $xml = $this->webDav->listFolder($dirname, true);
                    if ((strpos($xml, $wdPath) !== false)) {
                        if($this->checkMd5Sum($po->path, $xml, $wdPath)) {
                            $link = $this->webDav->publish($wdPath);
                        } else {
                            $ent->setWdError($this->lastWdError);
                            $this->appService->save($ent);
                        }
                    }
                    if ($link) {
                        $ent->setWdPublic(1);
                        $ent->setWdLink($link);
                        $this->appService->save($ent);
                        unlink($po->path);
                    }

                }
            }
            //sleep(5);
        //}

        return 0;
    }

    private function getList(): array
    {
        return $this->appService->findBy(DrvFile::class, [
            'wdPublic' => 3,
            'isDeleted' => 0,
        ], ['id' => 'ASC'], 100);

        // 851 - change to accessible local file
        //return [$this->appService->find(DrvFile::class, 851)];
    }

    private function checkMd5Sum(string $path, string $xml, string $wdPath): bool
    {
        $a = explode($wdPath, $xml);
        if (count($a) != 2) {
            $this->lastWdError = date('Y-m-d H:i:s') . 'Local: not found wdPath in response, or it more then 1';
            return false;
        }
        $a = explode('</d:prop>', $a[1]);
        $a = explode('<d:getetag>', $a[0]);
        if (count($a) != 2) {
            $this->lastWdError = date('Y-m-d H:i:s') . 'Local: not found d:getetag in response, or it more then 1';
            return false;
        }
        $a = explode('</d:getetag>', $a[1]);
        if (count($a) != 2) {
            $this->lastWdError = date('Y-m-d H:i:s') . 'Local: not found /d:getetag in response, or it more then 1';
            return false;
        }
        $remoteMd5 = trim($a[0]);
        $localMd5 = md5_file($path);

        if ($remoteMd5 != $localMd5) {
            $this->lastWdError = date('Y-m-d H:i:s') . 'Local: md5 sum not equivalent!';
            return false;
        }

        return true;
    }

}