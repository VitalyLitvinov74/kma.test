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
 * @property int $statusCode [int(11)]
 * @property string $content [varchar(255)]
 * @property string $url [varchar(255)]
 * @property TableUrlHeaders[] $headers
 */
class TableUrlResponses extends ActiveRecord
{
    use SaveRelationsTrait;

    public static function tableName(): string
    {
        return 'url_responses';
    }

    public function extraFields(): array
    {
        return [
            'headers'
        ];
    }

    public function behaviors()
    {
        return [
            'saveRelations'=>[
                'class'=>SaveRelationsBehavior::class,
                'relations' => [
                    'headers'=>['cascadeDelete'=>true]
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            ['headers', 'safe']
        ];
    }

    public function getHeaders(): ActiveQuery
    {
        return $this->hasMany(TableUrlHeaders::class, ['url_id' => 'id']);
    }
}