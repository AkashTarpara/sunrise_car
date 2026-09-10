<?php

namespace app\controllers;

use Yii;
use app\models\Shoppingbybrands;
use app\models\ShoppingbybrandsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ShoppingbybrandsController implements the CRUD actions for Shoppingbybrands model.
 */
class ShoppingbybrandsController extends Controller
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
     * Lists all Shoppingbybrands models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ShoppingbybrandsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => Shoppingbybrands::find()->count(),
        ]);
    }

    /**
     * Displays a single Shoppingbybrands model.
     * @param int $id Shopping By Brands ID
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
            $message = "Selected Shopping By Brands has been deactivated.";
        } else {
            $model->status = "Active";
            $message = "Selected Shopping By Brands has been activated.";
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
            $query = "UPDATE shopping_by_brands_id SET display_order = display_order + 1 WHERE display_order < " . $current . " and display_order >=" . $target;
            $query1 = "UPDATE shopping_by_brands_id SET display_order = " . $target . " WHERE shopping_by_brands_id_id =" . $id;
        } else if ($target > $current) {
            $query = "UPDATE shopping_by_brands_id SET display_order = display_order - 1 WHERE display_order <= " . $target . " AND display_order > " . $current;
            $query1 = "UPDATE shopping_by_brands_id SET display_order = " . $target . " WHERE shopping_by_brands_id_id =" . $id;
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
     * Creates a new Shoppingbybrands model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Shoppingbybrands();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();

            $model->logo = UploadedFile::getInstance($model, 'logo');
            $model->upload();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Shopping By Brands added  successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shoppingbybrands model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Shopping By Brands ID
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
            $model->logo = $model->getOldAttribute("logo");
            if (is_object(UploadedFile::getInstance($model, 'logo'))) {
                $model->logo = UploadedFile::getInstance($model, 'logo');
                $model->upload();
            }

            if ($model->save()) {

                Yii::$app->session->setFlash('success', "Shopping By Brands updated successfully.");
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shoppingbybrands model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Shopping By Brands ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $oldImage = $model->getOldAttribute('image');
        $oldlogo = $model->getOldAttribute('logo');

        if (!empty($oldImage)) {
            $filePath = Yii::getAlias('@app') . '/' . $oldImage;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        if (!empty($oldlogo)) {
            $filelogo = Yii::getAlias('@app') . '/' . $oldlogo;
            if (file_exists($filelogo)) {
                @unlink($filelogo);
            }
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Shoppingbybrands model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Shopping By Brands ID
     * @return Shoppingbybrands the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shoppingbybrands::findOne(['shopping_by_brands_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
