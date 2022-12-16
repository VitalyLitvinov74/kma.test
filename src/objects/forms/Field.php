<?php
declare(strict_types=1);

namespace app\objects\forms;

class Field implements IField
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}