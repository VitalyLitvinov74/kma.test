<?php
declare(strict_types=1);

namespace app\console\controller;

class WatcherDaemonController extends \vyants\daemon\controllers\WatcherDaemonController
{
    /**
     * @return array
     */
    protected function defineJobs()
    {
        sleep($this->sleep);
        //TODO: modify list, or get it from config, it does not matter
        $daemons = [
            ['className' => 'OneDaemonController', 'enabled' => true],
            ['className' => 'AnotherDaemonController', 'enabled' => false]
        ];
        return $daemons;
    }
}