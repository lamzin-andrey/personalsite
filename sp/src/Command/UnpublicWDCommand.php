<?php
// php bin/console app:runpwd

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


class UnpublicWDCommand extends Command
{
    private AppService $appService;
    private YandexWebDav $webDav;
    private WusbUploadService $wusbUploadService;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:unpwd';

    public function __construct(string $name = 'app:unpwd', AppService $appService, YandexWebDav $webDav, WusbUploadService $wusbUploadService)
    {
        $this->appService = $appService;
        $this->webDav = $webDav;
        $this->wusbUploadService = $wusbUploadService;

        parent::__construct(static::$defaultName);
    }


    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $procFile = sys_get_temp_dir() . '/unpwd.pid';
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
                $wdPath = trim($ent->getWdPath());
                if (!$wdPath) {
                    $ent->setWdPublic(0);
                    $this->appService->save($ent);
                    continue;
                }
                $s = trim($this->webDav->unpublish($wdPath));
                if (strlen($s) == 0) {
                    $ent->setWdPublic(1);
                    $this->appService->save($ent);
                } else {
                    $ent->setWdError($s);
                    $this->appService->save($ent);
                }
            }
            //sleep(5);
        //}

        return 0;
    }

    private function getList(): array
    {
        return $this->appService->findBy(DrvFile::class, [
            'wdPublic' => 6,
            'isDeleted' => true,
        ], ['id' => 'ASC'], 100);

        // 851 - change to accessible local file
        //return [$this->appService->find(DrvFile::class, 851)];
    }

}