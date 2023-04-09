<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

echo "<table>";
if (isset($_SESSION) && $user['Admin'] == 1) {
    echo "<tr>
            <td> id и почта пользователя </td>
            <td> действие </td>
            </tr>";
    foreach ($model as $k => $item) {
        echo "<tr>";
        $form = ActiveForm::begin() ?>
        <?= "<td>" . $form->field($delete, 'id')->hiddenInput(['value' => $item['id']]
        )->label(false) . $item['id'] . ":  " . $item['email'] . "</td>" ?>
        <?= "<td>" . Html::submitButton(
            'Удалить аккаунт',
            ['class' => 'inline-block bg-orange hover:bg-opacity-70 focus:outline-none text-black font-bold py-12 px-2 rounded']
        ) . "</td>" ?>
        <?php
        ActiveForm::end();
        echo "</tr>";
    }
} else {
    echo "<tr>
            <td> id и почта пользователя </td>
            </tr>";
    foreach ($model as $key => $value) {
        echo "<tr>";
        echo "<td>" . $value['id'] . ':   ' . $value['email'] . '</td>';
        echo "</tr>";
    }
}

echo "</table>";
