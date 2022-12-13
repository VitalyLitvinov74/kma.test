<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

interface WeUrls
{
    public function add(Form $form): TableUrlResponses;
}