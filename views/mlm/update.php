<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MlmUser */

$this->title = 'Update Mlm User: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mlm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mlm-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
