<?php
declare(strict_types=1);

namespace app\objects\urls\decorators;

use app\objects\exceptions\UrlNotResponding;
use app\objects\forms\FieldByForm;
use app\objects\queue\queueNames\KmaExchangeName;
use app\objects\queue\queueNames\KmaQueueName;
use app\objects\queue\QueueRabbitMq;
use app\objects\urls\Url;
use app\objects\urls\WeUrls;
use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

class UrlListWithCheckedSource implements WeUrls
{
    private $origin;
    private $queue;

    public function __construct(WeUrls $urls)
    {
        $this->origin = $urls;
        $this->queue = new QueueRabbitMq(
            new KmaExchangeName(),
            new KmaQueueName()
        );
    }

    public function add(Form $form): TableUrlResponses
    {
        $fields = $form->validatedFields();
        $headers = @get_headers($fields['url']);
        if ($headers && strpos($headers[0], '200')) {
            return $this->origin->add($form);
        }
        $this->queue->putIn($fields, 15);
        throw new UrlNotResponding($fields['url']);
    }
}