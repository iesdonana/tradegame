<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generos_videojuegos".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Videojuegos[] $videojuegos
 */
class GenerosVideojuegos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generos_videojuegos';
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
        return $this->hasMany(Videojuegos::className(), ['genero_id' => 'id'])->inverseOf('genero');
    }
}
