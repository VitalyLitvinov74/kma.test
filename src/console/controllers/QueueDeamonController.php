<?php
declare(strict_types=1);

namespace app\console\controller;

use app\objects\forms\FormByMessage;
use app\objects\forms\UrlForm;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\decorators\UrlListWithCheckedSource;
use app\objects\urls\Url;
use app\objects\urls\UrlList;
use PhpAmqpLib\Message\AMQPMessage;
use vyants\daemon\DaemonController;

class QueueDeamonController extends DaemonController
{

    /**
     * Daemon worker body
     *
     * @param AMQPMessage $job
     * @return boolean
     */
    protected function doJob($job)
    {
        $list = new UrlListWithCheckedSource( //это декоратор - занимается проверкой урла перед сохраннением
            new UrlList() // это оригинальный класс - занимается сохранением
        );
        $list->add(new FormByMessage($job));

    }

    /**
     * Extract current unprocessed jobs
     * You can extract jobs from DB (DataProvider will be great), queue managers (ZMQ, RabbiMQ etc), redis and so on
     *
     * @return array with jobs
     * @throws \Exception
     */
    protected function defineJobs()
    {
        $urlQueue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $urlQueue->processMessages(function (AMQPMessage $message) {
            $this->doJob($message);
        });
        return false;
    }
}