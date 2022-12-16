<?php
declare(strict_types=1);

namespace app\objects\forms;

use PhpAmqpLib\Message\AMQPMessage;
use vloop\entities\contracts\Form;
use vloop\entities\yii2\AbstractForm;

class FormByMessage implements Form
{
    private $message;

    public function __construct(AMQPMessage $message)
    {
        $this->message = $message;
    }

    public function validatedFields(): array
    {
        return json_decode($this->message->getBody());
    }
}