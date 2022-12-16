<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\FieldByForm;
use app\objects\forms\IField;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use Exception;
use vloop\entities\contracts\Form;

class Url implements IUrl
{
    private $urlField;
    private $queue;

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
    }

    public function sendToValidation(): void
    {
        $this->queue->putIn([
            'url' => $this->urlField->toString()
        ]);
    }
}