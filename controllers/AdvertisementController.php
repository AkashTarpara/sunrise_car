<?php

namespace app\controllers;

use app\models\Advertisement;
use app\models\AdvertisementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertisementController implements the CRUD actions for Advertisement model.
 */
class AdvertisementController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Advertisement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdvertisementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advertisement model.
     * @param int $advertisement_id Advertisement ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($advertisement_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($advertisement_id),
        ]);
    }

    /**
     * Creates a new Advertisement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Advertisement();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'advertisement_id' => $model->advertisement_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Advertisement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $advertisement_id Advertisement ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($advertisement_id)
    {
        $model = $this->findModel($advertisement_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'advertisement_id' => $model->advertisement_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Advertisement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $advertisement_id Advertisement ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($advertisement_id)
    {
        $this->findModel($advertisement_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advertisement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $advertisement_id Advertisement ID
     * @return Advertisement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($advertisement_id)
    {
        if (($model = Advertisement::findOne(['advertisement_id' => $advertisement_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
