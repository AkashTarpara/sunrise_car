<?php

namespace app\controllers;

use app\models\Subfloorcondition;
use app\models\SubfloorconditionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubfloorconditionController implements the CRUD actions for Subfloorcondition model.
 */
class SubfloorconditionController extends Controller
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
     * Lists all Subfloorcondition models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SubfloorconditionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subfloorcondition model.
     * @param int $subfloor_condition_id Subfloor Condition ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($subfloor_condition_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($subfloor_condition_id),
        ]);
    }

    /**
     * Creates a new Subfloorcondition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Subfloorcondition();

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
     * Updates an existing Subfloorcondition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $subfloor_condition_id Subfloor Condition ID
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
     * Deletes an existing Subfloorcondition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $subfloor_condition_id Subfloor Condition ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($subfloor_condition_id)
    {
        $this->findModel($subfloor_condition_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subfloorcondition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $subfloor_condition_id Subfloor Condition ID
     * @return Subfloorcondition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($subfloor_condition_id)
    {
        if (($model = Subfloorcondition::findOne(['subfloor_condition_id' => $subfloor_condition_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
