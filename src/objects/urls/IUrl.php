<?php
declare(strict_types=1);

namespace app\objects\urls;

interface IUrl
{
    public function sendToValidation(): void;
}