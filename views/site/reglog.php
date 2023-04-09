<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin() ?>
<?= $form->field($registration, 'email')->textInput() ?>
<?= $form->field($registration, 'password')->passwordInput() ?>
    <?=Html::submitButton('Создать аккаунт', ['class' => 'inline-block bg-orange hover:bg-opacity-70 focus:outline-none text-black font-bold py-2 px-4 rounded']) ?>
<?php ActiveForm::end() ?>



