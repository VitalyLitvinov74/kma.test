<?php
declare(strict_types=1);

namespace app\commands;

use app\objects\forms\FormByMessage;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\decorators\UrlListWithCheckedSource;
use app\objects\urls\UrlList;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;

class QueueController extends Controller
{
    public function actionWatch()
    {
        $urlQueue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $urlQueue->processMessages(function (AMQPMessage $message) {
            $list = new UrlListWithCheckedSource( //это декоратор - занимается проверкой урла перед сохраннением
                new UrlList() // это оригинальный класс - занимается сохранением
            );
            try {
                $list->add(new FormByMessage($message));
            } catch (\Exception $exception) {
                //куда нибудь логируем.
            }
            $message->ack();
        });
    }
}