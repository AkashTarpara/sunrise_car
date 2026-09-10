<?php

namespace app\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class PushNotification extends Component
{
  public function send($data, $target, $device_type = '')
  {

    if ($device_type == "Iphone") {
      $server_key = '';
    } else {
      $server_key = '';
    }
    $url = 'https://fcm.googleapis.com/fcm/send';
    //$data['title'] = "RR";
    $data['vibrate'] = 1;
    //$data['sound'] = 1;
    $fields = array();
    if ($device_type == "Iphone") {
      $data['body'] = $data['message'];
      $fields['notification'] = $data;
    } else {
      $fields['data'] = $data;
    }
    $fields['priority'] = "high";
    //$fields['to'] = $target;
    $fields['registration_ids'] = $target;
    $headers = array(
      'Content-Type:application/json',
      'Authorization:key=' . $server_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
      die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
  }

  public function sendNotification($data, $target, $device_type = '')
  {

    if ($device_type == "Iphone") {
      $server_key = '';
    } else {
      $server_key = '';
    }
    //echo "<pre>"; print_r($send_notification_id); exit;
    $url = 'https://fcm.googleapis.com/fcm/send';
    //$data['title'] = "RR";
    $data['vibrate'] = 1;
    //$data['send_notification_id'] = $send_notification_id;
    //$data['sound'] = 1;
    $fields = array();
    if ($device_type == "Iphone") {
      if ($data['type'] == 202 || $data['type'] == 206 || $data['type'] == 210 || $data['type'] == 214 || $data['type'] == 219 || $data['type'] == 224) {
        $data['body'] = '';
        $data['title'] = '';
        $data['sound'] = '';
        //$data['body'] = 'ttttt';
        $fields = array(
          'notification' => $data,
          'priority'          => 'high',
          //'alert'          => '',
          'content_available' => true
        );
        //$data['content-available']=1;
      } else {
        $data['body'] = $data['message'];
        $fields['notification'] = $data;
      }
    } else {
      $fields['data'] = $data;
    }
    $fields['priority'] = "high";
    //$fields['to'] = $target;
    $fields['registration_ids'] = $target;
    //echo "<pre>"; print_r($fields); exit;
    $headers = array(
      'Content-Type:application/json',
      'Authorization:key=' . $server_key
    );
    //echo "<pre>"; print_r($fields); exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
      die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    $data_new = json_decode($result);
    //echo "<pre>"; print_r($data_new); exit;
    return $result;
  }
}
