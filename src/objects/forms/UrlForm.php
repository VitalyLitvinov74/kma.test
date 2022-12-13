<?php
declare(strict_types=1);

namespace app\objects\forms;

use vloop\entities\yii2\AbstractForm;

class UrlForm extends AbstractForm //этой мой кастомный класс
{
    public $url;

    public function rules(): array
    {
        return [
            ['url', 'required'],
            ['url', 'string']
        ];
    }
}