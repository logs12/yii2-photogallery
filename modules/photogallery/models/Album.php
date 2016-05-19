<?php

namespace app\modules\photogallery\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%album}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $name_photographer
 * @property string $email
 * @property integer $phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $thumbnail
 *
 * @property Photo[] $photos
 */
class Album extends \yii\db\ActiveRecord
{
    public $thumbnail;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%album}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'name_photographer'], 'required'],
            [['name', 'name_photographer'], 'string', 'max'=> 50],
            [['description'], 'string', 'max'=> 200],
            ['phone', 'app\validators\PhoneValidator'],
            [['created_at', 'updated_at'], 'integer'],
            ['email', 'email'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название альбома',
            'description' => 'Описание альбома',
            'name_photographer' => 'Имя фотографа',
            'email' => 'Email',
            'phone' => 'Phone',
            'created_at' => 'Дата создания альбома',
            'updated_at' => 'Дата изменения информации альбома'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['parent_id' => 'id']);
    }

    public function getLastCreateDatePhoto()
    {
        return $this->find()
                ->select('p.created_at')
                ->joinWith(['photos p'],true,'RIGHT JOIN')
                ->where(['album.id'=>$this->id])
                ->orderBy('p.created_at DESC')->one();
    }

    public function getPhotoThumbnail()
    {
        //$preview = $this->find()->select('p.file')->joinWith(['photos p'],true,'RIGHT JOIN')->orderBy('p.created_at DESC')->one();
        return $this->find()
                ->select('p.thumbnail')
                ->joinWith(['photos p'],true,'RIGHT JOIN')
                ->where(['album.id'=>$this->id])
                ->orderBy('p.created_at DESC')
                ->one();
    }
}
