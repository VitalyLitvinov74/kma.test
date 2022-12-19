<?php
declare(strict_types=1);

namespace app\tables;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id [int(11)]
 * @property int $url_id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $value [varchar(255)]
 * @property TableUrlResponses $body
 */
class TableUrlHeaders extends ActiveRecord
{

    public static function tableName()
    {
        return 'page_headers';
    }

    public function rules()
    {
        return [
            [['name', 'value'], 'safe']
        ];
    }

    public function getBody(): ActiveQuery{
        return $this->hasOne(TableUrlResponses::class, ['id'=>'url_id']);
    }
}