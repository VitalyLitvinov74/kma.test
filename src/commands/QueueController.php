<?php
declare(strict_types=1);

namespace app\commands;

use app\objects\forms\Field;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\decorators\WebPageWithCheckedSource;
use app\objects\urls\WebPage;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class QueueController extends Controller
{
    public function actionWatch()
    {
        $urlQueue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $urlQueue->processMessages(function (AMQPMessage $message) {
           $url =
//           new WebPageWithCheckedSource( //Страница с проверкой статуса ответа
               new WebPage( //Страница которую сохраняем в бд
                   new Field( //получаем урл в виде поля (я работаю с полями, это универсальная оболочка)
                       ArrayHelper::getValue(
                           json_decode($message->getBody(), true),
                           'url'
                       )
                   )
               )
//           )
            ;
           $url->saveResponse();
           $message->ack();
        });
    }
}