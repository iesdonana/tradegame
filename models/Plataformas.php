<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "plataformas".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Videojuegos[] $videojuegos
 */
class Plataformas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plataformas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuegos()
    {
        return $this->hasMany(Videojuegos::className(), ['plataforma_id' => 'id'])->inverseOf('plataforma');
    }
}
