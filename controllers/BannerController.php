<?php

namespace app\controllers;

use Yii;
use app\models\Banner;
use app\models\BannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends Controller
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
     * Lists all Banner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => Banner::find()->count(),
        ]);
    }

    /**
     * Displays a single Banner model.
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
        //$str = Yii::$app->MyFunctions->decode($id);
        $str = $_REQUEST['id'];
        $model = $this->findModel($str);
        $message = "";
        if ($model->status == "Active") {
            $model->status = "Inactive";
            $message = "Selected Banner has been deactivated.";
        } else {
            $model->status = "Active";
            $message = "Selected Banner has been activated.";
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
            $query = "UPDATE banner SET display_order = display_order + 1 WHERE display_order < " . $current . " and display_order >=" . $target;
            $query1 = "UPDATE banner SET display_order = " . $target . " WHERE banner_id =" . $id;
        } else if ($target > $current) {
            $query = "UPDATE banner SET display_order = display_order - 1 WHERE display_order <= " . $target . " AND display_order > " . $current;
            $query1 = "UPDATE banner SET display_order = " . $target . " WHERE banner_id =" . $id;
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
     * Creates a new Banner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banner();
        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();

            $model->video = UploadedFile::getInstance($model, 'video');
            $model->uploadvideo();

            $model->mobile_video = UploadedFile::getInstance($model, 'mobile_video');
            $model->uploadvideo();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Banner added  successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Banner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {

            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }


            $model->video = $model->getOldAttribute("video");
            if (is_object(UploadedFile::getInstance($model, 'video'))) {
                $model->video = UploadedFile::getInstance($model, 'video');
                $model->uploadvideo();
            }

            $model->mobile_video = $model->getOldAttribute("mobile_video");
            if (is_object(UploadedFile::getInstance($model, 'mobile_video'))) {
                $model->mobile_video = UploadedFile::getInstance($model, 'mobile_video');
                $model->uploadvideo();
            }


            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Banner updated successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Banner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->getOldAttribute('image');
        $oldVideo = $model->getOldAttribute('video');
        $oldMobileVideo = $model->getOldAttribute('mobile_video');

        if (!empty($oldImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        if (!empty($oldVideo)) {
            $filePathVideo = Yii::getAlias('@app') . '/' . $oldVideo;
            if (file_exists($filePathVideo)) {
                @unlink($filePathVideo);
            }
        }
        if (!empty($oldMobileVideo)) {
            $filePathMobileVideo = Yii::getAlias('@app') . '/' . $oldMobileVideo;
            if (file_exists($filePathMobileVideo)) {
                @unlink($filePathMobileVideo);
            }
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Banner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
