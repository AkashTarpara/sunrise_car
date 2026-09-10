<?php

namespace app\modules\api\controllers;

use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    //Error Page Display
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if(Yii::$app->user->isGuest){
            $this->layout = "blank";
        }
        if ($exception !== null) {
            $this->layout = "blank";
            return $this->render('error', ['exception' => $exception]);
        }
    }

}
