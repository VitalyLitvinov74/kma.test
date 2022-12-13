<?php
declare(strict_types=1);

namespace app\objects\forms;

use vloop\entities\contracts\Form;
use vloop\entities\exceptions\NotFoundEntity;
use vloop\entities\exceptions\NotValidatedFields;

class FieldByForm implements IField
{
    private $form;
    private $fieldName;

    public function __construct(Form $form, string $fieldName = 'id')
    {
        $this->form = $form;
        $this->fieldName = $fieldName;
    }

    public function toString(): string
    {
        $fields = $this->form->validatedFields();
        if(isset($fields[$this->fieldName])){
            return (string) $fields[$this->fieldName];
        }
        throw new NotFoundEntity('Не существует поля ' . $this->fieldName);
    }
}