<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Password Chenge';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
           <div class="col-md-6 col-sm-6 col-xs-6">
            <?php $form = ActiveForm::begin(['id'=>'changepassword-form',]); ?>
                
                <?= $form->field($model,'oldpass',['inputOptions'=>[
                    'placeholder'=>'Enter old Password'
                ]])->passwordInput() ?>
                
               
                <?= $form->field($model,'newpass',['inputOptions'=>[
                    'placeholder'=>'Enter New Password'
                ]])->passwordInput() ?>
               
                <?= $form->field($model,'repeatnewpass',['inputOptions'=>[
                    'placeholder'=>'Repeat New Password'
                ]])->passwordInput() ?>
               
                <div class="form-group">
                   <?= Html::submitButton('Save',[
                            'class'=>'btn btn-success'
                        ]) ?>
                    <?= Html::a('Cancel',Yii::$app->urlManager->createUrl(['dashboard']), ['class' => 'btn btn-danger']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div> 