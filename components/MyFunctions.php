<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\FileHelper;
use \Eventviva\ImageResize;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Error\Card;
use app\models\StripeCardFingerprint;
use yii\web\Controller;

use app\models\Appuser;
use app\models\Appuserdevicesinfo;
use app\models\Configurations;
use app\models\Generalsetting;


use Exception;
use Twilio\Rest\Client;
use DateTime;
use DatePeriod;
use DateInterval;
use Da\QrCode\QrCode;
use Intervention\Image\ImageManagerStatic as Image;
use yii\helpers\Json;
use Aws\S3\S3Client;
use yii\helpers\Inflector;
use yii\helpers\Console;
use Mpdf\Tag\Em;

//require_once(Yii::getAlias('@vendor').'/stripe/init.php');
class MyFunctions extends Component
{

  //Get File Of external User
  public function UploadSocialImage($image_url)
  {
    $file_name = '';
    if (!empty($image_url) || $image_url != '') {
      try {
        $content = file_get_contents($image_url);
        $rand_name = Yii::$app->MyFunctions->random_string(32);
        $file_name = "uploads/images/user/" . $rand_name . '.jpg';
        $fp = fopen($file_name, "w");
        fwrite($fp, $content);
        fclose($fp);
      } catch (Exception $e) {
        return "";
      }
    }
    return $file_name;
  }

  //get google address on lat long
  public function getaddress($lat, $lng)
  {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&sensor=false';
    $json = @file_get_contents($url);
    $data = json_decode($json);
    $status = $data->status;
    if ($status == "OK")
      return $data->results[0]->formatted_address;
    else
      return "";
  }

  //location info geocode 
  public function getGeolocation($lat, $lng)
  {
    $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&key=AIzaSyB397jqkAas3pMMTvfzRWmh7oVUW5H9eCM';
    $file_contents = file_get_contents($request);
    $json_decode = json_decode($file_contents);
    $status = $json_decode->status;
    if ($status != "OK")
      return "";
    else
      /* print_r($json_decode = json_decode($file_contents));
      exit;
      */
      $city = "";
    $cityname1 = "";
    $cityname2 = "";
    $cityname3 = "";

    if (!empty($json_decode->results[0]->address_components)) {
      foreach ($json_decode->results[0]->address_components as $key => $gvalue) {

        if ($gvalue->types[0] == 'administrative_area_level_2') {
          $cityname1 = $gvalue->long_name;
        }
        if ($gvalue->types[0] == 'locality') {
          $cityname2 = $gvalue->long_name;
        }
        if ($gvalue->types[0] == 'political') {
          $cityname3 = $gvalue->long_name;
        }
      }
      if (!empty($cityname3)) {
        $city = $cityname3 . ',' . $cityname1;
      } else if (!empty($cityname2)) {
        $city = $cityname2 . ',' . $cityname1;
      } else {
        $city = $cityname1;
      }
      return $city;
    }
  }

  //resize Image 
  public function resize_image($file, $w, $h, $crop = FALSE)
  {
    //Example https://github.com/eventviva/php-image-resize
    require_once(Yii::getAlias('@vendor') . '/CustomLibrary/ImageResize/lib/ImageResize.php');
    $thumbnail_path = 'uploads/post/thumbnails/';
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    $thumb = $thumbnail_path . $rand_name . '.jpg';
    $image = new ImageResize($file);
    $image->resize(400, 400, $allow_enlarge = True);
    if ($image->save($thumb)) {
      return $thumb;
    }
  }

  //generate vieos thabnail
  public function GenerateVidoeThumbnailold($video_file_path)
  {
    $thumbnail_path = 'uploads/images/ourwork/thumbnails/';
    $second             = 1;
    $thumbSize       = '400x400';
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    $thumb = $thumbnail_path . $rand_name . '.jpg';

    //$cmd = "/usr/bin/ffmpeg -i {$video_file_path} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumb} 2>&1";
    $cmd = "/usr/bin/ffmpeg -i {$video_file_path} -deinterlace -an -ss {$second} -t 00:00:01   -r 1 -y -vcodec mjpeg -f mjpeg {$thumb} 2>&1";
    //$res = exec("ffprobe -i $video_file_path -show_entries format=duration -v quiet -of csv='p=0'");;
    //echo "<pre>"; print_r($res); exit;
    exec($cmd, $output, $retval);
    if ($retval) {

      return "";
      // echo "<pre>";
      //     print_r($output);
      //     print_r($retval);
      //     exit;
      //echo 'error in generating video thumbnail';
    } else {
      return $thumb;
    }
  }

  /* public function compress_image_new($tempPath,$filePath ,$imagePath,$cropDetailsJson){

    //echo "<pre>";print_r($tempPath);exit;
    $cropDetails = Json::decode($cropDetailsJson);
    $setting=Generalsetting::find()->one();
    $image_compress_quality_percentage=(!empty($setting->image_compress_quality_percentage))?(int)$setting->image_compress_quality_percentage:70;
    //image_compress_quality_percentage
    $x = round($cropDetails['x']);
    $y = round($cropDetails['y']);
    $width = round($cropDetails['width']);
    $height = round($cropDetails['height']);
    $scaleX = round($cropDetails['scaleX']);
    $scaleY = round($cropDetails['scaleY']);
    if ($tempPath) {
      $path = $filePath;
      $filename = $imagePath;

      // Resize and save the compressed image
      Image::configure(array('driver' => 'gd')); // Set driver if not set in config
      Image::make($tempPath)->crop($width, $height, $x, $y)->resize(800, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
      })->save($filename, $image_compress_quality_percentage); // 70 is the quality percentage

      // You can then save the filename to your model or perform further operations
      return $filename;

    }
  } */

