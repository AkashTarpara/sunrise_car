<?php

namespace app\controllers;

use Yii;
use app\models\Adminuser;
use app\models\AdminuserSearch;
use app\models\Appuser;
use app\models\AppuserSearch;
use app\models\PasswordForm;
use app\models\Authitem;
use app\models\Authassignment;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * AdminuserController implements the CRUD actions for Adminuser model.
 */
class AdminuserController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new AppuserSearch();
        $dataProvider = $searchModel->searchadmin($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Appuser model.
     * @param int $id Appuser ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Appuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Appuser();
        $model->scenario = 'createadmin';
        $queryRols = Authitem::find()->where(['type' => 1])->all();
        $rolsArr = ArrayHelper::map($queryRols, 'name', 'name');
        $model->role = '1';
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                if ($model->rols) {
                    foreach ($model->rols as $key => $item) {
                        $ischeck = Authassignment::find()->where([
                            'item_name' => $item,
                            'user_id' => $model->appuser_id,
                        ])->one();

                        if (empty($ischeck)) {
                            $authassignment = new Authassignment();
                            $authassignment->item_name = $item;
                            $authassignment->user_id = (string)$model->appuser_id;
                            $authassignment->created_at = time();
                            if (!$authassignment->save()) {
                                Yii::$app->MyFunctions->getModelErrors($authassignment, "Y");
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Admin User has been added successfully.');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'rolsArr' => $rolsArr,
            'selectArr' => []
        ]);
    }

    /**
     * Updates an existing Appuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Appuser ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $queryRols = Authitem::find()->where(['type' => 1])->all();
        $rolsArr = ArrayHelper::map($queryRols, 'name', 'name');
        $selectArr = [];
        if ($model->authAssignments) {
            foreach ($model->authAssignments as $item) {
                $selectArr[$item->item_name] = array('Selected' => true);
            }
        }
        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->save()) {
                Authassignment::deleteAll(['user_id' => $model->appuser_id]);
                if ($model->rols) {
                    foreach ($model->rols as $key => $item) {

                        $authassignment = new Authassignment();
                        $authassignment->item_name = $item;
                        $authassignment->user_id = (string) $model->appuser_id;
                        $authassignment->created_at = time();
                        if (!$authassignment->save()) {
                            Yii::$app->MyFunctions->getModelErrors($authassignment, "Y");
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('success', "Admin User has been Updated successfully.");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'rolsArr' => $rolsArr,
            'selectArr' => $selectArr
        ]);
    }

    /**
     * Deletes an existing Adminuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Adminuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Adminuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appuser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
