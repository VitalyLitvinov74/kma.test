<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\UrlForm;
use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;
use vloop\entities\exceptions\NotValidatedFields;

class UrlList implements WeUrls
{

    /**
     * @param UrlForm $urlForm
     * @return TableUrlResponses
     * @throws NotValidatedFields
     */
    public function add(Form $urlForm): TableUrlResponses
    {
        $fields = $urlForm->validatedFields();
        $record = new TableUrlResponses();
        $record->setAttributes($fields);
        $record->save();
        return $record;
    }
}