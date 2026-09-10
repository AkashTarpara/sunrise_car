<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AppuserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="appuser-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'appuser_id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'full_name') ?>

    <?= $form->field($model, 'phone_code') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'phone_verify') ?>

    <?php // echo $form->field($model, 'otp') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'organisation') ?>

    <?php // echo $form->field($model, 'email_verify_code') ?>

    <?php // echo $form->field($model, 'email_verify') ?>

    <?php // echo $form->field($model, 'email_verify_token') ?>

    <?php // echo $form->field($model, 'email_token') ?>

    <?php // echo $form->field($model, 'birth_date') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'login_type') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'notification') ?>

    <?php // echo $form->field($model, 'user_status') ?>

    <?php // echo $form->field($model, 'google_id') ?>

    <?php // echo $form->field($model, 'facebook_id') ?>

    <?php // echo $form->field($model, 'instagram_id') ?>

    <?php // echo $form->field($model, 'twitter_id') ?>

    <?php // echo $form->field($model, 'apple_id') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'password_reset_token_time') ?>

    <?php // echo $form->field($model, 'email_verify_time') ?>

    <?php // echo $form->field($model, 'lang_code') ?>

    <?php // echo $form->field($model, 'signup_type') ?>

    <?php // echo $form->field($model, 'status_of_user') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'is_signup') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'site_logo') ?>

    <?php // echo $form->field($model, 'user_type') ?>

    <?php // echo $form->field($model, 'stripe_live_customer_id') ?>

    <?php // echo $form->field($model, 'stripe_test_customer_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
