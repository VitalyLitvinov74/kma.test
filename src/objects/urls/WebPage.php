<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\IField;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\tables\TableUrlResponses;
use Exception;
use yii\helpers\Url;
use yii\httpclient\Client;

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
        $this->client = new Client();
    }

    public function sendToValidation(int $delaySec = 0): void
    {
        $this->queue->putIn(
            [
                'url' => $this->urlField->toString()
            ],
            $delaySec
        );
    }

    public function saveResponse(): TableUrlResponses
    {
        $client = $this->client;
        $response = $client->createRequest()
            ->setUrl(Url::to($this->urlField->toString(), true))
            ->send();
        $record = new TableUrlResponses([
            'statusCode' => $response->getStatusCode(),
            'headers' => json_encode($response->getHeaders()->toArray()),
            'content' => $response->getContent(),
            'url' => $this->urlField->toString()
        ]);
        $record->save();
        return $record;
    }

    /**
     * вернет структуру объекта.
     * @return IField
     */
    public function struct(): IField
    {
        return $this->urlField;
    }
}