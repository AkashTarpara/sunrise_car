<?php

namespace app\controllers;

use Yii;
use app\models\Fleet;
use app\models\FleetImage;
use app\models\FleetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class FleetController extends Controller
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
                'actions' => [
                    'delete' => ['POST'],
                    'deleteimage' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new FleetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Fleet();

        if ($model->load(Yii::$app->request->post())) {
            $model->images = UploadedFile::getInstances($model, 'images');
            if ($model->save()) {
                $model->uploadImages();
                Yii::$app->session->setFlash('success', 'Fleet added successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->images = UploadedFile::getInstances($model, 'images');
            if ($model->save()) {
                $this->deleteSelectedImages(Yii::$app->request->post('delete_image_ids', []));
                $model->uploadImages();
                Yii::$app->session->setFlash('success', 'Fleet updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->softDelete();

        return $this->redirect(['index']);
    }

    public function actionDeleteimage($id)
    {
        $image = FleetImage::findOne(['id' => $id, 'deleted_at' => null]);
        if ($image) {
            $fleetId = $image->fleet_id;
            $this->softDeleteImage($image);
            Yii::$app->session->setFlash('success', 'Fleet image deleted successfully.');
            return $this->redirect(['update', 'id' => $fleetId]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionChangestatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status === 'Active' ? 'Inactive' : 'Active';
        $model->save(false);

        return Yii::$app->MyFunctions->JsonPrint(['status' => 200, 'message' => 'Fleet status updated.']);
    }

    protected function findModel($id)
    {
        if (($model = Fleet::find()->where(['id' => $id, 'deleted_at' => null])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function deleteSelectedImages($imageIds)
    {
        if (!is_array($imageIds)) {
            return;
        }

        foreach ($imageIds as $imageId) {
            $image = FleetImage::findOne(['id' => $imageId, 'deleted_at' => null]);
            if ($image) {
                $this->softDeleteImage($image);
            }
        }
    }

    private function softDeleteImage(FleetImage $image)
    {
        if ($image->image && file_exists(Yii::getAlias('@app') . '/' . $image->image)) {
            @unlink(Yii::getAlias('@app') . '/' . $image->image);
        }

        $image->softDelete();
    }
}
