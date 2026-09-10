<?php

namespace app\controllers;

use Yii;
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
 * AppuserController implements the CRUD actions for Appuser model.
 */
class AppuserController extends Controller
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
     * Lists all Appuser models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AppuserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'appuser_id' => $model->appuser_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateadmin($id = '')
    {
        $id = Yii::$app->user->identity->appuser_id;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }


            $model->site_logo = $model->getOldAttribute("site_logo");
            if (is_object(UploadedFile::getInstance($model, 'site_logo'))) {
                $model->site_logo = UploadedFile::getInstance($model, 'site_logo');
                $model->upload();
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Profile updated successfully.");
                return $this->redirect(['/dashboard']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionResetpassword()
    {
        //$this->layout="login_new";
        $id = Yii::$app->user->identity->appuser_id;
        $businesses = $this->findModel($id);
        //$businesses->scenario = "resetpassword";
        $model = new PasswordForm();
        $model->scenario = "changepassword";

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //echo "<pre>"; print_r(Yii::$app->request->post()); exit;
            if (sha1($model->old_password) == $businesses->password) {
                $businesses->password = sha1($model->password);
                if ($businesses->save(false)) {
                    Yii::$app->session->setFlash('success', "password updated successfully.");
                    return $this->redirect(['/dashboard']);
                } else {
                    Yii::$app->MyFunctions->getModelErrors($businesses, "Y");
                }
            } else {
                Yii::$app->session->setFlash('error', "old password does not match");
                return $this->render('updatepassword', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('updatepassword', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Appuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Appuser ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Appuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Appuser ID
     * @return Appuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appuser::findOne(['appuser_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
