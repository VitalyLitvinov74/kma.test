<?php
declare(strict_types=1);

namespace app\console\controller;

use app\objects\forms\UrlForm;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\Url;
use app\objects\urls\UrlList;
use PhpAmqpLib\Message\AMQPMessage;
use vyants\daemon\DaemonController;

class QueueDeamonController extends DaemonController
{

    /**
     * Daemon worker body
     *
     * @param $job
     * @return boolean
     */
    protected function doJob($job)
    {

    }

    /**
     * Extract current unprocessed jobs
     * You can extract jobs from DB (DataProvider will be great), queue managers (ZMQ, RabbiMQ etc), redis and so on
     *
     * @return array with jobs
     */
    protected function defineJobs()
    {
        $urlQueue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $urlQueue->pickUpMessage();
    }
}