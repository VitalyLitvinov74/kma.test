<?php
declare(strict_types=1);

namespace app\controllers;

use app\objects\forms\FieldByForm;
use app\objects\forms\UrlForm;
use app\objects\urls\Url;
use yii\rest\Controller;

class KmaController extends Controller
{
    public function actionAddUrl(){
        $url = new Url(
            new FieldByForm(
                new UrlForm(),
                'url'
            )
        );
        $url->sendToValidation();
    }
}