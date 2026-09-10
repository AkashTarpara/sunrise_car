<?php

namespace app\controllers;

use Yii;
use app\models\Userorder;
use app\models\UserorderdetailSearch;
use app\models\UserorderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserorderController implements the CRUD actions for Userorder model.
 */
class UserorderController extends Controller
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
     * Lists all Userorder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserorderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userorder model.
     * @param int $id User Order ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new UserorderdetailSearch();
        $dataProvider = $searchModel->userorderdetailsearch(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeliverystatus()
    {
        if (Yii::$app->request->isPost) {
            $model = $this->findModel(Yii::$app->request->post('id'));
            $delivery_status = Yii::$app->request->post('status');
            $model->delivery_status = $delivery_status;
            if (!$model->save()) {
                Yii::$app->getSession()->setFlash('error', 'Delivery Status not changed.');
            }
            $model->sendOrderUpdateEmail();
            Yii::$app->getSession()->setFlash('success', 'Delivery Status Changed.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Userorder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Userorder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'user_order_id' => $model->user_order_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Userorder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id User Order ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_order_id' => $model->user_order_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Userorder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id User Order ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Userorder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id User Order ID
     * @return Userorder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userorder::findOne(['user_order_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
