<?php
declare(strict_types=1);

namespace app\objects\urls;

use app\objects\forms\IField;
use app\tables\TableUrlResponses;
use vloop\entities\contracts\Form;

interface IWebPage
{
    public function sendToValidation(int $delaySec = 0): void;

    public function saveResponse(): TableUrlResponses;

    /**
     * вернет структуру объекта.
     * @return IField
     */
    public function struct(): IField;
}