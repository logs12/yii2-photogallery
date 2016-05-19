<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\photogallery\models\AlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Albums';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="album-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Album', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => '-',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            [
                'header' => 'Обложка альбома',
                'format' => 'raw',
                'value' => function($model){
                   if ($model->photoThumbnail->thumbnail)
                        return Html::img(yii\helpers\Url::toRoute($model->photoThumbnail->thumbnail),[
                                'alt'=>'yii2 - картинка в gridview',
                        ]);
                    else return null;
                },
            ],
            'name_photographer',
            'email:email',
            'phone',
            [
                'header' => 'Количество фотографий',
                'value' => function ($model) {
                    return $model->getPhotos()->count();
                }
            ],
            [
                'header' => 'Время добавления последней фотографии',
                'format' =>  ['date', 'HH:mm:ss dd.MM.Y'],
                'value' => function ($model) {
                    if ($model->getPhotos()->count() > 0) {
                        return $model->lastCreateDatePhoto->created_at;
                    } else return null;
                }
            ],
            'created_at:date',
            'updated_at:date',
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete} {viewPhotos} {addPhoto}',
                    'buttons' => [
                            'viewPhotos' => function ($url,$model,$key) {
                                return Html::a('Просмотр фотографий',
                                        \yii\helpers\Url::to(['photo/view-photos','parent_id' =>$model->id]));
                            },
                            'addPhoto' => function ($url,$model,$key) {
                                return Html::a('Добавить фотографию',
                                        \yii\helpers\Url::to(['photo/create','id'=>$model->id]));
                            },
                    ],
            ]
        ],
    ]); ?>
</div>
