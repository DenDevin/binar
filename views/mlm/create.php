<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MlmUser */

$this->title = 'Create Mlm User';
$this->params['breadcrumbs'][] = ['label' => 'Mlm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mlm-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
