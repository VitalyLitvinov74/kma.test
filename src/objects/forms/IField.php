<?php
declare(strict_types=1);

namespace app\objects\forms;

interface IField
{
    public function toString(): string;
}