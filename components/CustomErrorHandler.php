<?php

namespace app\components;

use Yii;
use yii\base\ErrorHandler;

class CustomErrorHandler extends ErrorHandler
{
    public function renderException($exception)
    {
        if ($this->discardExistingOutput) {
            $this->clearOutput();
        }
        $BodyParams = (!empty(Yii::$app->request->getBodyParams())) ? json_encode(Yii::$app->request->getBodyParams()) : '';
        $headers = Yii::$app->request->headers;
        $headerArray = [];
        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                $headerArray[$name] = $value;
            }
        }
        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            //'trace' => $exception->getTrace(),
        ];


        $headers = (!empty($headerArray)) ? json_encode($headerArray) : '';
        $errors = (!empty($data)) ? json_encode($data) : '';
        //echo "<pre>"; print_r($errors); exit;
        $final_msg = '';
        $final_msg = $final_msg . "<b>Api Url :</b>" . Yii::$app->request->url . "<br>";
        $final_msg = $final_msg . "<b>Body Params :</b>" . $BodyParams . "<br>";
        $final_msg = $final_msg . "<b>Headers :</b>" . $headers . "<br>";
        $final_msg = $final_msg . "<b>Errors :</b>" . $errors . "<br>";
        //echo "<pre>"; print_r(json_encode($final_msg)); exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://yellowpanther.webhook.office.com/webhookb2/f43ea93a-21d2-46c1-915c-71e7a2018463@1a1b73ea-8b97-402c-b4d0-15f0f3b7ac00/IncomingWebhook/5a403b89d54d40b3ba699de7c720f3ea/10983ad6-4c34-4c75-8c2c-2485ea69c96b',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"text":' . json_encode($final_msg) . '}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return true;
        // echo "<pre>"; print_r($response); exit;

        // if ($exception instanceof \yii\web\HttpException && $exception->statusCode === 500) {
        //     // Send an email with the error details.
        //     echo "<pre>"; print_r(Yii::$app->request->getBodyParams()); exit;
        // }
        // else{
        //     echo "<pre>"; print_r("Yes"); exit;
        // }

        //parent::handleException($exception);
    }
}
