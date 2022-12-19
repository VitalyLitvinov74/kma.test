<?php
declare(strict_types=1);

namespace app\controllers;

use app\objects\forms\Field;
use app\objects\forms\FieldByForm;
use app\objects\forms\UrlForm;
use app\objects\urls\WebPage;
use app\tables\TableUrlHeaders;
use app\tables\TableUrlResponses;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class KmaController extends Controller
{
    public function actionAddUrl()
    {
        $url = new WebPage(
            new FieldByForm(
                new UrlForm(),
                'url'
            )
        );
        $url->sendToValidation();
    }

    public function actionGetAll()
    {
        return TableUrlResponses::find()->with('headers')->orderBy(['id' => SORT_DESC])->all();
    }

    public function actionSearchByHeaderValue(string $value)
    {
        return TableUrlResponses::find()
            ->leftJoin('page_headers', 'page_headers.url_id = url_responses.id')
            ->where(['like', 'page_headers.value', $value])
            ->orderBy(['url_responses.id'=>SORT_DESC])
            ->all();
    }
}