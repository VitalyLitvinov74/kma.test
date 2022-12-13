<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\FieldByForm;
use app\objects\forms\IField;
use app\objects\queue\QueueRabbitMq;
use vloop\entities\contracts\Form;

class Url implements IUrl
{
    private $urlField;
    private $queue;

    public function __construct(IField $urlFromSomewhere)
    {
        $this->urlField = $urlFromSomewhere;
        $this->queue = new QueueRabbitMq(
            'kma.exchange',
            'kma.queue'
        );
    }

    public function sendToValidation(): void
    {
        $this->queue->putIn([
            'url' => $this->urlField->toString()
        ]);
    }
}