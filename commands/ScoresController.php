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
use app\models\Rrapimatchesscorescommentaries;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ScoresController extends Controller
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
    
    //GET and Update Score
    public function actionUpdateScores()
    {
      
      date_default_timezone_set('Asia/Kolkata');

      $query = Matches::find()->Where(['status'=>'Active'])
      //Where(['=', 'date_time', date('Y-m-d H:i')])
      //->andWhere(['status'=>'Active'])
      ->andWhere(['!=','matche_status','Past'])
      ->orderBy(['date_time'=>SORT_ASC])->All();
      
      if(!empty($query))
      {
        foreach ($query as $key => $value) {
          $thestime = $value->date_time;
          $currentdate=date("Y-m-d H:i");
          $datetime_from = date("Y-m-d H:i",strtotime("-15 minutes",strtotime($thestime)));
          //echo "<pre>"; print_r($currentdate);  echo"<br/>"; print_r($datetime_from); exit; 
          if($currentdate==$datetime_from){
            $value->matche_status='Live';
            $value->save();
          }
        }
      }

      $livematches=Matches::find()->where(['status'=>'Active','matche_status'=>'Live'])->all();
      //$livematches=Matches::find()->where(['matche_status'=>'Live'])->all();
      //echo "<pre>"; print_r($livematches); exit;
      if(!empty($livematches))
      {
        foreach ($livematches as $key => $value) {
          if($token=Yii::$app->MyFunctions->getAPIToken())
          {
            $scoreUpdate=Yii::$app->MyFunctions->getAPIScorecard($token,$value);
            $commentaryEnUpdate=Yii::$app->MyFunctions->getAPICommentary($token,$value,'en');
            $commentaryHiUpdate=Yii::$app->MyFunctions->getAPICommentary($token,$value,'hi');
          }
        }
        return true;
      }
      return false;
    } 

    //Update Player Details
    public function actionUpdatePlayer()
    {
      $is=false;
      if($token=Yii::$app->MyFunctions->getAPIToken())
      {
        $is=Yii::$app->MyFunctions->getAPIPlayerstats($token);;
      }
      return $is;
    }

    public function actionUpdatepointtable()
    {
      if($token=Yii::$app->MyFunctions->getAPIToken()){
        $data=Yii::$app->MyFunctions->getAPIPointtable($token);
      }
      return 1;
    }

}
