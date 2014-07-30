<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\MtsSessions $model
 * @var ActiveForm $form
 */
?>
<div class="mts-sms-generate form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phpsessid')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'token_name')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'token_value')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'captcha')->textInput(['autofocus'=>'autofocus']) ?>
    <?= Html::img($model->imageUrl) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
