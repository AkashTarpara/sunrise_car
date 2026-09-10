<?php

namespace app\controllers;

use Yii;
use app\models\Installation;
use app\models\Installationbanner;
use app\models\InstallationSearch;
use app\models\Installationprocess;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * InstallationController implements the CRUD actions for Installation model.
 */
class InstallationController extends Controller
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
     * Lists all Installation models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = $this->findModel(1);
        return $this->render('update', [
            'model' => $model,
        ]);

        // $searchModel = new InstallationSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
    }

    /**
     * Displays a single Installation model.
     * @param int $installation_id Installation ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($installation_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($installation_id),
        ]);
    }

    /**
     * Creates a new Installation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Installation();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'installation_id' => $model->installation_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Installation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $installation_id Installation ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $id = 1;
        $model = $this->findModel($id);
        $installationbanner = Installationbanner::find()->all();
        $installationprocess = Installationprocess::find()->all();

        if ($model->load(Yii::$app->request->post())) {

            $oldInstallationBannerIDs = ArrayHelper::map($installationbanner, 'installation_banner_id', 'installation_banner_id');
            $installationbanner = Model::createMultiple(Installationbanner::classname(), $installationbanner);
            Model::loadMultiple($installationbanner, Yii::$app->request->post());

            $oldInstallationProcessIDs = ArrayHelper::map($installationprocess, 'installation_process_id', 'installation_process_id');
            $installationprocess = Model::createMultiple(Installationprocess::classname(), $installationprocess);
            Model::loadMultiple($installationprocess, Yii::$app->request->post());

            $deletedInstallationBannerIDs = array_diff(
                $oldInstallationBannerIDs,
                array_filter(ArrayHelper::map($installationbanner, 'installation_banner_id', 'installation_banner_id'))
            );

            $deletedInstallationProcessIDs = array_diff(
                $oldInstallationProcessIDs,
                array_filter(ArrayHelper::map($installationprocess, 'installation_process_id', 'installation_process_id'))
            );

            if (!empty($deletedInstallationBannerIDs)) {
                Installationbanner::deleteAll([
                    'installation_banner_id' => $deletedInstallationBannerIDs
                ]);
            }
            if (!empty($deletedInstallationProcessIDs)) {
                Installationprocess::deleteAll([
                    'installation_process_id' => $deletedInstallationProcessIDs
                ]);
            }

            $model->before_image = $model->getOldAttribute("before_image");
            if (is_object(UploadedFile::getInstance($model, 'before_image'))) {
                $model->before_image = UploadedFile::getInstance($model, 'before_image');
                $model->upload();
            }

            $model->after_image = $model->getOldAttribute("after_image");
            if (is_object(UploadedFile::getInstance($model, 'after_image'))) {
                $model->after_image = UploadedFile::getInstance($model, 'after_image');
                $model->upload();
            }

            $model->video = $model->getOldAttribute("video");
            if (is_object(UploadedFile::getInstance($model, 'video'))) {
                $model->video = UploadedFile::getInstance($model, 'video');
                $model->upload();
            }

            $model->save();

            foreach ($installationbanner as $index => $banner) {

                $banner->image = $banner->getOldAttribute('image');
                
                if (is_object(UploadedFile::getInstance($banner, "[{$index}]image"))) {
                    $banner->image = UploadedFile::getInstance($banner, "[{$index}]image");
                    $banner->upload();
                }

                $banner->save();
            }

            foreach ($installationprocess as $index => $process) {

                $process->image = $process->getOldAttribute('image');
                
                if (is_object(UploadedFile::getInstance($process, "[{$index}]image"))) {
                    $process->image = UploadedFile::getInstance($process, "[{$index}]image");
                    $process->upload();
                }

                // echo "<pre>"; print_r($process->image); exit;

                if(!$process->save()) {
                    Yii::$app->MyFunctions->getModelErrors($process,"Y");
                }
            }

            return $this->redirect('update');
        }

        return $this->render('update', [
            'model' => $model,
            'installationbanner' => (empty($installationbanner)) ? [new Installationbanner] : $installationbanner,
            'installationprocess' => (empty($installationprocess)) ? [new Installationprocess] : $installationprocess,
        ]);
    }

    /**
     * Deletes an existing Installation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $installation_id Installation ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($installation_id)
    {
        $this->findModel($installation_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Installation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $installation_id Installation ID
     * @return Installation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($installation_id)
    {
        if (($model = Installation::findOne(['installation_id' => $installation_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
