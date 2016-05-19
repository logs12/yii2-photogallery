<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\photogallery\models\Photo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="photo-form">

    <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php
       if (!Yii::$app->request->get('id')) {
            echo $form->field($model, 'parent_id')
                ->label('Альбом')
                ->dropDownList(ArrayHelper::map(app\modules\photogallery\models\Album::find()
                        ->orderBy('name')
                        ->all(), 'id', 'name'), ['prompt'=>'']);
       } else
           echo $form->field($model, 'parent_id')
                   ->label(false)
                   ->hiddenInput(['value' => Yii::$app->request->get('id')]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
