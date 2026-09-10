<?php

namespace app\controllers;

use app\models\Spacetype;
use app\models\SpacetypeSearch;
use app\models\SubfloorconditionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpacetypeController implements the CRUD actions for Spacetype model.
 */
class SpacetypeController extends Controller
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
     * Lists all Spacetype models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SpacetypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $searchModelSubfloor = new SubfloorconditionSearch();
        $dataProviderSubfloor = $searchModelSubfloor->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelSubfloor' => $searchModelSubfloor,
            'dataProviderSubfloor' => $dataProviderSubfloor,
        ]);
    }

    /**
     * Displays a single Spacetype model.
     * @param int $space_type_id Space Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($space_type_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($space_type_id),
        ]);
    }

    /**
     * Creates a new Spacetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Spacetype();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Spacetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $space_type_id Space Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Spacetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $space_type_id Space Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($space_type_id)
    {
        $this->findModel($space_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Spacetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $space_type_id Space Type ID
     * @return Spacetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($space_type_id)
    {
        if (($model = Spacetype::findOne(['space_type_id' => $space_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
