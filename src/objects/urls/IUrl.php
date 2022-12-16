<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

interface IUrl
{
    public function sendToValidation(int $delaySec = 0): void;

    public function saveResponse(Form $pageData): TableUrlResponses;
}