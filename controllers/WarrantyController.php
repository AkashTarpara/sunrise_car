<?php

namespace app\controllers;

use Yii;
use app\models\Warranty;
use app\models\WarrantyDocument;
use app\models\Warrantycertificate;
use app\models\WarrantySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * WarrantyController implements the CRUD actions for Warranty model.
 */
class WarrantyController extends Controller
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
     * Lists all Warranty models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = $this->findModel(1);
        return $this->render('update', [
            'model' => $model,
        ]);
        // $searchModel = new WarrantySearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
    }

    /**
     * Displays a single Warranty model.
     * @param int $warranty_id Warranty ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($warranty_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($warranty_id),
        ]);
    }

    /**
     * Creates a new Warranty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Warranty();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'warranty_id' => $model->warranty_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Warranty model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $warranty_id Warranty ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $warranty_id = 1;
        $model = $this->findModel($warranty_id);
        $warrentydocument = WarrantyDocument::find()->andWhere(['warranty_id' => $warranty_id])->all();
        $warrantycertificate = Warrantycertificate::find()->all();

        if ($model->load(Yii::$app->request->post())) {

            $oldWarrentyDocumentIDs = ArrayHelper::map($warrentydocument, 'warranty_document_id', 'warranty_document_id');
            $warrentydocument = Model::createMultiple(WarrantyDocument::classname(), $warrentydocument);
            Model::loadMultiple($warrentydocument, Yii::$app->request->post());

            $deletedWarrentyDocumentIDs = array_diff(
                $oldWarrentyDocumentIDs,
                array_filter(ArrayHelper::map($warrentydocument, 'warranty_document_id', 'warranty_document_id'))
            );

            if (!empty($deletedWarrentyDocumentIDs)) {
                WarrantyDocument::deleteAll([
                    'warranty_document_id' => $deletedWarrentyDocumentIDs
                ]);
            }

            $oldwarrantycertificateIDs = ArrayHelper::map($warrantycertificate, 'warranty_certificate_id', 'warranty_certificate_id');
            $warrantycertificate = Model::createMultiple(Warrantycertificate::classname(), $warrantycertificate);
            Model::loadMultiple($warrantycertificate, Yii::$app->request->post());

            $deletedwarrantycertificateIDs = array_diff(
                $oldwarrantycertificateIDs,
                array_filter(ArrayHelper::map($warrantycertificate, 'warranty_certificate_id', 'warranty_certificate_id'))
            );

            if (!empty($deletedwarrantycertificateIDs)) {
                Warrantycertificate::deleteAll([
                    'warranty_certificate_id' => $deletedwarrantycertificateIDs
                ]);
            }
            
            $model->image = $model->getOldAttribute("image");
            if (is_object(UploadedFile::getInstance($model, 'image'))) {
                $model->image = UploadedFile::getInstance($model, 'image');
                $model->upload();
            }

            $model->save();

            foreach ($warrentydocument as $index => $document) {

                $document->warranty_id = $warranty_id;
                $document->file = $document->getOldAttribute('file');
                
                if (is_object(UploadedFile::getInstance($document, "[{$index}]file"))) {
                    $document->file = UploadedFile::getInstance($document, "[{$index}]file");
                    $document->upload();
                }

                $document->save();
            }

            foreach ($warrantycertificate as $index => $document) {

                $document->image = $document->getOldAttribute('image');
                
                if (is_object(UploadedFile::getInstance($document, "[{$index}]image"))) {
                    $document->image = UploadedFile::getInstance($document, "[{$index}]image");
                    $document->upload();
                }

                $document->save();
            }

            return $this->redirect('update');
        }

        return $this->render('update', [
            'model' => $model,
            'warrentydocument' => (empty($warrentydocument)) ? [new WarrantyDocument] : $warrentydocument,
            'warrantycertificate' => (empty($warrantycertificate)) ? [new Warrantycertificate] : $warrantycertificate,
        ]);
    }

    /**
     * Deletes an existing Warranty model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $warranty_id Warranty ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($warranty_id)
    {
        $this->findModel($warranty_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Warranty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $warranty_id Warranty ID
     * @return Warranty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($warranty_id)
    {
        if (($model = Warranty::findOne(['warranty_id' => $warranty_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
