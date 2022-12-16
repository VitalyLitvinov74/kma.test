<?php
declare(strict_types=1);

namespace app\objects\queue\queueNames;

use app\objects\forms\IField;

/**
 * Аналог константы.
 */
class KmaExchangeName implements IField
{

    public function toString(): string
    {
        return 'kma.exchange';
    }
}