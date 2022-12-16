<?php
declare(strict_types=1);

namespace app\objects\urls\decorators;

use app\objects\exceptions\UrlNotResponding;
use app\objects\forms\IField;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\IWebPage;
use app\tables\TableUrlResponses;
use PhpAmqpLib\Message\AMQPMessage;
use vloop\entities\contracts\Form;

class WebPageWithCheckedSource implements IWebPage
{
    private $origin;
    private $queue;
    private $message;

    public function __construct(IWebPage $url, AMQPMessage $message)
    {
        $this->origin = $url;
        $this->queue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $this->message = $message;
    }

    public function sendToValidation(): void
    {
        $this->origin->sendToValidation();
    }

    public function saveResponse(): TableUrlResponses
    {
        $messageBody = json_decode($this->message->getBody(), true);
        if(isset($messageBody['validated'])){
            return $this->origin->saveResponse();
        }
        $headers = @get_headers($messageBody['url']);
        if ($headers && strpos($headers[0], '200')) {
            return $this->origin->saveResponse();
        }
        $this->queue->putIn(
            [
                'url' => $messageBody['url'],
                'validated' => true
            ],
            15
        );
        throw new UrlNotResponding($messageBody['url']);
    }
}