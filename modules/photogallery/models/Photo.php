<?php

namespace app\modules\photogallery\models;

use Yii;
use app\behaviors\TimestampPhotoBehavior;

/**
 * This is the model class for table "{{%photo}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $address
 * @property string $file
 * @property string $thumbnail
 * @property string $created_at
 *
 * @property Album $parent
 */
class Photo extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photo}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'class' => TimestampPhotoBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id','created_at'], 'integer'],
            [['title', 'address', ], 'required'],
            [['title'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 200],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Album::className(), 'targetAttribute' => ['parent_id' => 'id']],
            ['file', 'image', 'extensions' => ['jpg','png','gif'],  'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * 20],
            ['thumbnail','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'title' => 'Заголовок фотографии ',
            'address' => 'Адрес фотосъемки ',
            'file' => 'Загружаемый файл ',
            'thumbnail' => 'Миниатюра ',
            'created_at' => 'Время добавления последней фотографии',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Album::className(), ['id' => 'parent_id']);
    }
}
