<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\Matches;
use app\models\Generalsetting;
use app\models\Newsletter;

ini_set('memory_limit','2048M');


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionNewsletterupdate()
    {
      //date_default_timezone_set('Asia/Kolkata');
      date_default_timezone_set('Europe/London');
      //exit('Hello');
      //echo "<pre>"; print_r(date('Y-m-d H:i')); exit;
      $model = Newsletter::find()->Where(['status'=>'Inactive'])->AndWhere(['=','publish_date',date('Y-m-d H:i')])->all();
      //echo "<pre>"; print_r($model); exit;
      if(!empty($model)){
        foreach ($model as $key => $value) {
          $value->status='Active';
          $value->created_at=date('Y-m-d H:i:s');
          $value->save();
        }
      }
      return 1;
    }
   
  
}