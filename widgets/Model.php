<?php

namespace app\widgets;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];
        $arrFields = array_keys($model->attributes);

        // Index the existing models by primary field (usually id)
        if (!empty($multipleModels)) {
            $multipleModels = ArrayHelper::index($multipleModels, $arrFields[0]);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (
                    isset($item[$arrFields[0]]) &&
                    !empty($item[$arrFields[0]]) &&
                    isset($multipleModels[$item[$arrFields[0]]])
                ) {
                    // Use existing model
                    $models[] = $multipleModels[$item[$arrFields[0]]];
                } else {
                    // Create new model
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);
        return $models;
    }
}
