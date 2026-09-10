<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Productimage;
use app\models\Productspecifications;
use app\models\Productspecificationsdetail;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => Product::find()->count(),
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id Product ID
     * @return string
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
        //$str = Yii::$app->MyFunctions->decode($id);
        $str = $_REQUEST['id'];
        $model = $this->findModel($str);
        $message = "";
        if ($model->status == "Active") {
            $model->status = "Inactive";
            $message = "Selected Product has been deactivated.";
        } else {
            $model->status = "Active";
            $message = "Selected Product has been activated.";
        }
        if (!$model->save()) {
            Yii::$app->MyFunctions->getModelErrors($model, "Y");
        }
        return Yii::$app->MyFunctions->JsonPrint(array('status' => 200, 'message' => $message));
    }

    public function actionChangeorder()
    {
        //echo "<pre>";print_r($_REQUEST);exit;
        $id = $_REQUEST['id'];
        $current = $_REQUEST['current'];
        $target = $_REQUEST['target'];
        $total = $_REQUEST['total'];
        $connection = \Yii::$app->db;
        if ($target > $total || $target == 0) {
            Yii::$app->getSession()->setFlash(
                'error',
                'Enter valid sequence.'
            );
            return $this->redirect(['index']);
        } else if ($target < $current) {
            $query = "UPDATE product SET display_order = display_order + 1 WHERE display_order < " . $current . " and display_order >=" . $target;
            $query1 = "UPDATE product SET display_order = " . $target . " WHERE product_id =" . $id;
        } else if ($target > $current) {
            $query = "UPDATE product SET display_order = display_order - 1 WHERE display_order <= " . $target . " AND display_order > " . $current;
            $query1 = "UPDATE product SET display_order = " . $target . " WHERE product_id =" . $id;
        } else {
            Yii::$app->getSession()->setFlash(
                'error',
                'Enter valid sequence.'
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->scenario = 'create';
        $modelsProductspecifications = [new Productspecifications];
        $modelsProductspecificationsdetail = [[new Productspecificationsdetail]];
        $Productimage = [new Productimage];

        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();

            /* $url = [];
            foreach ($_FILES['Product'] as $key => $value) {
                foreach ($value['url'] as $k => $v) {
                    $url[$k][$key] = $v;
                }
            } */

            $modelsProductspecifications = Model::createMultiple(Productspecifications::classname());
            Model::loadMultiple($modelsProductspecifications, Yii::$app->request->post());
            $indexedMedicalHealthModels = [];
            foreach ($modelsProductspecifications as $i => $mh) {
                $indexedMedicalHealthModels[$i] = $mh; // index matches POST index (e.g., 0,1,2)
            }
            if (isset($_POST['Productspecificationsdetail'])) {
                foreach ($_POST['Productspecificationsdetail'] as $indexSubmenu => $rooms) {
                    if (isset($_POST['Productspecificationsdetail'][$indexSubmenu])) {
                        $medicalHealthTitle = isset($indexedMedicalHealthModels[$indexSubmenu]) ? $indexedMedicalHealthModels[$indexSubmenu]->title : '';
                        foreach ($rooms as $indexProductspecificationsdetail => $room) {


                            $data['Productspecificationsdetail'] = $room;
                            $modelRoom = new Productspecificationsdetail();
                            $modelRoom->load($data);
                            $modelRoom->sort_order = $indexProductspecificationsdetail;
                            $modelsProductspecificationsdetail[$indexSubmenu][$indexProductspecificationsdetail] = $modelRoom;
                            $valid = $modelRoom->validate();
                        }
                    }
                }
            }

            $Productimage = Model::createMultiple(Productimage::classname());
            Model::loadMultiple($Productimage, Yii::$app->request->post());
            foreach ($Productimage as $index => $modelServiceItem) {
                $modelServiceItem->sort_order = $index;
                $modelServiceItem->file = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]file");
                $modelServiceItem->video = \yii\web\UploadedFile::getInstance($modelServiceItem, "[{$index}]video");
                $modelServiceItem->upload();
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {
                    foreach ($modelsProductspecifications as $indexSubmenu => $modelSubmenu) {

                        if ($flag === false) {
                            break;
                        }

                        $modelSubmenu->product_id = $model->product_id;
                        if (!($flag = $modelSubmenu->save())) {
                            break;
                        }

                        if (isset($_POST['Productspecificationsdetail'][$indexSubmenu])) {
                            if (isset($modelsProductspecificationsdetail[$indexSubmenu]) && is_array($modelsProductspecificationsdetail[$indexSubmenu])) {
                                foreach ($modelsProductspecificationsdetail[$indexSubmenu] as $indexProductspecificationsdetail => $modelRoom) {
                                    $modelRoom->product_specifications_id = $modelSubmenu->product_specifications_id;

                                    if (!($flag = $modelRoom->save(false))) {
                                        //Yii::$app->MyFunctions->getModelErrors($modelRoom,"Y");
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    $product_id = $model->product_id;
                    foreach ($Productimage as $models) {
                        $models->product_id = $product_id;
                        if (!($flag = $models->save())) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    /* if (!empty($url)) {
                        foreach ($url as $key => $file) {

                            if ($file['error'] == 0) {

                                //$type = explode('/', $file['type'])[0];
                                //echo "<pre>"; print_r(); exit;
                                $image = Yii::$app->MyFunctions->upload_file($file, "uploads/images/product/media/" . $model->product_id . "/");

                                if ($image != false) {
                                    //$size=getimagesize($image);
                                    $Productimage = new Productimage();
                                    $Productimage->file = $image;
                                    $Productimage->product_id = $model->product_id;
                                    if (!$Productimage->save(false)) {
                                        throw new Exception("Transaction faild Please try again");
                                    }
                                }
                            }
                        }
                    } */
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Product added successfully.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'modelsProductspecifications' => (empty($modelsProductspecifications)) ? [new Productspecifications] : $modelsProductspecifications,
            'modelsProductspecificationsdetail' => (empty($modelsProductspecificationsdetail)) ? [[new Productspecificationsdetail]] : $modelsProductspecificationsdetail,
            'Productimage' => (empty($Productimage)) ? [new Productimage] : $Productimage,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $modelsProductspecifications = $model->productSpecifications;
        $modelsProductspecificationsdetail = [];
        $Productimage = $model->productImages;
        $oldRooms = [];
        if (!empty($modelsProductspecifications)) {
            foreach ($modelsProductspecifications as $indexHouse => $modelHouse) {
                $rooms = (!empty($modelHouse->productSpecificationsDetails)) ? $modelHouse->productSpecificationsDetails : [new Productspecificationsdetail];

                $modelsProductspecificationsdetail[$indexHouse] = $rooms;
                $oldRooms = ArrayHelper::merge(ArrayHelper::index($rooms, 'product_specifications_detail_id'), $oldRooms);
            }
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }

            /* $url = [];
            foreach ($_FILES['Product'] as $key => $value) {
                foreach ($value['url'] as $k => $v) {
                    $url[$k][$key] = $v;
                }
            } */
            // reset
            $modelsProductspecificationsdetail = [];

            $oldHouseIDs = ArrayHelper::map($modelsProductspecifications, 'product_specifications_id', 'product_specifications_id');
            $modelsProductspecifications = Model::createMultiple(Productspecifications::classname(), $modelsProductspecifications);
            //echo "<pre>";print_r($modelsProductspecifications);exit;
            Model::loadMultiple($modelsProductspecifications, Yii::$app->request->post());
            $deletedHouseIDs = array_diff($oldHouseIDs, array_filter(ArrayHelper::map($modelsProductspecifications, 'product_specifications_id', 'product_specifications_id')));

            $roomsIDs = [];
            $indexedMedicalHealthModels = [];
            foreach ($modelsProductspecifications as $i => $mh) {
                $indexedMedicalHealthModels[$i] = $mh; // index matches POST index (e.g., 0,1,2)
            }
            if (isset($_POST['Productspecificationsdetail'])) {
                foreach ($_POST['Productspecificationsdetail'] as $indexHouse => $rooms) {

                    $roomsIDs = ArrayHelper::merge($roomsIDs, array_filter(ArrayHelper::getColumn($rooms, 'product_specifications_detail_id')));
                    $medicalHealthTitle = isset($indexedMedicalHealthModels[$indexHouse]) ? $indexedMedicalHealthModels[$indexHouse]->title : '';
                    foreach ($rooms as $indexRoom => $room) {

                        $data['Productspecificationsdetail'] = $room;
                        $modelRoom = (isset($room['product_specifications_detail_id']) && isset($oldRooms[$room['product_specifications_detail_id']])) ? $oldRooms[$room['product_specifications_detail_id']] : new Productspecificationsdetail;
                        $modelRoom->load($data);


                        $modelsProductspecificationsdetail[$indexHouse][$indexRoom] = $modelRoom;
                    }
                }
            }
            //echo "<pre>";print_r($modelsProductspecificationsdetail);exit;
            $oldRoomsIDs = ArrayHelper::getColumn($oldRooms, 'product_specifications_detail_id');
            $deletedRoomsIDs = array_diff($oldRoomsIDs, $roomsIDs);

            $deletedServiceItemIDsProductimage = ArrayHelper::map($Productimage, 'product_image_id', 'product_image_id');
            $Productimage = Model::createMultiple(Productimage::classname(), $Productimage);
            Model::loadMultiple($Productimage, Yii::$app->request->post());
            $deletedServiceItemIDsProductimage = array_diff($deletedServiceItemIDsProductimage, array_filter(ArrayHelper::map($Productimage, 'product_image_id', 'product_image_id')));

            foreach ($Productimage as $index => $modelsuniversityCourse) {

                $modelsuniversityCourse->sort_order = $index;
                $modelsuniversityCourse->file = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]file");
                $modelsuniversityCourse->video = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]video");
                $modelsuniversityCourse->upload();
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {

                    if (!empty($deletedRoomsIDs)) {
                        Productspecificationsdetail::deleteAll(['product_specifications_detail_id' => $deletedRoomsIDs]);
                    }

                    if (!empty($deletedHouseIDs)) {
                        Productspecifications::deleteAll(['product_specifications_id' => $deletedHouseIDs]);
                    }

                    foreach ($modelsProductspecifications as $indexHouse => $modelHouse) {
                        //echo "<pre>";print_r($flag);exit;
                        if ($flag === false) {
                            break;
                        }

                        $modelHouse->product_id = $model->product_id;

                        if (!($flag = $modelHouse->save())) {
                            break;
                        }

                        if (isset($_POST['Productspecificationsdetail'][$indexHouse])) {
                            //echo "<pre>";print_r($modelsProductspecificationsdetail);exit;
                            if (isset($modelsProductspecificationsdetail[$indexHouse]) && is_array($modelsProductspecificationsdetail[$indexHouse])) {

                                foreach ($modelsProductspecificationsdetail[$indexHouse] as $indexRoom => $modelRoom) {
                                    //echo "<pre>";print_r($modelRoom);exit;
                                    $modelRoom->product_specifications_id = $modelHouse->product_specifications_id;
                                    //echo "<pre>";print_r($modelRoom->save());exit;
                                    if (!($flag = $modelRoom->save())) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($deletedServiceItemIDsProductimage)) {
                        Productimage::deleteAll(['product_image_id' => $deletedServiceItemIDsProductimage]);
                    }

                    foreach ($Productimage as $indexServiceItem => $modelsuniversityCourse) {

                        if ($flag === false) {
                            break;
                        }

                        $modelsuniversityCourse->product_id = $model->product_id;
                        if (!($flag = $modelsuniversityCourse->save())) {
                            break;
                        }
                    }

                    /* if (!empty($url)) {
                        foreach ($url as $key => $file) {

                            if ($file['error'] == 0) {

                                //$type = explode('/', $file['type'])[0];
                                //echo "<pre>"; print_r(); exit;
                                $image = Yii::$app->MyFunctions->upload_file($file, "uploads/images/product/media/" . $model->product_id . "/");

                                if ($image != false) {
                                    //$size=getimagesize($image);
                                    $Productimage = new Productimage();
                                    $Productimage->file = $image;
                                    $Productimage->product_id = $model->product_id;
                                    if (!$Productimage->save(false)) {
                                        throw new Exception("Transaction faild Please try again");
                                    }
                                }
                            }
                        }
                    } */
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Product updated  successfully.');
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
            'modelsProductspecifications' => (empty($modelsProductspecifications)) ? [new Productspecifications] : $modelsProductspecifications,
            'modelsProductspecificationsdetail' => (empty($modelsProductspecificationsdetail)) ? [[new Productspecificationsdetail]] : $modelsProductspecificationsdetail,
            'Productimage' => (empty($Productimage)) ? [new Productimage] : $Productimage,
        ]);
    }

    public function actionCopy($id)
    {
        $models = $this->findModel($id);
        $modelsProductspecificationss = $models->productSpecifications;
        $modelsProductspecificationsdetail = [];
        $Productimages = [];
        $oldRooms = [];


        $model = new Product();
        $models->scenario = 'create';
        $modelsProductspecifications = [new Productspecifications];
        //$modelsProductspecificationsdetail = [[new Productspecificationsdetail]];
        $Productimage = [new Productimage];
        $models->image = '';
        $models->title = '';
        $models->product_category_id = '';
        if ($model->load(Yii::$app->request->post())) {

            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }



            /* $url = [];
            foreach ($_FILES['Product'] as $key => $value) {
                foreach ($value['url'] as $k => $v) {
                    $url[$k][$key] = $v;
                }
            } */
            // reset
            $modelsProductspecificationsdetail = [];

            $oldHouseIDs = ArrayHelper::map($modelsProductspecifications, 'product_specifications_id', 'product_specifications_id');
            $modelsProductspecifications = Model::createMultiple(Productspecifications::classname(), $modelsProductspecifications);
            //echo "<pre>";print_r($modelsProductspecifications);exit;
            Model::loadMultiple($modelsProductspecifications, Yii::$app->request->post());
            $deletedHouseIDs = array_diff($oldHouseIDs, array_filter(ArrayHelper::map($modelsProductspecifications, 'product_specifications_id', 'product_specifications_id')));

            $roomsIDs = [];
            $indexedMedicalHealthModels = [];
            foreach ($modelsProductspecifications as $i => $mh) {
                $indexedMedicalHealthModels[$i] = $mh; // index matches POST index (e.g., 0,1,2)
            }
            if (isset($_POST['Productspecificationsdetail'])) {
                foreach ($_POST['Productspecificationsdetail'] as $indexHouse => $rooms) {

                    $roomsIDs = ArrayHelper::merge($roomsIDs, array_filter(ArrayHelper::getColumn($rooms, 'product_specifications_detail_id')));
                    $medicalHealthTitle = isset($indexedMedicalHealthModels[$indexHouse]) ? $indexedMedicalHealthModels[$indexHouse]->title : '';
                    foreach ($rooms as $indexRoom => $room) {

                        $data['Productspecificationsdetail'] = $room;
                        $modelRoom = (isset($room['product_specifications_detail_id']) && isset($oldRooms[$room['product_specifications_detail_id']])) ? $oldRooms[$room['product_specifications_detail_id']] : new Productspecificationsdetail;
                        $modelRoom->load($data);


                        $modelsProductspecificationsdetail[$indexHouse][$indexRoom] = $modelRoom;
                    }
                }
            }
            //echo "<pre>";print_r($modelsProductspecificationsdetail);exit;
            $oldRoomsIDs = ArrayHelper::getColumn($oldRooms, 'product_specifications_detail_id');
            $deletedRoomsIDs = array_diff($oldRoomsIDs, $roomsIDs);

            $deletedServiceItemIDsProductimage = ArrayHelper::map($Productimage, 'product_image_id', 'product_image_id');
            $Productimage = Model::createMultiple(Productimage::classname(), $Productimage);
            Model::loadMultiple($Productimage, Yii::$app->request->post());
            $deletedServiceItemIDsProductimage = array_diff($deletedServiceItemIDsProductimage, array_filter(ArrayHelper::map($Productimage, 'product_image_id', 'product_image_id')));

            foreach ($Productimage as $index => $modelsuniversityCourse) {

                $modelsuniversityCourse->sort_order = $index;
                $modelsuniversityCourse->file = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]file");
                $modelsuniversityCourse->video = \yii\web\UploadedFile::getInstance($modelsuniversityCourse, "[{$index}]video");
                $modelsuniversityCourse->upload();
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save()) {

                    if (!empty($deletedRoomsIDs)) {
                        Productspecificationsdetail::deleteAll(['product_specifications_detail_id' => $deletedRoomsIDs]);
                    }

                    if (!empty($deletedHouseIDs)) {
                        Productspecifications::deleteAll(['product_specifications_id' => $deletedHouseIDs]);
                    }

                    foreach ($modelsProductspecifications as $indexHouse => $modelHouse) {
                        //echo "<pre>";print_r($flag);exit;
                        if ($flag === false) {
                            break;
                        }

                        $modelHouse->product_id = $model->product_id;

                        if (!($flag = $modelHouse->save())) {
                            break;
                        }

                        if (isset($_POST['Productspecificationsdetail'][$indexHouse])) {
                            //echo "<pre>";print_r($modelsProductspecificationsdetail);exit;
                            if (isset($modelsProductspecificationsdetail[$indexHouse]) && is_array($modelsProductspecificationsdetail[$indexHouse])) {

                                foreach ($modelsProductspecificationsdetail[$indexHouse] as $indexRoom => $modelRoom) {
                                    //echo "<pre>";print_r($modelRoom);exit;
                                    $modelRoom->product_specifications_id = $modelHouse->product_specifications_id;
                                    //echo "<pre>";print_r($modelRoom->save());exit;
                                    if (!($flag = $modelRoom->save())) {
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($deletedServiceItemIDsProductimage)) {
                        Productimage::deleteAll(['product_image_id' => $deletedServiceItemIDsProductimage]);
                    }

                    foreach ($Productimage as $indexServiceItem => $modelsuniversityCourse) {

                        if ($flag === false) {
                            break;
                        }

                        $modelsuniversityCourse->product_id = $model->product_id;
                        if (!($flag = $modelsuniversityCourse->save())) {
                            break;
                        }
                    }

                    /* if (!empty($url)) {
                        foreach ($url as $key => $file) {

                            if ($file['error'] == 0) {

                                //$type = explode('/', $file['type'])[0];
                                //echo "<pre>"; print_r(); exit;
                                $image = Yii::$app->MyFunctions->upload_file($file, "uploads/images/product/media/" . $model->product_id . "/");

                                if ($image != false) {
                                    //$size=getimagesize($image);
                                    $Productimage = new Productimage();
                                    $Productimage->file = $image;
                                    $Productimage->product_id = $model->product_id;
                                    if (!$Productimage->save(false)) {
                                        throw new Exception("Transaction faild Please try again");
                                    }
                                }
                            }
                        }
                    } */
                }

                if ($flag) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Product updated  successfully.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        if (!empty($modelsProductspecificationss)) {
            foreach ($modelsProductspecificationss as $indexHouse => $modelHouse) {
                $rooms = (!empty($modelHouse->productSpecificationsDetails)) ? $modelHouse->productSpecificationsDetails : [new Productspecificationsdetail];

                $modelsProductspecificationsdetail[$indexHouse] = $rooms;
                $oldRooms = ArrayHelper::merge(ArrayHelper::index($rooms, 'product_specifications_detail_id'), $oldRooms);
            }
        }
        return $this->render('update', [
            'model' => $models,
            'modelsProductspecifications' => (empty($modelsProductspecificationss)) ? [new Productspecifications] : $modelsProductspecificationss,
            'modelsProductspecificationsdetail' => (empty($modelsProductspecificationsdetail)) ? [[new Productspecificationsdetail]] : $modelsProductspecificationsdetail,
            'Productimage' => (empty($Productimages)) ? [new Productimage] : $Productimages,
        ]);
    }





    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->getOldAttribute('image');

        if (!empty($oldImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionDeleteimage()
    {
        if (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) {
            $Productimage = Productimage::findOne($_REQUEST['key']);
            $file = $Productimage->file;
            $Productimage->delete();
            unlink(Yii::getAlias('@app') . "/" . $file);
        }
        return true;
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Product ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['product_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
