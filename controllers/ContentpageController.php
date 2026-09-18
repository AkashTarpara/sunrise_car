<?php

namespace app\controllers;

use Yii;
use app\models\Contentpage;
use app\models\Contentpagedetail;
use app\models\ContentpagedetailSearch;
use app\models\ContentpageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * ContentpageController implements the CRUD actions for Contentpage model.
 */
class ContentpageController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                //'only' => ['logout','index'],
                'rules' => [
                    [
                        //'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        //'actions' => ['login','userforgotpassword','spforgotpassword','adminforgotpassword'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contentpage models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ContentpageSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contentpage model.
     * @param int $id Content Page ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ContentpagedetailSearch();
        $dataProvider = $searchModel->contentpagedetailsearch(Yii::$app->request->queryParams,$id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total'=>Contentpagedetail::find()->where(['content_page_id'=>$id])->count(),
        ]);
    }

    public function actionChangestatus()
    {
        //echo "<pre>";print_r($_REQUEST);exit;
        //$str=Yii::$app->MyFunctions->decode($_REQUEST['id']);
        $str=$_REQUEST['id'];
        //echo "<pre>";print_r($_REQUEST['id']);exit;
        $message="";
        $model=$this->findModel($str);
        if($model->status == "Active"){
            $model->status = "Inactive";
            $message="Selected Page has been deactivated.";
            //Yii::$app->getSession()->setFlash('success','Selected Menu has been deactivated.');
        }else{
            $message="Selected Page has been activated.";
            $model->status = "Active";
            //Yii::$app->getSession()->setFlash('success','Selected Menu has been activated.');
        }
        if(!$model->save()){
            Yii::$app->MyFunctions->getModelErrors($model,"Y");
        }
        return Yii::$app->MyFunctions->JsonPrint(array('status' => 200,'message'=>$message));
        //return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Contentpage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Contentpage();
        $Contentpagedetail = [new Contentpagedetail];

        if ($model->load(Yii::$app->request->post())) {

            $Contentpagedetail = Model::createMultiple(Contentpagedetail::classname());
            Model::loadMultiple($Contentpagedetail, Yii::$app->request->post());
            foreach ($Contentpagedetail as $index => $modelServiceItem) {
                $modelServiceItem->sort_order = $index;
                $modelServiceItem->image = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]image");
                $modelServiceItem->logo = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]logo");
                $modelServiceItem->video = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]video");
                $modelServiceItem->mobile_image = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]mobile_image");
                $modelServiceItem->mobile_video = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]mobile_video");
                $modelServiceItem->upload();
            }


            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {

                    $content_page_id = $model->content_page_id;
                    foreach ($Contentpagedetail as $models) {
                        $models->content_page_id = $content_page_id;
                        if (! ($flag = $models->save())) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }    
                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Page has been added successfully.');
                    return $this->redirect(['index']);
                }
                else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'Contentpagedetail' => (empty($Contentpagedetail)) ? [new Contentpagedetail] : $Contentpagedetail,
        ]);
    }

    /**
     * Updates an existing Contentpage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Content Page ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $Contentpagedetail = $model->contentPageDetails;

        if ($model->load(Yii::$app->request->post())) {
   
            $oldServiceItemIDs = ArrayHelper::map($Contentpagedetail, 'content_page_detail_id', 'content_page_detail_id');
            $Contentpagedetail = Model::createMultiple(Contentpagedetail::classname(), $Contentpagedetail);
            Model::loadMultiple($Contentpagedetail, Yii::$app->request->post());
            $deletedServiceItemIDs = array_diff($oldServiceItemIDs, array_filter(ArrayHelper::map($Contentpagedetail, 'content_page_detail_id', 'content_page_detail_id')));

            foreach ($Contentpagedetail as $index => $modelsuniversityCourse) {
                $modelsuniversityCourse->sort_order = $index;
                $modelsuniversityCourse->image = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]image");
                $modelsuniversityCourse->logo = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]logo");
                $modelsuniversityCourse->video = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]video");
                $modelsuniversityCourse->mobile_image = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]mobile_image");
                $modelsuniversityCourse->mobile_video = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]mobile_video");
                $modelsuniversityCourse->upload();
            }
            //echo "<pre>"; print_r($deletedServiceItemIDs); exit;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {

                    
                    if (! empty($deletedServiceItemIDs)) {
                        Contentpagedetail::deleteAll(['content_page_detail_id' => $deletedServiceItemIDs]);
                    }

                    foreach ($Contentpagedetail as $indexServiceItem => $modelsuniversityCourse) {

                        if ($flag === false) {
                            break;
                        }

                        $modelsuniversityCourse->content_page_id = $model->content_page_id;
                        if (!($flag = $modelsuniversityCourse->save())) {
                            break;
                        }                                                       
                    }
                             
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',"Page has been Updated successfully.");
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'Contentpagedetail' => (empty($Contentpagedetail)) ? [new Contentpagedetail] : $Contentpagedetail,
        ]);
    }

    /**
     * Deletes an existing Contentpage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Content Page ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contentpage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Content Page ID
     * @return Contentpage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contentpage::findOne(['content_page_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
