<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\IField;
use app\tables\TableUrlResponses;

interface IWebPage
{
    public function sendToValidation(): void;

    public function saveResponse(): TableUrlResponses;
}