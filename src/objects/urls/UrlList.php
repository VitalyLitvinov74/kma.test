<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

class UrlList implements WeUrls
{

    public function add(Form $urlForm): TableUrlResponses
    {
        $fields = $urlForm->validatedFields();
        $record = new TableUrlResponses($fields);
    }
}