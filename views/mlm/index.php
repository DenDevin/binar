<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchMlmUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mlm Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mlm-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Mlm User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>








        <div class="row" style="height: 100px;">

        </div>

        <div class="row" id="sidebar">
            <div class="col-md-12">
                <div class="well">
                    <div>
                        <ul class="nav">
                            <li>
                                <?php if(count($roots) > 0) : ?>
                                <label class="tree-toggle nav-header" label-default="">Корень</label>
                                <ul>
                                    <?php foreach($roots as $root) : ?>
                                        <li><span class="badge"><?= $root->id; ?></span>
                                     <? $binar->getTree($root->id); ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>



    <?php Pjax::end(); ?>
<?php

$script = <<< JS
    $('.tree-toggle').click(function () {
	$(this).parent().children('ul.tree').toggle(200);
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);



?>
</div>
