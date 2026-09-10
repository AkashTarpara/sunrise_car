<?php

namespace app\controllers;

use Yii;
use app\models\Generalsetting;
use app\models\GeneralsettingSearch;
use app\models\Advertisement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\widgets\Model;

/**
 * GeneralsettingController implements the CRUD actions for Generalsetting model.
 */
class GeneralsettingController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Generalsetting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GeneralsettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Generalsetting model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Generalsetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Generalsetting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->setting_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Generalsetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $model = $this->findModel(1);
        $advertisement = Advertisement::find()->andWhere(["type" => "product"])->all();
        $advertisementhome = Advertisement::find()->andWhere(["type" => "homepage"])->all();

        if ($model->load(Yii::$app->request->post())) {

            $model->contact_us_image = $model->getOldAttribute("contact_us_image");
            if (is_object(UploadedFile::getInstance($model, 'contact_us_image'))) {
                $model->contact_us_image = UploadedFile::getInstance($model, 'contact_us_image');
                $model->upload();
            }

            /* $model->download_calendar = $model->getOldAttribute("download_calendar");
            if (is_object(UploadedFile::getInstance($model, 'download_calendar'))) {
                $model->download_calendar = UploadedFile::getInstance($model, 'download_calendar');
                $model->upload();
            }

            $model->match_centre_powered_by_image = $model->getOldAttribute("match_centre_powered_by_image");
            if (is_object(UploadedFile::getInstance($model, 'match_centre_powered_by_image'))) {
                $model->match_centre_powered_by_image = UploadedFile::getInstance($model, 'match_centre_powered_by_image');
                $model->upload();
            } */

            $oldAdvertisementIDs = ArrayHelper::map($advertisement, 'advertisement_id', 'advertisement_id');

            $advertisement = Model::createMultiple(Advertisement::className(), $advertisement);
            Model::loadMultiple($advertisement, Yii::$app->request->post());

            $deletedAdvertisementIDs = array_diff(
                $oldAdvertisementIDs,
                array_filter(ArrayHelper::map($advertisement, 'advertisement_id', 'advertisement_id'))
            );

            if (!empty($deletedAdvertisementIDs)) {
                Advertisement::deleteAll([
                    'and',
                    ['advertisement_id' => $deletedAdvertisementIDs],
                    ['type' => 'product']
                ]);
            }


            $oldAdvertisementHomeIDs = ArrayHelper::map($advertisementhome, 'advertisement_id', 'advertisement_id');

            $advertisementhome = Model::createMultiple(Advertisement::className(), $advertisementhome);
            Model::loadMultiple($advertisementhome, Yii::$app->request->post());

            $deletedAdvertisementHomeIDs = array_diff(
                $oldAdvertisementHomeIDs,
                array_filter(ArrayHelper::map($advertisementhome, 'advertisement_id', 'advertisement_id'))
            );

            if (!empty($deletedAdvertisementHomeIDs)) {
                Advertisement::deleteAll([
                    'and',
                    ['advertisement_id' => $deletedAdvertisementHomeIDs],
                    ['type' => 'homepage']
                ]);
            }

            if ($model->save()) {

                // Product Advertisements
                foreach ($advertisement as $index => $document) {

                    $document->type = 'product';

                    $uploadedImage = UploadedFile::getInstanceByName(
                        "AdvertisementProduct[{$index}][image]"
                    );

                    if ($uploadedImage) {
                        $document->image = $uploadedImage;
                        $document->upload();
                    } else {
                        $document->image = $document->getOldAttribute('image');
                    }

                    // skip new empty row
                    if ($document->isNewRecord && empty($document->image)) {
                        continue;
                    }

                    $document->save(false);
                }


                // Homepage Advertisements
                foreach ($advertisementhome as $indexhome => $documenthome) {

                    $documenthome->type = 'homepage';

                    $uploadedImage = UploadedFile::getInstanceByName(
                        "AdvertisementHome[{$indexhome}][image]"
                    );

                    if ($uploadedImage) {
                        $documenthome->image = $uploadedImage;
                        $documenthome->upload();
                    } else {
                        $documenthome->image = $documenthome->getOldAttribute('image');
                    }

                    // skip new empty row
                    if ($documenthome->isNewRecord && empty($documenthome->image)) {
                        continue;
                    }

                    $documenthome->save(false);
                }

                Yii::$app->session->setFlash('success', "Updated successfully.");
                // return $this->render('update', [
                //     'model' => $model,
                // ]);
                return $this->redirect('update');
            }
        }
        return $this->render('update', [
            'model' => $model,
            'advertisement' => (empty($advertisement)) ? [new Advertisement] : $advertisement,
            'advertisementhome' => (empty($advertisementhome)) ? [new Advertisement] : $advertisementhome,
        ]);
    }

    /**
     * Deletes an existing Generalsetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Generalsetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Generalsetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Generalsetting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
