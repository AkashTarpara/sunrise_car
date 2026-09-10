<?php

namespace app\controllers;

use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class EventController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                    ['allow' => false, 'roles' => ['?']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new EventSearch();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Event();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Event added successfully.');
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $oldImage = $model->getOldAttribute('image');
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->upload();
            } else {
                $model->image = $oldImage;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Event updated successfully.');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->image && file_exists(Yii::getAlias('@app') . '/' . $model->image)) {
            @unlink(Yii::getAlias('@app') . '/' . $model->image);
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionChangestatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status === 'Active' ? 'Inactive' : 'Active';
        $model->save(false);
        return Yii::$app->MyFunctions->JsonPrint(['status' => 200, 'message' => 'Event status updated.']);
    }

    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
