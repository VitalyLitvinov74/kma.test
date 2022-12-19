<?php
declare(strict_types=1);

namespace app\commands;

use app\objects\exceptions\UrlNotResponding;
use app\objects\forms\Field;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\decorators\WebPageWithCheckedSource;
use app\objects\urls\WebPage;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;

/**
 * Сам этот контроллер я бы вынес в отдельный контейнер.
 * а тут сделал бы обычный callback с обычным контроллером.
 * по сути у нас бы появился один прокси контейнер который занимается исключительно очередями.
 * дело в том что такой код проще тестить, в противном случае у нас все завязано на работу с очередями.
 */
class QueueController extends Controller
{
    public function actionWatch()
    {
        $urlQueue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $urlQueue->processMessages(function (AMQPMessage $message) {
            sleep(30);
            $url =
                new WebPageWithCheckedSource( //Страница с проверкой статуса ответа
                    new WebPage( //Страница которую сохраняем в бд
                        new Field( //получаем урл в виде поля (я работаю с полями, это универсальная оболочка)
                            ArrayHelper::getValue(
                                json_decode($message->getBody(), true),
                                'url'
                            )
                        )
                    ),
                    $message
                );
            try {
                $url->saveResponse();
            } catch (Exception $exception) {
                //ошибки можем залогировать. но на текущий момоент не обращаем на это внимания.
            } catch (UrlNotResponding $exception) {
                //Дело в том что не каждый урл существует. следовательно что он будет отвечать если его нет?
                // Если урл ничего не ответил, в этот момент отправлно сообщение в очередь
            }
            $message->ack();
        });
    }
}