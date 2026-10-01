<?php

namespace app\controllers;

use Yii;
use app\models\Faq;
use app\models\FaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class FaqController extends Controller
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
                'actions' => ['delete' => ['POST'], 'updateorder' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    public function actionCreate()
    {
        $model = new Faq();
        $model->loadDefaultValues();

        // Preselect type when coming from a filtered list, e.g. faq/create?type=terms_condition
        $type = Yii::$app->request->get('type');
        if ($type && array_key_exists($type, Faq::typeList())) {
            $model->type = $type;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $model->getTypeLabel() . ' added successfully.');
            return $this->redirect(['index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', $model->getTypeLabel() . ' updated successfully.');
            return $this->redirect(['index']);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Record deleted successfully.');
        return $this->redirect(['index']);
    }

    public function actionUpdateorder($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'updateorder';
        $model->display_order = Yii::$app->request->post('display_order');

        if ($model->validate(['display_order'])) {
            // Move this entry to the new position and shift the rest of its type around it
            Faq::reorder($model->type, $model->faq_id, $model->display_order);
            Yii::$app->session->setFlash('success', 'Display order updated.');
            return Yii::$app->MyFunctions->JsonPrint(['status' => 1, 'message' => 'Display order updated.']);
        }
        return Yii::$app->MyFunctions->JsonPrint(['status' => 0, 'message' => Yii::$app->MyFunctions->getModelErrors($model)]);
    }

    public function actionChangestatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status === 'Active' ? 'Inactive' : 'Active';
        $model->save(false);
        return Yii::$app->MyFunctions->JsonPrint(['status' => 200, 'message' => 'Status updated.']);
    }

    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
