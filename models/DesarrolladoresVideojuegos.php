<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "desarrolladores_videojuegos".
 *
 * @property int $id
 * @property string $compania
 *
 * @property Videojuegos[] $videojuegos
 */
class DesarrolladoresVideojuegos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desarrolladores_videojuegos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['compania'], 'required'],
            [['compania'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'compania' => 'Compania',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuegos()
    {
        return $this->hasMany(Videojuegos::className(), ['desarrollador_id' => 'id'])->inverseOf('desarrollador');
    }
}
