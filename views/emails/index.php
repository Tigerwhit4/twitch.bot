<?php
/**
 * @var yii\web\View $this
 * @var app\models\Emails $emailModel
 * @var \yii\data\ActiveDataProvider $activeEmails
 */
use yii\bootstrap\ActiveForm;

?>
<h1>Emails</h1>

<p>
    <?php
    $form = ActiveForm::begin(
        [
            'action' => ['create'],
            'id' => 'form-add-emails',
            'layout' => 'inline',
            'beforeSubmit' => "function(form) {
                if($(form).find('.has-error').length) {
                        return false;
                }

                $.ajax({
                        url: form.attr('action'),
                        type: 'post',
                        data: form.serialize(),
                        success: function(data) {
                                alert('Done');
                        },
                        error: function(response) {
                                alert('Error: ' + response.responseJSON.message);
                        }
                });

                return false;
        }",
        ]
    );
    ?>
    <?= $form->field($emailModel, 'email'); ?>
    <?= \yii\helpers\Html::submitButton('Create', ['class'=>'btn btn-success']); ?>
    <?php $form->end(); ?>

    <?= \yii\grid\GridView::widget(
        [
            'dataProvider' => $activeEmails,
        ]
    ) ?>
</p>
