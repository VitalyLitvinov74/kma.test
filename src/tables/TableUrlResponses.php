<?php
declare(strict_types=1);

namespace app\tables;

use yii\db\ActiveRecord;

/**
 *
 * @property int $id [int(11)]
 * @property int $statusCode [int(11)]
 * @property string $headers [varchar(255)]
 * @property string $content [varchar(255)]
 */
class TableUrlResponses extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'url_responses';
    }
}