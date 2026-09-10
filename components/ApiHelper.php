<?php
namespace app\components;

use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;
class ApiHelper
{
    public static function makeApiCall($apiName='', $method = 'GET', $params = [], $headers = [])
    {   
        $url =  Yii::$app->params['apipath'].$apiName;
        $client = new Client();
        $response = $client->createRequest()
            ->setHeaders($headers)
            ->setMethod($method)
            ->setUrl($url)
            ->setData($params)
            ->send();

        if ($response->isOk) {
            return Json::decode($response->content);
        } else {
            // Handle API call error, you can throw an exception or return false, etc.
            return false;
        }
    }
}
?>