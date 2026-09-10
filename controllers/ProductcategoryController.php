<?php

namespace app\controllers;

use Yii;
use app\models\Productcategory;
use app\models\ProductcategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductcategoryController implements the CRUD actions for Productcategory model.
 */
class ProductcategoryController extends Controller
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
     * Lists all Productcategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductcategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => Productcategory::find()->count(),
        ]);
    }

    /**
     * Displays a single Productcategory model.
     * @param int $id Product Category ID
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
            $message = "Selected Product Category has been deactivated.";
        } else {
            $model->status = "Active";
            $message = "Selected Product Category has been activated.";
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
            $query = "UPDATE product_category SET display_order = display_order + 1 WHERE display_order < " . $current . " and display_order >=" . $target;
            $query1 = "UPDATE product_category SET display_order = " . $target . " WHERE product_category_id =" . $id;
        } else if ($target > $current) {
            $query = "UPDATE product_category SET display_order = display_order - 1 WHERE display_order <= " . $target . " AND display_order > " . $current;
            $query1 = "UPDATE product_category SET display_order = " . $target . " WHERE product_category_id =" . $id;
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
     * Creates a new Productcategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Productcategory();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();

            $model->banner_image = UploadedFile::getInstance($model, 'banner_image');
            $model->upload();

            $model->sample_image = UploadedFile::getInstance($model, 'sample_image');
            $model->upload();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Product Category added  successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Productcategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Product Category ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post())) {

            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }

            $model->banner_image = $model->getOldAttribute("banner_image");
            if (is_object(UploadedFile::getInstance($model, 'banner_image'))) {
                $model->banner_image = UploadedFile::getInstance($model, 'banner_image');
                $model->upload();
            }

            $model->sample_image = $model->getOldAttribute("sample_image");
            if (is_object(UploadedFile::getInstance($model, 'sample_image'))) {
                $model->sample_image = UploadedFile::getInstance($model, 'sample_image');
                $model->upload();
            }

            if ($model->save()) {

                Yii::$app->session->setFlash('success', "Product Category updated successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Productcategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Product Category ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->getOldAttribute('image');
        $oldBannerImage = $model->getOldAttribute('banner_image');
        $oldSampleImage = $model->getOldAttribute('sample_image');

        if (!empty($oldImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        if (!empty($oldBannerImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldBannerImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        if (!empty($oldSampleImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldSampleImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Productcategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Product Category ID
     * @return Productcategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Productcategory::findOne(['product_category_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
