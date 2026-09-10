<?php

namespace app\controllers;

use Yii;
use app\models\Tradepropartner;
use app\models\TradepropartnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TradepropartnerController implements read/export actions for Tradepropartner model.
 */
class TradepropartnerController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'update-status' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tradepropartner models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TradepropartnerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tradepropartner model.
     * @param int $id
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
     * Updates the status of a Tradepropartner application (Pending/Approved/Rejected).
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->post('status');

        if ($status !== null && in_array((int) $status, [Tradepropartner::STATUS_PENDING, Tradepropartner::STATUS_APPROVED, Tradepropartner::STATUS_REJECTED], true)) {
            $model->status = (int) $status;
            $model->save(false, ['status', 'updated_at']);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Tradepropartner model.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tradepropartner model based on its primary key value.
     * @param int $id
     * @return Tradepropartner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tradepropartner::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
