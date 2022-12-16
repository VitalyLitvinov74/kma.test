<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\IField;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\tables\TableUrlResponses;
use Exception;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class WebPage implements IWebPage
{
    private $urlField;
    private $queue;
    private $client;

    /**
     * @throws Exception
     */
    public function __construct(IField $urlFromSomewhere)
    {
        $this->urlField = $urlFromSomewhere;
        $this->queue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
        $this->client = new Client([
            'transport' => [
                'class' => CurlTransport::class
            ]
        ]);
    }

    public function sendToValidation(): void
    {
        $this->queue->putIn([
            'url' => $this->urlField->toString(),
        ]);
    }

    public function saveResponse(): TableUrlResponses
    {
        $client = $this->client;
        $response = $client->createRequest()
            ->setUrl($this->urlField->toString())
            ->send();
        $record = new TableUrlResponses([
            'statusCode' => $response->getStatusCode(),
            'headers' => json_encode($response->getHeaders()->toArray()),
            'content' => Html::encode($response->getContent()),
            'url' => $this->urlField->toString()
        ]);
        $record->save();
        return $record;
    }
}