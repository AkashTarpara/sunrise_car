<?php

namespace app\controllers;

use Yii;
use app\models\Userorderdetail;
use app\models\UserorderdetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserorderdetailController implements the CRUD actions for Userorderdetail model.
 */
class UserorderdetailController extends Controller
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
     * Lists all Userorderdetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserorderdetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userorderdetail model.
     * @param int $id User Order Detail ID
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
     * Creates a new Userorderdetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Userorderdetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'user_order_detail_id' => $model->user_order_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Userorderdetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id User Order Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_order_detail_id' => $model->user_order_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Userorderdetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id User Order Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Userorderdetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id User Order Detail ID
     * @return Userorderdetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userorderdetail::findOne(['user_order_detail_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
