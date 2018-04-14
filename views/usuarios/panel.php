<?php
use yii\helpers\Html;
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default panel-trade">
    <li>
        <?= Html::a('Datos de la cuenta', [
            'usuarios/modificar',
            'seccion' => 'datos'
        ], ['data-seccion' => 'datos']) ?>
    </li>
    <li>
        <?= Html::a('Datos personales', [
            'usuarios/modificar',
            'seccion' => 'personal'
        ], ['data-seccion' => 'personal']) ?>
    </li>
    <?php if ($model->password !== null): ?>
        <li>
            <?= Html::a('Cambiar contraseña', [
                'usuarios/modificar',
                'seccion' => 'password'
            ], ['data-seccion' => 'password']) ?>
        </li>
    <?php endif; ?>

</ul>
