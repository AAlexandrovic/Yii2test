<?php

namespace app\controllers;

use yii\web\Controller;

class CustomController extends Controller
{
    protected function setMeta($title = Null)
    {
        $this->view->title = $title;
    }


}