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
        $daemons = [
            ['className' => QueueDeamonController::class, 'enabled' => true],
        ];
        return $daemons;
    }
}