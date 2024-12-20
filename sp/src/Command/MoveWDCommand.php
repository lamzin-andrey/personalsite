<?php
// php bin/console app::mwd_dwd

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


class MoveWDCommand extends Command
{
    private const WD_APP_ROOT_DIR = 'WUSB';

    private AppService $appService;
    private YandexWebDav $webDav;
    private WusbUploadService $wusbUploadService;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:mwd_dwd';

    public function __construct(string $name = 'app:mwd_dwd', AppService $appService, YandexWebDav $webDav, WusbUploadService $wusbUploadService)
    {
        $this->appService = $appService;
        $this->webDav = $webDav;
        $this->wusbUploadService = $wusbUploadService;

        parent::__construct(static::$defaultName);
    }


    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $this->webDav->setAuthParams(
            $this->appService->getParameter('app.wd_username'),
            $this->appService->getParameter('app.wd_password')
        );
        while (true) {
            $list = $this->getList();
            /**
             * @var DrvFile $ent
            */
            foreach ($list as $ent) {
                $po = $this->wusbUploadService->getFilePathObject($ent, $this->appService, null);
                if (!$po->error && file_exists($po->path)) {
                    $wdPath = static::WD_APP_ROOT_DIR . '/' . $po->userPath;
                    if ($this->createFolder($wdPath)) {
                        $link = '';
                        if ($this->upload($po->path, $wdPath)) {
                            $link = $this->webDav->publish($wdPath);
                        }
                        if ($link) {
                            $ent->setWdPath($wdPath);
                            $ent->setWdLink($link);
                            $ent->setWdPublic(1);
                            $this->appService->save($ent);
                            unlink($po->path);
                        }
                    }
                } {
                    $ent->setWdPublic(2);
                    $this->appService->save($ent);
                }
            }
            sleep(5);
        }

        return 0;
    }

    private function getList(): array
    {
        return $this->appService->findBy(DrvFile::class, [
            'wdPublic' => 0,
            'isDeleted' => 0,
        ], ['id' => 'ASC'], 100);

        // 851 - change to accessible local file
        //return [$this->appService->find(DrvFile::class, 851)];
    }

    private function createFolder(string $path): bool
    {
        $pathInfo = pathinfo($path);
        $dirname = $pathInfo['dirname'];
        $a = explode('/', $dirname);
        $b = [];
        foreach ($a as $s) {
            if (count($b) > 0) {
                $cs = implode('/', $b) . '/' . $s;
            } else {
                $cs = $s;
            }
            $b[] = $s;
            $s = trim($this->webDav->createFolder($cs));
        }
        return (strlen($s) === 0);
    }

    private function upload(string $path, string $wdPath): bool
    {
        $ctrl = 'Wonderful wonderful life. And I not listen it sobg now.';
        $fileA = sys_get_temp_dir() . '/wusbCheckFile1.txt';
        if (!file_exists($fileA)) {
            file_put_contents($fileA, $ctrl);
        }
        $fileB = sys_get_temp_dir() . '/wusbCheckFile' . rand(1000, 9999) . '.txt';
        if (file_exists($fileB)) {
            unlink($fileB);
        }
        $state = $this->uploadSimple($fileA, $wdPath);
        if (!$state) {
            return false;
        }
        $this->webDav->download($wdPath, $fileB);
        if (!file_exists($fileB)) {
            return false;
        }
        if (file_exists($fileB)) {
            $s = file_get_contents($fileB);
            if ($s !== $ctrl) {
                echo "No ctrl!";
                return false;
            }
            $this->webDav->delete($wdPath);
            unlink($fileB);
        }
        $state = $this->uploadSimple($path, $wdPath);
        if (!$state) {
            return false;
        }

        $dirname = pathinfo($wdPath)['dirname'];
        $xml = $this->webDav->listFolder($dirname, true);

        return (strpos($xml, $wdPath) !== false);
    }

    private function uploadSimple(string $path, string $wdPath): bool
    {
        $s = $this->webDav->upload($path, $wdPath);
        return (strlen($s) === 0);
    }
}