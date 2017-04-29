<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">
<script>
 var root_url = "<?=Url::base();?>";
</script>
<?
$this->registerJs('
$(document).ready(function()
{
$("#w0").on("beforeSubmit",function(e){
	  e.preventDefault();
    e.stopImmediatePropagation(); // 
	App.postData(this);
	return false;
});
});
');

?>
    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-success" >Ваша ссылка для восстановления сессии: <a href="<?=$restore_url?>" ><?=$restore_url?></a> </div>
   <?php $form = ActiveForm::begin(
  
   [
   'options'=>['class'=>'form-inline'],
    'action'=>'?r=tasks/create',
	'enableClientScript' => true,
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
   ]
   ); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'Название задачи'])->label(false); ?>

 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            [
			'attribute'=>'name',
			'format'=>'raw',
			'value'=>function($model) {
				 return  Html::textInput ( $model->id.'_name', $model->name, ['id'=>$model->id.'_name'] );
			}
			],
            'create_date',
           [
			'attribute'=>'done',
			'format'=>'raw',
			'value'=>function($model) {
				 return  Html::checkbox ( $model->id.'_done', $model->done == 1 ? true : false, ['id'=>$model->id.'_done'] );
			}
			],
         

            [
			'class' => 'yii\grid\ActionColumn',
			'template'=>'{delete} {save}',
			'buttons'=>[
			'delete'=>function($url, $model, $key)
			{
				return Html::a('Удалить',$url,['class'=>'btn btn-danger','data-confirm'=>'Вы действительно хотите удалить эту запись?']);
			},
			'save'=>function($url, $model, $key)
			{
				return Html::button('Сохранить',['class'=>'btn btn-success','onclick'=>'App.updateRow('.$model->id.')']);;
			}
			 ]
			],
        ],
    ]); ?>
</div>
