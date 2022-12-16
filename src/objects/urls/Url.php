<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\FieldByForm;
use app\objects\forms\IField;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\tables\TableUrlResponses;
use Exception;
use vloop\entities\contracts\Form;
use yii\db\ActiveRecord;

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

    public function sendToValidation(int $delaySec = 0): void
    {
        $this->queue->putIn(
            [
                'url' => $this->urlField->toString()
            ],
            $delaySec
        );
    }

    public function saveResponse(Form $pageData): TableUrlResponses
    {
        $fields = $pageData->validatedFields();
        $record = new TableUrlResponses();
        $record->load($fields, '');
        $record->save();
        return $record;
    }
}