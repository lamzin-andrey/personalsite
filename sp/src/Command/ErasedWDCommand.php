<?php
// php bin/console app:rmwd

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


class ErasedWDCommand extends Command
{
    private AppService $appService;
    private YandexWebDav $webDav;
    private WusbUploadService $wusbUploadService;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:rmwd';

    public function __construct(string $name = 'app:rmwd', AppService $appService, YandexWebDav $webDav, WusbUploadService $wusbUploadService)
    {
        $this->appService = $appService;
        $this->webDav = $webDav;
        $this->wusbUploadService = $wusbUploadService;

        parent::__construct(static::$defaultName);
    }


    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $procFile = sys_get_temp_dir() . '/rmwd.pid';
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
                $wdPath = $ent->getWdPath();
                $s = trim($this->webDav->delete($wdPath));
                if (strlen($s) == 0) {
                    $ent->setWdPublic(4);
                    $this->appService->save($ent);
                } else {
                    $ent->setWdError($s);
                    $this->appService->save($ent);
                }
                $po = $this->wusbUploadService->getFilePathObject($ent, $this->appService, null);
                if (!$po->error) {
                    if (file_exists($po->path)) {
                        unlink($po->path);
                    }
                    if (file_exists($po->symlink)) {
                        unlink($po->symlink);
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
            'wdPublic' => 1,
            'isNoErased' => false,
        ], ['id' => 'ASC'], 100);

        // 851 - change to accessible local file
        //return [$this->appService->find(DrvFile::class, 851)];
    }

}