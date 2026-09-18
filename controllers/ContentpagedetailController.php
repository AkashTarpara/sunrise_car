<?php

namespace app\controllers;

use Yii;
use app\models\Contentpagedetail;
use app\models\ContentpagedetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContentpagedetailController implements the CRUD actions for Contentpagedetail model.
 */
class ContentpagedetailController extends Controller
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
     * Lists all Contentpagedetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ContentpagedetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contentpagedetail model.
     * @param int $id Content Page Detail ID
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
        //$str=Yii::$app->MyFunctions->decode($id );
        $str = $_REQUEST['id'];
        $model = $this->findModel($str);
        if ($model->status == "Active") {
            $model->status = "Inactive";
            $message = "Selected Content Page Detail has been deactivated.";
            //Yii::$app->getSession()->setFlash('success','Selected Content Page Detail has been deactivated.');
        } else {
            $model->status = "Active";
            $message = "Selected Content Page Detail has been activated.";
            //Yii::$app->getSession()->setFlash('success','Selected Content Page Detail has been activated.');
        }
        if (!$model->save()) {
            Yii::$app->MyFunctions->getModelErrors($model, "Y");
        }
        return Yii::$app->MyFunctions->JsonPrint(array('status' => 200, 'message' => $message));
        //return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionChangeorder()
    {
        //echo "<pre>";print_r($_REQUEST);exit;
        $id = $_REQUEST['id'];
        $content_page_id = $_REQUEST['content_page_id'];
        $current = $_REQUEST['current'];
        $target = $_REQUEST['target'];
        $total = $_REQUEST['total'];
        $connection = \Yii::$app->db;
        if ($target > $total || $target == 0) {
            Yii::$app->getSession()->setFlash(
                'error',
                'Enter valid sequence.'
            );
            return $this->redirect(Yii::$app->request->referrer);
        } else if ($target < $current) {
            $query = "UPDATE content_page_detail SET display_order = display_order + 1 WHERE display_order < " . $current . " and display_order >=" . $target . " AND content_page_id =" . $content_page_id;
            $query1 = "UPDATE content_page_detail SET display_order = " . $target . " WHERE content_page_detail_id =" . $id . " AND content_page_id =" . $content_page_id;
        } else if ($target > $current) {
            $query = "UPDATE content_page_detail SET display_order = display_order - 1 WHERE display_order <= " . $target . " AND display_order > " . $current . " AND content_page_id =" . $content_page_id;
            $query1 = "UPDATE content_page_detail SET display_order = " . $target . " WHERE content_page_detail_id =" . $id . " AND content_page_id =" . $content_page_id;
        } else {
            Yii::$app->getSession()->setFlash(
                'error',
                'Enter valid sequence.'
            );
            return $this->redirect(Yii::$app->request->referrer);
        }
        $command = $connection->createCommand($query);
        $command->execute();
        $command2 = $connection->createCommand($query1);
        $command2->execute();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new Contentpagedetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Contentpagedetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'content_page_detail_id' => $model->content_page_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Contentpagedetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Content Page Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'content_page_detail_id' => $model->content_page_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Contentpagedetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Content Page Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contentpagedetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Content Page Detail ID
     * @return Contentpagedetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contentpagedetail::findOne(['content_page_detail_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