  public function compress_image_new($tempPath, $filePath, $imagePath, $cropDetailsJson)
  {
    // echo '<pre>';
    // print_r($tempPath);
    // exit;
    // Decode crop details JSON
    $cropDetails = Json::decode($cropDetailsJson);

    // Retrieve image compress quality percentage from settings
    $setting = Generalsetting::find()->one();
    $image_compress_quality_percentage = (!empty($setting->image_compress_quality_percentage)) ? (int)$setting->image_compress_quality_percentage : 70;

    // Extract crop details
    $x = round($cropDetails['x']);
    $y = round($cropDetails['y']);
    $width = round($cropDetails['width']);
    $height = round($cropDetails['height']);

    // Check if temporary path exists
    if ($tempPath) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE); // Create a fileinfo resource
      $mimeType = finfo_file($finfo, $tempPath); // Get the MIME type of the file
      finfo_close($finfo); // Close the fileinfo resource
      // Resize and compress the image
      Image::configure(['driver' => 'gd']); // Set driver if not set in config
      $image = Image::make($tempPath)
        ->crop($width, $height, $x, $y)
        ->resize(800, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })
        ->encode(null, $image_compress_quality_percentage);

      // Initialize AWS S3 client
      $s3 = new S3Client([
        'version' => 'latest',
        'region' => Yii::$app->params['s3_region'],
        'credentials' => [
          'key' => Yii::$app->params['s3_key'],
          'secret' => Yii::$app->params['s3_secret'],
        ],
      ]);

      // Upload the compressed image to S3
      try {
        $result = $s3->putObject([
          'Bucket' => Yii::$app->params['s3_bucket_name'],
          'Key' => $imagePath, // S3 key where the image will be stored
          'Body' => $image,
          'ContentType' => $mimeType,
          //'ACL' => 'public-read', // Optionally set the ACL (access control list)
        ]);
      } catch (Exception $e) {
        Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $e->getMessage()]);
        // Handle S3 upload exception
        //return null;
      }
    }
    return $imagePath;
  }


  /* public function GenerateImageThumbnail($imagePath,$thumbnailImagepath,$width,$height){
    $originalImagePath = Yii::$app->params['ImagePath'].$imagePath;
    $extension = pathinfo($originalImagePath, PATHINFO_EXTENSION);
    $path = $thumbnailImagepath;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
        chmod($path, 0777); 
    }

    $name = Yii::$app->MyFunctions->random_string(32);
    // Path to save the thumbnail
    $thumbnailPath = $path.$name.'.'.$extension;

    // Width and height of the thumbnail
    $thumbnailWidth = $width;
    $thumbnailHeight = $height;

    // Load the image
    $image = Image::make($originalImagePath);

    // Resize the image to create thumbnail
    $image->fit($thumbnailWidth, $thumbnailHeight);

    // Save the thumbnail
    $image->save($thumbnailPath);
    
    return  $thumbnailPath;
  } */

  public function GenerateImageThumbnail($originalImagePath, $thumbnailImagepath, $width, $height)
  {
    // Generate a random name for the thumbnail
    $name = Yii::$app->MyFunctions->random_string(32);

    // Get the extension of the original image
    $extension = pathinfo($originalImagePath, PATHINFO_EXTENSION);

    // Path to save the thumbnail
    $thumbnailPath = $thumbnailImagepath . $name . '.' . $extension;

    // Load the AWS S3 client
    $awsConfig = [
      'version' => 'latest',
      'region' => Yii::$app->params['s3_region'],
      'credentials' => [
        'key' => Yii::$app->params['s3_key'],
        'secret' => Yii::$app->params['s3_secret'],
      ],
    ];
    $s3 = new S3Client($awsConfig);

    // Retrieve the original image from S3
    $getObjectResponse = $s3->getObject([
      'Bucket' => Yii::$app->params['s3_bucket_name'],
      'Key' => $originalImagePath,
    ]);
    // Create an Intervention Image instance from the retrieved image
    $image = Image::make($getObjectResponse['Body']);

    // Resize the image to create the thumbnail
    $image->fit($width, $height);

    // Upload the thumbnail image back to S3
    $result = '';
    try {
      $result = $s3->putObject([
        'Bucket' => Yii::$app->params['s3_bucket_name'],
        'Key' => $thumbnailPath,
        'Body' => (string) $image->encode(), // Convert the image to binary string
        'ContentType' => $getObjectResponse['ContentType'],
        //'ACL' => 'public-read', // Optionally set the ACL (access control list)
      ]);
    } catch (Exception $e) {
      Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $e->getMessage()]);
      // Handle the error as needed
    }
    return $thumbnailPath;
  }
  //generate vieos thabnail
  public function GenerateVidoeThumbnail($video_file_path, $filepath)
  {
    $awsConfig = [
      'version' => 'latest',
      'region' => Yii::$app->params['s3_region'],
      'credentials' => [
        'key' => Yii::$app->params['s3_key'],
        'secret' => Yii::$app->params['s3_secret'],
      ],
    ];
    $s3 = new S3Client($awsConfig);
    $thumbnail_path = $filepath;
    /* if (!is_dir($thumbnail_path)) {
      mkdir($thumbnail_path, 0777, true);
      chmod($thumbnail_path, 0777);
    } */
    $second = 1;
    $thumbSize = '400x400';
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    $thumb = $thumbnail_path . $rand_name . '.jpg';
    $new_video_file_path = Yii::$app->params['ImagePath'] . $video_file_path;
    //$new_thumb = Yii::$app->params['ImagePath'] . $thumb;
    $localimagepath = 'uploads/thumbnail/' . $rand_name . '.jpg';
    //$cmd = "/usr/bin/ffmpeg -i {$video_file_path} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumb} 2>&1";
    $cmd = "/usr/bin/ffmpeg -i {$new_video_file_path} -deinterlace -an -ss {$second} -t 00:00:01   -r 1 -y -vcodec mjpeg -f mjpeg {$localimagepath} 2>&1";
    //$res = exec("ffprobe -i $video_file_path -show_entries format=duration -v quiet -of csv='p=0'");;
    //echo "<pre>"; print_r($res); exit;
    exec($cmd, $output, $retval);

    if ($retval) {

      return "";
      // echo "<pre>";
      // print_r($output);
      // print_r($retval);
      // exit;
      //echo 'error in generating video thumbnail';
    } else {

      $imageContent = file_get_contents(Yii::$app->params['backend_image_path'] . $localimagepath);

      if ($imageContent !== false) {
        $ext = pathinfo(Yii::$app->params['backend_image_path'] . $localimagepath, PATHINFO_EXTENSION);

        $name = Yii::$app->MyFunctions->random_string(32);
        $savePath = $thumb;
        $headers = get_headers(Yii::$app->params['backend_image_path'] . $localimagepath, 1);
        // Extract content type from headers
        $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
        // Upload the compressed image to S3
        try {
          $result = $s3->putObject([
            'Bucket' => Yii::$app->params['s3_bucket_name'],
            'Key' => $savePath, // S3 key where the image will be stored
            'Body' => $imageContent,
            'ContentType' => $contentType,
            //'ACL' => 'public-read', // Optionally set the ACL (access control list)
          ]);
          unlink(Yii::getAlias("@app") . "/" . $localimagepath);
        } catch (Exception $e) {
          Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $e->getMessage()]);
          // Handle S3 upload exception
          //return null;
        }

        return $savePath;
        //return $thumb;
      }
    }
  }

  public function GenerateVidoeThumbnailNew($video_file_path, $filepath)
  {
    $awsConfig = [
      'version' => 'latest',
      'region' => Yii::$app->params['s3_region'],
      'credentials' => [
        'key' => Yii::$app->params['s3_key'],
        'secret' => Yii::$app->params['s3_secret'],
      ],
    ];
    $s3 = new S3Client($awsConfig);
    $thumbnail_path = $filepath;
    /* if (!is_dir($thumbnail_path)) {
      mkdir($thumbnail_path, 0777, true);
      chmod($thumbnail_path, 0777);
    } */
    $second = 1;
    $thumbSize = '400x400';
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    $thumb = $thumbnail_path . $rand_name . '.jpg';
    $new_video_file_path = $video_file_path;
    //$new_thumb = Yii::$app->params['ImagePath'] . $thumb;
    $localimagepath = 'uploads/thumbnail/' . $rand_name . '.jpg';
    //$cmd = "/usr/bin/ffmpeg -i {$video_file_path} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumb} 2>&1";
    $cmd = "/usr/bin/ffmpeg -i {$new_video_file_path} -deinterlace -an -ss {$second} -t 00:00:01   -r 1 -y -vcodec mjpeg -f mjpeg {$localimagepath} 2>&1";
    //$res = exec("ffprobe -i $video_file_path -show_entries format=duration -v quiet -of csv='p=0'");;
    //echo "<pre>"; print_r($res); exit;
    exec($cmd, $output, $retval);

    if ($retval) {

      return "";
      // echo "<pre>";
      // print_r($output);
      // print_r($retval);
      // exit;
      //echo 'error in generating video thumbnail';
    } else {

      $imageContent = file_get_contents(Yii::$app->params['backend_image_path'] . $localimagepath);

      if ($imageContent !== false) {
        $ext = pathinfo(Yii::$app->params['backend_image_path'] . $localimagepath, PATHINFO_EXTENSION);

        $name = Yii::$app->MyFunctions->random_string(32);
        $savePath = $thumb;
        $headers = get_headers(Yii::$app->params['backend_image_path'] . $localimagepath, 1);
        // Extract content type from headers
        $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
        // Upload the compressed image to S3
        try {
          $result = $s3->putObject([
            'Bucket' => Yii::$app->params['s3_bucket_name'],
            'Key' => $savePath, // S3 key where the image will be stored
            'Body' => $imageContent,
            'ContentType' => $contentType,
            //'ACL' => 'public-read', // Optionally set the ACL (access control list)
          ]);
          unlink(Yii::getAlias("@app") . "/" . $localimagepath);
        } catch (Exception $e) {
          Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $e->getMessage()]);
          // Handle S3 upload exception
          //return null;
        }

        return $savePath;
        //return $thumb;
      }
    }
  }

  //generate vieos thabnail
  public function CopyImage($filePath, $path)
  {
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $name = Yii::$app->MyFunctions->random_string(32);
    $destinationFilePath = $path . $name . '.' . $extension;
    if (!copy($filePath, $destinationFilePath)) {
      return "";
    } else {
      return $destinationFilePath;
    }
  }

  public function GenerateVidoeDuration($video_file_path)
  {
    $res = exec("ffprobe -i $video_file_path -show_entries format=duration -v quiet -of csv='p=0'");
    return $res;
  }

  public function getTimestamp($date)
  {
    $date = date_create($date);
    return (float)round(date_timestamp_get($date) * 1000);
  }

  public function TimezoneDateTime($datetime)
  {
    if (((Yii::$app->user->identity) ? Yii::$app->user->identity->time_zone : 'UTC') != 'UTC') {
      $datetime = new \DateTime($datetime, new \DateTimeZone('UTC'));
      $la_time = new \DateTimeZone(Yii::$app->user->identity->time_zone);
      $datetime->setTimezone($la_time);
      return $datetime->format('Y-m-d h:i A');
    }
    return $datetime;
  }
  public function TimezoneDate($datetime)
  {
    if (((Yii::$app->user->identity) ? Yii::$app->user->identity->time_zone : 'UTC') != 'UTC') {
      $datetime = new \DateTime($datetime, new \DateTimeZone('UTC'));
      $la_time = new \DateTimeZone(Yii::$app->user->identity->time_zone);
      $datetime->setTimezone($la_time);
      return $datetime->format('Y-m-d');
    }
    return $datetime;
  }

  public function getUktime()
  {
    date_default_timezone_set('Europe/London');
    $sTime = date("Y-m-d H:i:s");
    return $sTime;
  }


  /*public function ApiResponse($status,$message,$data)
  {
    $this->JsonPrint(array('status'=>$status,'message'=>$message,'data'=>$data));
    exit;
  }*/

  public function JsonPrint($data, $header = 200)
  {
    Yii::$app->MyFunctions->setHeader($header);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
  }

  public function checkfile($input)
  {
    $ext = array('mpg', 'wma', 'mov', 'flv', 'mp4', 'mp3', 'avi', 'qt', 'wmv', 'rm');
    $extfile = substr($input['name'], -4);
    $extfile = explode('.', $extfile);
    $good = array();
    $extfile = $extfile[1];
    if (in_array($extfile, $ext)) {
      $good['safe'] = true;
      $good['ext'] = $extfile;
    } else {
      $good['safe'] = false;
    }
    return $good;
  }

  public function getModelErrors($model, $is_display = "N")
  {
    $errors = '';
    if ($model->hasErrors()) {
      foreach ($model->getErrors() as $key => $value) {
        foreach ($value as $k => $v) {
          $errors .= $v;
        }
      }
    } else {
      $errors .= "Something went's wrong try again.";
    }
    if ($is_display == "Y") {
      $this->JsonPrint(array('status' => 0, 'message' => Yii::t('app', $errors)));
    } else {
      return $errors;
    }
  }

  public function random_string($length)
  {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));
    for ($i = 0; $i < $length; $i++) {
      $key .= $keys[array_rand($keys)];
    }
    return $key;
  }
  public function GenerateOTP($length)
  {
    $key = '';
    $keys = range(0, 9);
    for ($i = 0; $i < $length; $i++) {
      $key .= $keys[array_rand($keys)];
    }
    return $key;
  }

  public function GenerateOTPNew($length)
  {
    $key = '';
    $keys = range(1111, 9999);
    for ($i = 0; $i < $length; $i++) {
      $key .= $keys[array_rand($keys)];
    }
    return $key;
  }

  public function orderNumber($id)
  {
    $bs_id = $id;
    $idsize = strlen($bs_id);
    $totdigit = 8;
    $digit = $totdigit - $idsize;
    $odrid = "";
    for ($i = 0; $i < $digit; $i++) {
      $odrid = $odrid . rand(1, 9);
    }
    return $job_id = Yii::$app->cache->get('ORDER_NO_PREFIX') . $odrid . $bs_id;
  }

  public function encode($string)
  {
    return urlencode(base64_encode($string));
  }
  public function decode($string)
  {
    return base64_decode(urldecode($string));
  }

  public function upload_file($file, $path, $filename = '')
  {
    $extension = pathinfo(basename($file["name"]), PATHINFO_EXTENSION);
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    if (!empty($filename)) {
      $rand_name = str_replace(' ', '_', $filename) . '-' . mt_rand(100000, 999999);
    }
    $file_name = $path . $rand_name . '.' . $extension;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }
    if (!move_uploaded_file($file["tmp_name"], $file_name)) {
      return false;
    } else {
      if ($filename) {
        return array('filename' => $rand_name, 'file' => $file_name);
      }
      return $file_name;
    }
  }


  public function uploadFileWithName($file, $path, $filename = '')
  {
    $extension = pathinfo(basename($file["name"]), PATHINFO_EXTENSION);
    $rand_name = Yii::$app->MyFunctions->random_string(32);
    if (!empty($filename)) {
      $rand_name = str_replace(' ', '_', $filename) . '-' . mt_rand(100, 999);
    }
    $file_name = $path . $rand_name . '.' . $extension;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }
    if (!move_uploaded_file($file["tmp_name"], $file_name)) {
      return false;
    } else {
      return $file_name;
    }
  }



  // public function upload_file($file,$path,$filename='') {
  //     $extension = pathinfo(basename($file["name"]),PATHINFO_EXTENSION);
  //     $rand_name=Yii::$app->MyFunctions->random_string(32);
  //     $file_name=$path . $rand_name . '.' . $extension;

  //     if (!is_dir($path)) {
  //         mkdir($path, 0777, true);
  //         chmod($path, 0777); 
  //     }
  //     if (!move_uploaded_file($file["tmp_name"], $file_name)) {
  //         return false;
  //         //echo "Sorry, there was an error uploading your file.";
  //     }else{
  //       if($filename){
  //         return array('filename' =>$rand_name ,'file'=> $file_name);
  //       }
  //       return $file_name;
  //     }
  // }


  public function setHeader($status)
  {
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    $content_type = "application/json; charset=utf-8";

    header($status_header);
    header('Content-type: ' . $content_type);
    //header('X-Powered-By: ' . "Capermint Technology <caperminttechnology.com>");
  }
  private function _getStatusCodeMessage($status)
  {
    $codes = array(
      200 => 'OK',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
  }

  public function GetDynamicmodule($id)
  {
    $model = Dynamicmodule::find()->Where(["api_name" => $id, 'status' => 'Active'])->one();
    if (!empty($model)) {
      if ($model->sort_type == 'SORT_DESC') {
        $data['sort_type'] = SORT_DESC;
      } else {
        $data['sort_type'] = SORT_ASC;
      }

      $data['display_limit'] = $model->display_limit;
      //echo "<pre>"; print_r($data); exit;
      return $data;
    } else {
      $data['sort_type'] = SORT_DESC;
      $data['display_limit'] = '10';
      return $data;
    }
  }

  public function GetConfigurations($value)
  {
    $model = Configurations::find()->Where(["name" => $value, 'status' => 'Active'])->one();
    if (!empty($model)) {
      return $model->value;
    } else {
      return '#ffffff';
    }
  }

  public function GetUser($auth_key)
  {
    $model = Appuserdevicesinfo::find()->Where(["auth_key" => $auth_key])->one();
    if ($model && $model->appuser_id !== null) {
      if ($model && $model->appuser->is_deleted == "Yes") {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 3, 'message' => Yii::t('app', "An account with this email address does not exist. Please sign up to create a new account.")));
      }

      if ($model && $model->appuser->status == "Inactive") {
        Yii::$app->MyFunctions->JsonPrint(array('status' => 4, 'message' => Yii::t('app', "You account is inactive. Please contact admin for more information.")));
      }
      //if ($model->appuser->role == '3') {
      return $model->appuser;
      // } else {
      //   Yii::$app->MyFunctions->JsonPrint(array('status' => 3, 'message' => Yii::t('app', 'Unauthorized access')));
      // }
    } else {
      $this->setHeader(200);
      echo json_encode(array('status' => 3, 'message' => Yii::t('app', 'User not Found.')), JSON_PRETTY_PRINT);
      exit;
    }
  }

  public function GetSubAdminUser($auth_key)
  {
    $model = Appuserdevicesinfo::find()->Where(["auth_key" => $auth_key])->one();
    if ($model && $model->sub_admin_id !== null) {
      return $model->subadminuser;
    } else {
      $this->setHeader(200);
      echo json_encode(array('status' => 3, 'message' => Yii::t('app', 'User not Found.')), JSON_PRETTY_PRINT);
      exit;
    }
  }

  public function GetMasterAdminUser($auth_key)
  {
    $model = Adminuser::find()->Where(["auth_key" => $auth_key])->one();
    if ($model && $model->admin_id !== null) {
      return $model;
    } else {
      $this->setHeader(200);
      echo json_encode(array('status' => 3, 'message' => Yii::t('app', 'User not Found.')), JSON_PRETTY_PRINT);
      exit;
    }
  }

  public function getDatesFromRange($start, $end, $format = 'Y-m-d')
  {
    $array = array();
    $interval = new DateInterval('P1D');
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach ($period as $date) {
      $array[] = $date->format($format);
    }
    return $array;
  }

  public function getMonthsFromRange($startDate, $endDate)
  {
    //echo "<pre>"; print_r($startDate); exit;
    $months = array();
    while (strtotime($startDate) <= strtotime($endDate)) {
      $months[] = date('F', strtotime($startDate));
      $startDate = date('01 M Y', strtotime($startDate .
        '+ 1 month'));
    }
    return $months;
  }

  public function DeviceDetail($user_id, $device)
  {

    $userdevice = DeviceDetails::find()->where(['devices_id' => $device['devices_id']])->One();
    $auth_key = "";
    if (!empty($userdevice)) {
      $userdevice->user_id = $user_id;
      $userdevice->devices_type = $device['devices_type'];
      $userdevice->devices_token = $device['devices_token'];
      $userdevice->app_version = $device['app_version'];
      $userdevice->devices_name = $device['devices_name'];
      $userdevice->devices_id = $device['devices_id'];
      $userdevice->save();
      $auth_key = $userdevice->auth_key;
    } else {
      $newdevices = new DeviceDetails();
      $newdevices->user_id = $user_id;
      $newdevices->devices_type = $device['devices_type'];
      $newdevices->devices_token = $device['devices_token'];
      $newdevices->app_version = $device['app_version'];
      $newdevices->devices_name = $device['devices_name'];
      $newdevices->devices_id = $device['devices_id'];
      $newdevices->auth_key = $this->random_string(32);
      $newdevices->save();

      $auth_key = $newdevices->auth_key;
    }
    return $auth_key;
  }
  //SMS Code In Twilio 
  public function sendTwilioSMS($model)
  {
    //echo "<pre>"; print_r($model); exit;
    $twilioService = Yii::$app->Yii2Twilio->initTwilio();
    try {
      $message = $twilioService->account->messages->create(
        "+" . $model->phone_code . $model->phone_number, // to 
        array(
          "from" => "+441173253498",
          "body" => "Welcome to the Rajasthan Royals family, please use the OTP code " . $model->otp . " to login and experience the IPL's best app! #HallaBol"

        )
      );
    } catch (\Twilio\Exceptions\RestException $e) {
      //echo $e->getMessage();
    }
  }

  public function sendSMS($contact, $msg)
  {
    $login_id = Yii::$app->params['Smsuser'];
    $password = Yii::$app->params['Password'];
    $api_url = "https://logonutility.in/app/getkey/" . $login_id . "/" . $password;

    $response_key = file_get_contents($api_url);
    if (!empty($response_key)) {
      $res_key = explode("/", $response_key);

      if ($res_key[0] == "API-KEY") {
        $api_key = $res_key[1];

        $api_key = $api_key;

        $contacts = $contact;

        $from = 'ESUGGI';

        $sms_text = urlencode($msg);

        /*$ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "https://logonutility.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=10988&routeid=20&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
        curl_close($ch);*/

        //$api_key = $api_key;
        //$contacts = $contacts;
        //$from = 'ESUGGI';
        //$sms_text = urlencode($msg);
        $api_url = "https://logonutility.in/app/smsapi/index.php?key=" . $api_key . "&campaign=10988&routeid=20&type=text&contacts=" . $contacts . "&senderid=" . $from . "&msg=" . $sms_text;

        $response = file_get_contents($api_url);
        //exit;
        return $response;
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  public function GenerateQRCode($cass_number, $filepath = 'uploads/images/user/qrcode/')
  {
    $qrCode = (new QrCode($this->encode($cass_number)))
      ->setSize(512)
      ->setMargin(5)
      ->useForegroundColor(0, 0, 0);

    $path = $filepath;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
    }

    // now we can display the qrcode in many ways
    // saving the result to a file:
    $full_path = $path . Yii::$app->MyFunctions->random_string(50) . '.png';

    $qrCode->writeFile($full_path);
    return $full_path;
  }

  public function DownloadimageFromUrl($imageUrl, $saveImagePath, $model)
  {

    $imageContent = file_get_contents($imageUrl);

    if ($imageContent !== false) {
      $ext = pathinfo($imageUrl, PATHINFO_EXTENSION);
      $path = $saveImagePath;
      // if (!is_dir($path)) {
      //   mkdir($path, 0777, true);
      //   chmod($path, 0777); 
      // }
      if ($model->thumbnail_image) {
        $url = $model->thumbnail_image;
        $check_image = Yii::$app->MyFunctions->CheckAndDeleteS3Image($url);
      }

      $s3 = new S3Client([
        'version' => 'latest',
        'region' => Yii::$app->params['s3_region'],
        'credentials' => [
          'key' => Yii::$app->params['s3_key'],
          'secret' => Yii::$app->params['s3_secret'],
        ],
      ]);


      $name = Yii::$app->MyFunctions->random_string(32);
      $savePath = $path . $name . '.' . $ext;
      $headers = get_headers($imageUrl, 1);
      // Extract content type from headers
      $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
      // Upload the compressed image to S3
      try {
        $result = $s3->putObject([
          'Bucket' => Yii::$app->params['s3_bucket_name'],
          'Key' => $savePath, // S3 key where the image will be stored
          'Body' => $imageContent,
          'ContentType' => $contentType,
          //'ACL' => 'public-read', // Optionally set the ACL (access control list)
        ]);
      } catch (Exception $e) {
        Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => $e->getMessage()]);
        // Handle S3 upload exception
        //return null;
      }
      // echo '<pre>';
      // print_r($result);
      // exit;
      //file_put_contents($savePath, $imageContent);

      return $savePath;
    } else {
      return '';
    }
  }

  public function StoreS3Image($image, $filepath = 'uploads/')
  {

    $awsConfig = [
      'version' => 'latest', // Use the latest AWS SDK version
      'region' => Yii::$app->params['s3_region'],
      'credentials' => [
        'key' => Yii::$app->params['s3_key'],
        'secret' => Yii::$app->params['s3_secret'],
      ],
    ];

    $s3 = new S3Client($awsConfig);

    $name = $filepath . Yii::$app->MyFunctions->random_string(50) . '.' . $image->extension;
    // echo '<pre>';
    // print_r($name);
    // exit;
    $bucketName = Yii::$app->params['s3_bucket_name']; // Replace with your S3 bucket name
    $objectKey = $name;   // Replace with the desired object key
    $imageFilePath = $image->tempName;
    $ContentType = $image->type;

    $result = '';

    try {
      $result = $s3->putObject([
        'Bucket' => $bucketName,
        'Key' => $objectKey,
        'SourceFile' => $imageFilePath,
        'ContentType' => $ContentType,
        //'ACL' => 'public-read', // Optional: Set ACL for public read access
      ]);
    } catch (Exception $e) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $e->getMessage()));
      //echo "Error uploading the image to S3: " . $e->getMessage();
    }

    // echo '<pre>';
    // print_r($result);
    // exit;
    /*try {
      $resultnew = $s3->putObjectTagging([
        'Bucket' => $bucketName,
        'Key' => $objectKey,
        'Tagging' => ['TagSet' => $tags],
      ]);
      //return $result;
    } catch (\Exception $e) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $e->getMessage()));
    }*/

    return $name;
  }

  public function CheckAndDeleteS3Image($fileName)
  {

    $awsConfig = [
      'version' => 'latest', // Use the latest AWS SDK version
      'region' => Yii::$app->params['s3_region'],
      'credentials' => [
        'key' => Yii::$app->params['s3_key'],
        'secret' => Yii::$app->params['s3_secret'],
      ],
    ];

    $s3 = new S3Client($awsConfig);

    $bucketName = Yii::$app->params['s3_bucket_name']; // Replace with your S3 bucket name
    try {
      $file_name = $fileName;
      // Check if the file exists in the bucket
      $result = $s3->doesObjectExist($bucketName, $file_name);
      //echo "<pre>";print_r($result);exit;
      if ($result) {
        $s3->deleteObject([
          'Bucket' => $bucketName,
          'Key' => $file_name,
        ]);
      }
    } catch (S3Exception $e) {
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, "message" => $e->getMessage()));
    }
    //return $result;
  }
  public function CheckEmail($email)
  {

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.emailable.com/v1/verify?email=' . $email . '&api_key=live_7303a2793e4ed6dfa157',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpStatusCode == 200) {
      $responseArray = json_decode($response, true);
      if (!empty($responseArray)) {
        $state = (isset($responseArray['state']) && !empty($responseArray['state'])) ? $responseArray['state'] : '';
        if ($state == 'undeliverable') {
          Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', 'Invalid email')));
        }
      }
    }
  }

  public function getTimeDuration($duration)
  {

    //$duration = 0;
    $minutes = floor($duration / 60);
    $seconds = floor($duration % 60);

    // Format seconds to always be two digits
    $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

    // Return the formatted string
    $timeFormat =  "$minutes:$seconds";
    return $timeFormat;
  }

  public function getDatesBetween($startDate, $endDate)
  {
    $dates = [];

    // Create DateTime objects for start and end dates
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    // Add one day to the end date to include it in the period
    $end->modify('+1 day');

    // Create DateInterval of 1 day
    $interval = new DateInterval('P1D');

    // Create DatePeriod
    $datePeriod = new DatePeriod($start, $interval, $end);

    // Iterate over DatePeriod and add dates to the array
    foreach ($datePeriod as $date) {
      $dates[] = $date->format('Y-m-d');
    }

    return $dates;
  }

  public function getTimeSlots($startTime, $endTime, $duration)
  {
    $timeSlots = [];

    // Create DateTime objects for start and end times
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);

    // Create DateInterval for the duration
    $interval = new DateInterval('PT' . $duration . 'M');

    // Iterate through the time range and generate time slots
    while ($start < $end) {
      $slotStart = clone $start;
      $slotEnd = (clone $start)->add($interval);

      // Ensure the end time of the slot does not exceed the end time range
      if ($slotEnd > $end) {
        break;
      }

      $timeSlots[] = [
        'start' => $slotStart->format('H:i'),
        'end' => $slotEnd->format('H:i')
      ];

      // Move to the next slot
      $start->add($interval);
    }

    return $timeSlots;
  }

  public function getUsersByAgeRange($ageRange)
  {
    $start_age = '';
    $end_age = '';
    $current_date = date('Y-m-d');
    if (!empty($ageRange)) {
      switch ($ageRange) {
        case '12-18':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 12 year'));
          break;
        case '19-24':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          break;
        case '25-30':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          break;
        case '31-36':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          break;
        case '37-42':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          break;
        case '43-49':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 49 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          break;
        case '50+':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 100 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 50 year'));
          break;
      }
    }
    if (!empty($start_age) && !empty($end_age)) {
      $usersCount = Appuserissignup::find()->joinWith(['appuser'])->where(['appuser.role' => '3', 'appuser.user_type' => 'User', 'appuser.is_deleted' => 'No', 'appuser_issignup.devices_type' => 'Web'])->andWhere(['between', 'appuser.birth_date', $start_age, $end_age])->count();
    }
    return $usersCount;
  }

  public function getUsersByAgeRangeStartdateEnddate($ageRange, $start_date, $end_date)
  {
    $start_age = '';
    $end_age = '';
    $current_date = date('Y-m-d');
    if (!empty($ageRange)) {
      switch ($ageRange) {
        case '12-18':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 12 year'));
          break;
        case '19-24':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          break;
        case '25-30':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          break;
        case '31-36':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          break;
        case '37-42':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          break;
        case '43-49':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 49 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          break;
        case '50+':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 100 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 50 year'));
          break;
      }
    }
    if (!empty($start_age) && !empty($end_age)) {
      $usersCount = Appuserissignup::find()->joinWith(['appuser'])->where(['appuser.role' => '3', 'appuser.user_type' => 'User', 'appuser.is_deleted' => 'No', 'appuser_issignup.devices_type' => 'Web'])->andWhere(['between', 'appuser.birth_date', $start_age, $end_age])->andWhere(['between', 'DATE(appuser_issignup.created_at)', $start_date, $end_date])->count();
    }
    return $usersCount;
  }

  public function getUsersByAgeRangeFanapp($ageRange)
  {
    $start_age = '';
    $end_age = '';
    $current_date = date('Y-m-d');
    if (!empty($ageRange)) {
      switch ($ageRange) {
        case '12-18':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 12 year'));
          break;
        case '19-24':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          break;
        case '25-30':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          break;
        case '31-36':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          break;
        case '37-42':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          break;
        case '43-49':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 49 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          break;
        case '50+':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 100 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 50 year'));
          break;
      }
    }
    if (!empty($start_age) && !empty($end_age)) {
      $usersCount = Appuserissignup::find()->joinWith(['appuser'])->where(['appuser.role' => '3', 'appuser.user_type' => 'User', 'appuser.is_deleted' => 'No', 'appuser_issignup.is_signup' => 'APP_USER'])->andWhere(['between', 'appuser.birth_date', $start_age, $end_age])->count();
    }
    return $usersCount;
  }

  public function getUsersByAgeRangeStartdateEnddateFanapp($ageRange, $start_date, $end_date)
  {
    $start_age = '';
    $end_age = '';
    $current_date = date('Y-m-d');
    if (!empty($ageRange)) {
      switch ($ageRange) {
        case '12-18':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 12 year'));
          break;
        case '19-24':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 18 year'));
          break;
        case '25-30':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 24 year'));
          break;
        case '31-36':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 30 year'));
          break;
        case '37-42':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 36 year'));
          break;
        case '43-49':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 49 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 42 year'));
          break;
        case '50+':
          $start_age = date('Y-m-d', strtotime($current_date . ' - 100 year'));
          $end_age = date('Y-m-d', strtotime($current_date . ' - 50 year'));
          break;
      }
    }
    if (!empty($start_age) && !empty($end_age)) {
      $usersCount = Appuserissignup::find()->joinWith(['appuser'])->where(['appuser.role' => '3', 'appuser.user_type' => 'User', 'appuser.is_deleted' => 'No', 'appuser_issignup.is_signup' => 'APP_USER'])->andWhere(['between', 'appuser.birth_date', $start_age, $end_age])->andWhere(['between', 'DATE(appuser_issignup.created_at)', $start_date, $end_date])->count();
    }
    return $usersCount;
  }

  public function setSignupinfo($model)
  {

    $Appuserissignup = new Appuserissignup();

    $Appuserissignup->appuser_id = $model->appuser_id;
    $Appuserissignup->phone_code = $model->phone_code;
    $Appuserissignup->phone_number = $model->phone_number;
    $Appuserissignup->email = $model->email;
    $Appuserissignup->country_name = '';
    $Appuserissignup->login_type = $model->login_type;
    $Appuserissignup->is_signup = $model->is_signup;
    $Appuserissignup->devices_type = $model->devices_type;
    $Appuserissignup->devices_name = $model->devices_name;
    $Appuserissignup->os = $model->os;
    $Appuserissignup->devices_id = $model->devices_id;
    $Appuserissignup->app_version = $model->app_version;
    if (!$Appuserissignup->save()) {
      Yii::$app->MyFunctions->getModelErrors($Appuserissignup, "Y");
    }
    return $Appuserissignup;
  }



  public function setDeviceinfo($model)
  {
    //echo "<pre>";print_r($model);exit;

    $UserDevice = Appuserdevicesinfo::find()
      ->andWhere(["devices_id" => $model->devices_id])
      ->one();

    if (!$UserDevice) {
      $UserDevice = new Appuserdevicesinfo();
      $UserDevice->auth_key = Yii::$app->MyFunctions->random_string(50);
    }

    $UserDevice->appuser_id = $model->appuser_id;
    $UserDevice->devices_id = $model->devices_id;
    $UserDevice->devices_token = $model->devices_token;
    $UserDevice->app_version = $model->app_version;
    $UserDevice->devices_name = $model->devices_name;
    $UserDevice->devices_type = $model->devices_type;
    if (!$UserDevice->save()) {
      //Yii::$app->MyFunctions->getModelErrors($UserDevice,"Y");
      //$model->delete();
      Yii::$app->MyFunctions->JsonPrint(array('status' => 0, 'message' => Yii::t('app', "Something went's wrong try again later")));
    }
    return $UserDevice;
  }

  public function getDeviceinfo($authkey = '')
  {
    $UserDevice = Appuserdevicesinfo::find()->andWhere(["auth_key" => $authkey])->one();
    return $UserDevice;
  }

  //User Object Api
  public function getUserObject($model, $device = '')
  {
    $data = [];
    if (!empty(($device->auth_key))) {
      $device = Appuserdevicesinfo::find()->andWhere(["auth_key" => $device->auth_key])->one();
      $data['auth_key'] = $device->auth_key;
      $data['devices_id'] = $device->devices_id;
    }

    $data['appuser_id'] = $model->appuser_id;
    $data['first_name'] = $model->first_name;
    $data['last_name'] = $model->last_name;
    $data['full_name'] = $model->full_name;
    $data['phone_code'] = $model->phone_code;
    $data['phone_number'] = $model->phone_number;
    $data['postal_code'] = $model->postal_code;
    $data['email'] = $model->email;
    $image = Yii::$app->params['ImagePath'] . 'uploads/default/user-placeholderdefault.png';
    $data['email'] = $model->email;
    $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : $image;
    $data['login_type'] = $model->login_type;
    $data['status'] = $model->status;
    $data['is_deleted'] = $model->is_deleted;
    $data['updated_at'] = $model->updated_at;
    $data['created_at'] = $model->created_at;

    return $data;
  }

  //User Object Api
  public function getBannerObject($model)
  {
    $data = [];

    if (!empty($model)) {
      $data['banner_id'] = $model->banner_id;
      $data['title'] = $model->title;
      $data['subtitle'] = $model->subtitle;
      $data['btn_title'] = $model->btn_title;
      $data['url'] = $model->url;
      $data['type'] = $model->type;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['video'] = (!empty($model->video)) ? Yii::$app->params['ImagePath'] . $model->video : '';
      $data['mobile_video'] = (!empty($model->mobile_video)) ? Yii::$app->params['ImagePath'] . $model->mobile_video : '';
      $data['display_order'] = $model->display_order;
      $data['status'] = $model->status;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }


    return $data;
  }

  public function getProductcategory($model)
  {
    $data = [];

    if (!empty($model)) {
      $data['product_category_id'] = $model->product_category_id;
      $data['title'] = $model->title;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['banner_image'] = (!empty($model->banner_image)) ? Yii::$app->params['ImagePath'] . $model->banner_image : '';
      $data['sample_image'] = (!empty($model->sample_image)) ? Yii::$app->params['ImagePath'] . $model->sample_image : '';
      $data['description'] = $model->description;
      $data['display_order'] = $model->display_order;
      $data['status'] = $model->status;
      $data['price'] = $model->price;
      $data['sample_price'] = $model->sample_price;
      $data['material_per_step'] = number_format((float)$model->material_per_step, 2, '.', '');
      $data['installation_per_step'] = number_format((float)$model->installation_per_step, 2, '.', '');
      $data['meta_title'] = $model->meta_title;
      $data['meta_tag'] = $model->meta_tag;
      $data['meta_description'] = $model->meta_description;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }


    return $data;
  }

  public function getNewslatterNewObject($model)
  {

    $data['newsletter_id'] = $model->newsletter_id;
    $data['title'] = $model->title;
    $data['sub_title'] = $model->sub_title;
    //$data['description']=$model->description;
    $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
    $data['image_height'] = $model->image_height;
    $data['image_width'] = $model->image_width;
    $data['thumbnail_image'] = (!empty($model->thumbnail_image)) ? Yii::$app->params['ImagePath'] . $model->thumbnail_image : '';
    $data['thumbnail_height'] = $model->thumbnail_height;
    $data['thumbnail_width'] = $model->thumbnail_width;
    $data['date'] = $model->date;
    $data['is_featured'] = $model->is_featured;
    //$data['type']=$model->type;
    // $data['contact_type']=$model->contact_type;
    // $data['btn_title']=$model->btn_title;
    // $data['btn_url']=$model->btn_url;
    // $data['btn_type']=$model->btn_type;
    $data['slug'] = $model->slug;
    $data['status'] = $model->status;
    $data['created_at'] = $model->created_at;
    //$data['news']=$this->getNewslatterNewObject($model->newsletters);

    return $data;
  }

  public function getNewslatterObject($model)
  {

    $data['newsletter_id'] = $model->newsletter_id;
    $data['title'] = $model->title;
    $data['sub_title'] = $model->sub_title;
    $data['description'] = $model->description;
    $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
    $data['image_height'] = $model->image_height;
    $data['image_width'] = $model->image_width;
    $data['thumbnail_image'] = (!empty($model->thumbnail_image)) ? Yii::$app->params['ImagePath'] . $model->thumbnail_image : '';
    $data['thumbnail_height'] = $model->thumbnail_height;
    $data['thumbnail_width'] = $model->thumbnail_width;
    $data['meta_title'] = $model->meta_title;
    $data['meta_tag'] = $model->meta_tag;
    $data['meta_description'] = $model->meta_description;
    $data['date'] = $model->date;
    $data['slug'] = $model->slug;
    $data['status'] = $model->status;
    $data['created_at'] = $model->created_at;

    return $data;
  }

  public function getClientsayObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['client_say_id'] = $model->client_say_id;
      $data['name'] = $model->name;
      $data['designation'] = $model->designation;
      $data['company_name'] = $model->company_name;
      $data['description'] = $model->description;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['video'] = (!empty($model->video)) ? Yii::$app->params['ImagePath'] . $model->video : '';
      $data['status'] = $model->status;
      $data['created_at'] = $model->created_at;
    }
    return $data;
  }

  public function getProductHomeObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['product_id'] = $model->product_id;
      $data['product_category_id'] = $model->product_category_id;
      $data['title'] = $model->title;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['price'] = number_format((float)$model->price, 2, '.', '');
      $data['sqft_in_box'] = $model->sqft_in_box;
      $data['price_per_box'] = number_format((float)$model->price_per_box, 2, '.', '');
      $data['main_price'] = (!empty($model->main_price)) ? number_format((float)$model->main_price, 2, '.', '') : null;
      $data['price_per_piece'] = (!empty($model->price_per_piece)) ? number_format((float)$model->price_per_piece, 2, '.', '') : null;
      $data['material_per_step'] = ($model->productCategory) ? number_format((float)$model->productCategory->material_per_step, 2, '.', '') : '0.00';
      $data['installation_per_step'] = ($model->productCategory) ? number_format((float)$model->productCategory->installation_per_step, 2, '.', '') : '0.00';
      $data['save_button_price'] = $model->save_button_price;
      $data['slug'] = $model->slug;
      $data['display_order'] = $model->display_order;
      $data['status'] = $model->status;
      $data['need_to_display_price'] = $model->need_to_display_price;
      $data['meta_title'] = ($model->productCategory) ? $model->productCategory->meta_title : "";
      $data['meta_tag'] = ($model->productCategory) ? $model->productCategory->meta_tag : "";
      $data['meta_description'] = ($model->productCategory) ? $model->productCategory->meta_description : "";
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }
    return $data;
  }
  public function getProductObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['product_id'] = $model->product_id;
      $data['product_category_id'] = $model->product_category_id;
      $data['title'] = $model->title;
      $data['description'] = $model->description;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['price'] = number_format((float)$model->price, 2, '.', '');
      $data['sqft_in_box'] = $model->sqft_in_box;
      $data['price_per_box'] = number_format((float)$model->price_per_box, 2, '.', '');
      $data['main_price'] = (!empty($model->main_price)) ? number_format((float)$model->main_price, 2, '.', '') : null;
      $data['price_per_piece'] = (!empty($model->price_per_piece)) ? number_format((float)$model->price_per_piece, 2, '.', '') : null;
      $data['material_per_step'] = ($model->productCategory) ? number_format((float)$model->productCategory->material_per_step, 2, '.', '') : '0.00';
      $data['installation_per_step'] = ($model->productCategory) ? number_format((float)$model->productCategory->installation_per_step, 2, '.', '') : '0.00';
      $data['save_button_price'] = $model->save_button_price;
      $data['slug'] = $model->slug;
      $data['display_order'] = $model->display_order;
      $data['status'] = $model->status;
      $data['need_to_display_price'] = $model->need_to_display_price;
      $data['meta_title'] = ($model->productCategory) ? $model->productCategory->meta_title : "";
      $data['meta_tag'] = ($model->productCategory) ? $model->productCategory->meta_tag : "";
      $data['meta_description'] = ($model->productCategory) ? $model->productCategory->meta_description : "";
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
      $data['product_image'] = $this->getProductImagesObject($model->productImages);
      $data['product_specifications'] = $this->getProductSpecificationsObject($model->productSpecifications);
    }
    return $data;
  }

  public function getProductImagesObject($model)
  {
    $data = [];
    if (!empty($model)) {
      foreach ($model as $key => $value) {
        $data[$key]['product_image_id'] = $value->product_image_id;
        $data[$key]['product_id'] = $value->product_id;
        $data[$key]['type'] = $value->type;
        $data[$key]['file'] = (!empty($value->file)) ? Yii::$app->params['ImagePath'] . $value->file : '';
        $data[$key]['video'] = (!empty($value->video)) ? Yii::$app->params['ImagePath'] . $value->video : '';
        $data[$key]['video_url'] = $value->video_url;
        $data[$key]['created_at'] = $value->created_at;
      }
    }
    return $data;
  }
  public function getProductSpecificationsObject($model)
  {
    $data = [];
    if (!empty($model)) {
      foreach ($model as $key => $value) {
        $data[$key]['product_specifications_id'] = $value->product_specifications_id;
        $data[$key]['product_id'] = $value->product_id;
        $data[$key]['title'] = $value->title;
        $data[$key]['created_at'] = $value->created_at;
        $data[$key]['product_specifications_detail'] = $this->getProductSpecificationsDetailsObject($value->productSpecificationsDetails);
      }
    }
    return $data;
  }
  public function getProductSpecificationsDetailsObject($model)
  {
    $data = [];
    if (!empty($model)) {
      foreach ($model as $key => $value) {
        $data[$key]['product_specifications_id'] = $value->product_specifications_id;
        $data[$key]['title'] = $value->title;
        $data[$key]['sub_title'] = $value->sub_title;
        $data[$key]['created_at'] = $value->created_at;
      }
    }
    return $data;
  }

  public function getShoppingbybrandsObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['shopping_by_brands_id'] = $model->shopping_by_brands_id;
      $data['title'] = $model->title;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['logo'] = (!empty($model->logo)) ? Yii::$app->params['ImagePath'] . $model->logo : '';
      $data['display_order'] = $model->display_order;
      $data['status'] = $model->status;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }
    return $data;
  }
  public function getAppuseraddressObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['appuser_address_id'] = $model->appuser_address_id;
      $data['appuser_id'] = $model->appuser_id;
      $data['name'] = $model->name;
      $data['address_line_1'] = $model->address_line_1;
      $data['address_line_2'] = $model->address_line_2;
      $data['latitude'] = $model->latitude;
      $data['longitude'] = $model->longitude;
      $data['city'] = $model->city;
      $data['state'] = $model->state;
      $data['postal_code'] = $model->postal_code;
      $data['mobile_number'] = $model->mobile_number;
      $data['is_default'] = $model->is_default;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }
    return $data;
  }

  public function getAboutusObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['about_us_id'] = $model->about_us_id;
      $data['banner_title'] = $model->banner_title;
      $data['banner_sub_title'] = $model->banner_sub_title;
      $data['image'] = (!empty($model->image)) ? Yii::$app->params['ImagePath'] . $model->image : '';
      $data['title'] = $model->title;
      $data['sub_title'] = $model->sub_title;
      $data['description'] = $model->description;
      $data['status'] = $model->status;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }
    return $data;
  }

  public function getUsercartsObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['user_carts_id'] = $model->user_carts_id;
      $data['appuser_id'] = $model->appuser_id;
      $data['product_id'] = (int)$model->product_id;
      $data['price'] = number_format((float)$model->price, 2, '.', '');
      $data['quantity'] = (int)$model->quantity;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
      $data['product'] = $this->getProductHomeObject($model->product);
    }
    return $data;
  }
  public function getUserorderObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['user_order_id'] = $model->user_order_id;
      $data['appuser_id'] = $model->appuser_id;
      $data['appuser_address_id'] = $model->appuser_address_id;
      $data['order_number'] = $model->order_number;
      $data['delivery_type'] = $model->delivery_type;
      $data['payment_type'] = $model->payment_type;
      $data['payment_status'] = $model->payment_status;
      $data['payment_id'] = $model->payment_id;
      $data['sub_total'] = number_format((float)$model->sub_total, 2, '.', '');
      $data['tax'] = number_format((float)$model->tax, 2, '.', '');
      $data['shipping'] = number_format((float)$model->shipping, 2, '.', '');
      $data['total'] = number_format((float)$model->total, 2, '.', '');
      $data['payment_date'] = $model->payment_date;
      $data['order_status'] = $model->order_status;
      $data['delivery_status'] = $model->delivery_status;
      $data['created_at'] = $model->created_at;
      $data['address'] = (!empty($model->appuserAddress)) ? $this->getAppuseraddressObject($model->appuserAddress) : (object)array();
      $data['order_detail'] = $this->getUserOrderDetailsObject($model->userOrderDetails);
    }
    return $data;
  }
  public function getUserOrderDetailsObject($model)
  {
    $data = [];
    if (!empty($model)) {
      foreach ($model as $key => $value) {
        $data[$key]['user_order_detail_id'] = $value->user_order_detail_id;
        $data[$key]['user_order_id'] = $value->user_order_id;
        $data[$key]['product_id'] = $value->product_id;
        $data[$key]['product_title'] = (!empty($value->product->title)) ? $value->product->title : '';
        $data[$key]['product_image'] =  (!empty($value->product->image)) ? Yii::$app->params['ImagePath'] . $value->product->image : '';
        $data[$key]['product_price'] = (!empty($value->product->price)) ? number_format((float)$value->product->price, 2, '.', '') : '';
        $data[$key]['price'] = number_format((float)$value->price, 2, '.', '');
        $data[$key]['quantity'] = $value->quantity;
        $data[$key]['created_at'] = $value->created_at;
      }
    }
    return $data;
  }

  public function getShippingchargeObject($model)
  {
    $data = [];
    if (!empty($model)) {
      $data['shipping_charge_id'] = $model->shipping_charge_id;
      $data['min_mile'] = $model->min_mile;
      $data['max_mile'] = $model->max_mile;
      $data['price'] = number_format((float)$model->price, 2, '.', '');
      $data['status'] = $model->status;
      $data['created_at'] = $model->created_at;
      $data['updated_at'] = $model->updated_at;
    }
    return $data;
  }

  public function getSampleObject($model)
  {
    $data = [];

    if (!empty($model)) {
      $data['sample_id'] = $model->sample_id;
      $data['first_name'] = $model->first_name;
      $data['last_name'] = $model->last_name;
      $data['company_name'] = $model->company_name;
      $data['email'] = $model->email;
      $data['phone_no'] = $model->phone_no;
      $data['address'] = $model->address;
      $data['address_line_2'] = $model->address_line_2;
      $data['city'] = $model->city;
      $data['state'] = $model->state;
      $data['zip_code'] = $model->zip_code;
      $data['product_name'] = $model->product_name;
      $data['order_number'] = $model->order_number;
      $data['payment_type'] = $model->payment_type;
      $data['payment_status'] = $model->payment_status;
      $data['payment_id'] = $model->payment_id;
      $data['sub_total'] = number_format((float)$model->sub_total, 2, '.', '');
      $data['tax'] = number_format((float)$model->tax, 2, '.', '');
      $data['shipping'] = number_format((float)$model->shipping, 2, '.', '');
      $data['total'] = number_format((float)$model->total, 2, '.', '');
      $data['payment_date'] = $model->payment_date;
      $data['order_status'] = $model->order_status;
      $data['delivery_status'] = $model->delivery_status;
      $data['created_at'] = $model->created_at;
    }

    return $data;
  }
}
