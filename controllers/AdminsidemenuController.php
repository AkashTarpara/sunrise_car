<?php

namespace app\controllers;

use Yii;
use app\models\Adminsidemenu;
use app\models\AdminsidemenuSearch;
use app\models\Adminsidemenudetail;
use app\models\Adminsidemenusubdetail;
use app\models\AdminsidemenudetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * AdminsidemenuController implements the CRUD actions for Adminsidemenu model.
 */
class AdminsidemenuController extends Controller
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
     * Lists all Adminsidemenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminsidemenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total'=>Adminsidemenu::find()->count(),
        ]);
    }

    /**
     * Displays a single Adminsidemenu model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new AdminsidemenudetailSearch();
        $dataProvider = $searchModel->submenuSearch(Yii::$app->request->queryParams,$id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total'=>Adminsidemenudetail::find()->where(['admin_sidemenu_id'=>$id])->count(),
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
            $message="Selected Menu has been deactivated.";
            //Yii::$app->getSession()->setFlash('success','Selected Menu has been deactivated.');
        }else{
            $message="Selected Menu has been activated.";
            $model->status = "Active";
            //Yii::$app->getSession()->setFlash('success','Selected Menu has been activated.');
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
        $current = $_REQUEST['current'];
        $target = $_REQUEST['target'];
        $total = $_REQUEST['total'];
        $connection = \Yii::$app->db;
        if($target > $total || $target == 0){
            Yii::$app->getSession()->setFlash(
                            'error','Enter valid sequence.'
                        );
            return $this->redirect(['index']);
        }else if ($target < $current) {
            $query = "UPDATE admin_sidemenu SET display_order = display_order + 1 WHERE display_order < ".$current." and display_order >=".$target;
            $query1 = "UPDATE admin_sidemenu SET display_order = ".$target." WHERE admin_sidemenu_id =".$id;
        }else if ($target > $current) {
            $query = "UPDATE admin_sidemenu SET display_order = display_order - 1 WHERE display_order <= ".$target." AND display_order > ".$current;
            $query1 = "UPDATE admin_sidemenu SET display_order = ".$target." WHERE admin_sidemenu_id =".$id;
        }else{
            Yii::$app->getSession()->setFlash(
                            'error','Enter valid sequence.'
                        );
            return $this->redirect(['index']);
        }
        $command = $connection->createCommand($query);
        $command->execute();
        $command2 = $connection->createCommand($query1);
        $command2->execute();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Adminsidemenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionCreate()
    {
        $model = new Adminsidemenu();
        $modelsadminsidemenudetail = [new Adminsidemenudetail];
        $modelsAdminsidemenusubdetail = [[new Adminsidemenusubdetail]];

        if ($model->load(Yii::$app->request->post())) {

            $modelsadminsidemenudetail = Model::createMultiple(Adminsidemenudetail::classname());
            Model::loadMultiple($modelsadminsidemenudetail, Yii::$app->request->post());

            // validate person and houses models
            //$valid = $model->validate();
        
            //echo "<pre>";print_r($_POST['Adminsidemenusubdetail']);exit;
            if (isset($_POST['Adminsidemenusubdetail'])) {
                
                foreach ($_POST['Adminsidemenusubdetail'] as $indexSubmenu => $rooms) {
                    if(isset($_POST['Adminsidemenusubdetail'][$indexSubmenu])){
                        foreach ($rooms as $indexAdminsidemenusubdetail => $room) {
                            //echo "<pre>";print_r($room);exit;
                            $data['Adminsidemenusubdetail'] = $room;
                            $modelRoom = new Adminsidemenusubdetail;
                            $modelRoom->load($data);
                            $modelsAdminsidemenusubdetail[$indexSubmenu][$indexAdminsidemenusubdetail] = $modelRoom;
                            $valid = $modelRoom->validate();
                        }
                    } 
                }
            }
            //echo "<pre>";print_r($modelsAdminsidemenusubdetail);exit;
            

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {
                    foreach ($modelsadminsidemenudetail as $indexSubmenu => $modelSubmenu) {

                        if ($flag === false) {
                            break;
                        }

                        $modelSubmenu->admin_sidemenu_id = $model->admin_sidemenu_id;
                        //$modelSubmenu->title = $modelSubmenu->title;
                        //echo "<pre>";print_r($modelSubmenu->title);exit;
                        //$modelSubmenu->validate();
                        if (!($flag = $modelSubmenu->save())) {
                            break;
                        }

                        if (isset($_POST['Adminsidemenusubdetail'][$indexSubmenu])) {
                            //echo "<pre>";print_r($_POST['Adminsidemenusubdetail'][$indexSubmenu]);exit;
                            if (isset($modelsAdminsidemenusubdetail[$indexSubmenu]) && is_array($modelsAdminsidemenusubdetail[$indexSubmenu])) {
                                foreach ($modelsAdminsidemenusubdetail[$indexSubmenu] as $indexAdminsidemenusubdetail => $modelRoom) {
                                    $modelRoom->admin_sidemenu_detail_id = $modelSubmenu->admin_sidemenu_detail_id;
                                    if (!($flag = $modelRoom->save(false))) {
                                        //Yii::$app->MyFunctions->getModelErrors($modelRoom,"Y");
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Admin Side Menu added  successfully.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsadminsidemenudetail' => (empty($modelsadminsidemenudetail)) ? [new Adminsidemenudetail] : $modelsadminsidemenudetail,
            'modelsAdminsidemenusubdetail' => (empty($modelsAdminsidemenusubdetail)) ? [[new Adminsidemenusubdetail]] : $modelsAdminsidemenusubdetail,
        ]);
    }

    /**
     * Updates an existing Adminsidemenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsadminsidemenudetail = $model->adminSidemenuDetails;
        $modelsAdminsidemenusubdetail = [];
        //echo "<pre>";print_r($modelsAdminsidemenusubdetail);exit;
        $oldRooms = [];

        if (!empty($modelsadminsidemenudetail)) {
            foreach ($modelsadminsidemenudetail as $indexHouse => $modelHouse) {
                $rooms = (!empty($modelHouse->adminsidemenusubdetail))?$modelHouse->adminsidemenusubdetail:[new Adminsidemenusubdetail];
                //echo "<pre>";print_r($rooms);exit;
                //if(!empty($rooms)){
                    $modelsAdminsidemenusubdetail[$indexHouse] = $rooms;
                    $oldRooms = ArrayHelper::merge(ArrayHelper::index($rooms, 'admin_sidemenu_sub_detail_id'), $oldRooms);
                //}
                
            }
        }
        //echo "<pre>";print_r($modelsAdminsidemenusubdetail[1]);exit;

        if ($model->load(Yii::$app->request->post())) {

            // reset
            $modelsAdminsidemenusubdetail = [];

            $oldHouseIDs = ArrayHelper::map($modelsadminsidemenudetail, 'admin_sidemenu_detail_id', 'admin_sidemenu_detail_id');
            $modelsadminsidemenudetail = Model::createMultiple(Adminsidemenudetail::classname(), $modelsadminsidemenudetail);
            //echo "<pre>";print_r($modelsadminsidemenudetail);exit;
            Model::loadMultiple($modelsadminsidemenudetail, Yii::$app->request->post());
            $deletedHouseIDs = array_diff($oldHouseIDs, array_filter(ArrayHelper::map($modelsadminsidemenudetail, 'admin_sidemenu_detail_id', 'admin_sidemenu_detail_id')));

            // validate person and houses models
            //$valid = $model->validate();
            //$valid = Model::validateMultiple($modelsadminsidemenudetail) && $valid;

            $roomsIDs = [];
            //echo "<pre>";print_r($_POST['Adminsidemenusubdetail']);exit;
            if (isset($_POST['Adminsidemenusubdetail'])) {
                foreach ($_POST['Adminsidemenusubdetail'] as $indexHouse => $rooms) {

                    $roomsIDs = ArrayHelper::merge($roomsIDs, array_filter(ArrayHelper::getColumn($rooms, 'admin_sidemenu_sub_detail_id')));

                    foreach ($rooms as $indexRoom => $room) {

                        $data['Adminsidemenusubdetail'] = $room;
                        $modelRoom = (isset($room['admin_sidemenu_sub_detail_id']) && isset($oldRooms[$room['admin_sidemenu_sub_detail_id']])) ? $oldRooms[$room['admin_sidemenu_sub_detail_id']] : new Adminsidemenusubdetail;
                        $modelRoom->load($data);

                        $modelsAdminsidemenusubdetail[$indexHouse][$indexRoom] = $modelRoom;

                        //$valid = $modelRoom->validate();
                        //echo "<pre>";print_r($valid);exit;
                    }
                }
            }
            //echo "<pre>";print_r($modelsAdminsidemenusubdetail);exit;
            $oldRoomsIDs = ArrayHelper::getColumn($oldRooms, 'admin_sidemenu_sub_detail_id');
            $deletedRoomsIDs = array_diff($oldRoomsIDs, $roomsIDs);

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {

                    if (! empty($deletedRoomsIDs)) {
                        Adminsidemenusubdetail::deleteAll(['admin_sidemenu_sub_detail_id' => $deletedRoomsIDs]);
                    }

                    if (! empty($deletedHouseIDs)) {
                        Adminsidemenudetail::deleteAll(['admin_sidemenu_detail_id' => $deletedHouseIDs]);
                    }

                    foreach ($modelsadminsidemenudetail as $indexHouse => $modelHouse) {
                        //echo "<pre>";print_r($flag);exit;
                        if ($flag === false) {
                            break;
                        }

                        $modelHouse->admin_sidemenu_id = $model->admin_sidemenu_id;

                        if (!($flag = $modelHouse->save())) {
                            break;
                        }

                        if (isset($_POST['Adminsidemenusubdetail'][$indexHouse])) {
                            //echo "<pre>";print_r($modelsAdminsidemenusubdetail);exit;
                            if (isset($modelsAdminsidemenusubdetail[$indexHouse]) && is_array($modelsAdminsidemenusubdetail[$indexHouse])) {
                                
                                foreach ($modelsAdminsidemenusubdetail[$indexHouse] as $indexRoom => $modelRoom) {
                                    //echo "<pre>";print_r($modelRoom);exit;
                                    $modelRoom->admin_sidemenu_detail_id = $modelHouse->admin_sidemenu_detail_id;
                                    //echo "<pre>";print_r($modelRoom->save());exit;
                                    if (!($flag = $modelRoom->save())) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Admin Side Menu updated  successfully.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        //echo "<pre>";print_r($modelsAdminsidemenusubdetail[0]);exit;
        return $this->render('update', [
            'model' => $model,
            'modelsadminsidemenudetail' => (empty($modelsadminsidemenudetail)) ? [new Adminsidemenudetail] : $modelsadminsidemenudetail,
            'modelsAdminsidemenusubdetail' => (empty($modelsAdminsidemenusubdetail)) ? [[new Adminsidemenusubdetail]] : $modelsAdminsidemenusubdetail
        ]);
    }

    /**
     * Deletes an existing Adminsidemenu model.
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
     * Finds the Adminsidemenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adminsidemenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adminsidemenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
