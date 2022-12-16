<?php
declare(strict_types=1);

namespace app\controllers;

use app\objects\forms\FieldByForm;
use app\objects\forms\UrlForm;
use app\objects\urls\WebPage;
use yii\rest\Controller;

class KmaController extends Controller
{
    public function actionAddUrl(){
        $url = new WebPage(
            new FieldByForm(
                new UrlForm(),
                'url'
            )
        );
        $url->sendToValidation();
    }
}