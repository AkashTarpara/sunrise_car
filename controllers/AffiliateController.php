<?php

namespace app\controllers;

use Yii;
use app\models\Affiliate;
use app\models\AffiliateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AffiliateController implements read/export actions for Affiliate model.
 */
class AffiliateController extends Controller
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
     * Lists all Affiliate models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AffiliateSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Affiliate model.
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
     * Updates the status of an Affiliate application (Pending/Approved/Rejected).
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->post('status');

        if ($status !== null && array_key_exists((int) $status, Affiliate::statusOptions())) {
            $model->status = (int) $status;
            $model->admin_notes = Yii::$app->request->post('admin_notes', $model->admin_notes);
            $model->save(false, ['status', 'admin_notes', 'updated_at']);
        }

        return $this->redirect(['view', 'id' => $model->affiliate_id]);
    }

    /**
     * Deletes an existing Affiliate model.
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
     * Finds the Affiliate model based on its primary key value.
     * @param int $id
     * @return Affiliate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Affiliate::findOne(['affiliate_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
