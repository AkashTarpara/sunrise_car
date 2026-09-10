<?php

namespace app\controllers;

use Yii;
use app\models\Adminsidemenudetail;
use app\models\AdminsidemenudetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminsidemenudetailController implements the CRUD actions for Adminsidemenudetail model.
 */
class AdminsidemenudetailController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Adminsidemenudetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminsidemenudetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Adminsidemenudetail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangestatus()
    {
        //$str=Yii::$app->MyFunctions->decode($id );
        $str=$_REQUEST['id'];
        $model=$this->findModel($str);
        if($model->status == "Active"){
            $model->status = "Inactive";
            $message="Selected Sub Menu has been deactivated.";
            //Yii::$app->getSession()->setFlash('success','Selected Sub Menu has been deactivated.');
        }else{
            $model->status = "Active";
            $message="Selected Sub Menu has been activated.";
            //Yii::$app->getSession()->setFlash('success','Selected Sub Menu has been activated.');
        }
        if(!$model->save()){
            Yii::$app->MyFunctions->getModelErrors($model,"Y");
        }
        return Yii::$app->MyFunctions->JsonPrint(array('status' => 200,'message'=>$message));
        //return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionChangeorder()
    {
        //echo "<pre>";print_r($_REQUEST);exit;
        $id = $_REQUEST['id'];
        $admin_sidemenu_id = $_REQUEST['admin_sidemenu_id'];
        $current = $_REQUEST['current'];
        $target = $_REQUEST['target'];
        $total = $_REQUEST['total'];
        $connection = \Yii::$app->db;
        if($target > $total || $target == 0){
            Yii::$app->getSession()->setFlash(
                            'error','Enter valid sequence.'
                        );
            return $this->redirect(Yii::$app->request->referrer);
        }else if ($target < $current) {
            $query = "UPDATE admin_sidemenu_detail SET display_order = display_order + 1 WHERE display_order < ".$current." and display_order >=".$target." AND admin_sidemenu_id =".$admin_sidemenu_id;
            $query1 = "UPDATE admin_sidemenu_detail SET display_order = ".$target." WHERE admin_sidemenu_detail_id =".$id." AND admin_sidemenu_id =".$admin_sidemenu_id;
        }else if ($target > $current) {
            $query = "UPDATE admin_sidemenu_detail SET display_order = display_order - 1 WHERE display_order <= ".$target." AND display_order > ".$current." AND admin_sidemenu_id =".$admin_sidemenu_id;
            $query1 = "UPDATE admin_sidemenu_detail SET display_order = ".$target." WHERE admin_sidemenu_detail_id =".$id." AND admin_sidemenu_id =".$admin_sidemenu_id;
        }else{
            Yii::$app->getSession()->setFlash(
                            'error','Enter valid sequence.'
                        );
            return $this->redirect(Yii::$app->request->referrer);
        }
        $command = $connection->createCommand($query);
        $command->execute();
        $command2 = $connection->createCommand($query1);
        $command2->execute();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Adminsidemenudetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Adminsidemenudetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->admin_sidemenu_detail_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Adminsidemenudetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->admin_sidemenu_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Adminsidemenudetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Adminsidemenudetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adminsidemenudetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adminsidemenudetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
