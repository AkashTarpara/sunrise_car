<?php

namespace app\controllers;

use app\models\Installationcomplexity;
use app\models\InstallationcomplexitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InstallationcomplexityController implements the CRUD actions for Installationcomplexity model.
 */
class InstallationcomplexityController extends Controller
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
     * Lists all Installationcomplexity models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InstallationcomplexitySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Installationcomplexity model.
     * @param int $installation_complexity_id Installation Complexity ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($installation_complexity_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($installation_complexity_id),
        ]);
    }

    /**
     * Creates a new Installationcomplexity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Installationcomplexity();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['spacetype/index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Installationcomplexity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $installation_complexity_id Installation Complexity ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['spacetype/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Installationcomplexity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $installation_complexity_id Installation Complexity ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($installation_complexity_id)
    {
        $this->findModel($installation_complexity_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Installationcomplexity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $installation_complexity_id Installation Complexity ID
     * @return Installationcomplexity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($installation_complexity_id)
    {
        if (($model = Installationcomplexity::findOne(['installation_complexity_id' => $installation_complexity_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
