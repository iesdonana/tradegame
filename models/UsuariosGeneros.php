<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios_generos".
 *
 * @property int $id
 * @property string $sexo
 *
 * @property UsuariosDatos[] $usuariosDatos
 */
class UsuariosGeneros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_generos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sexo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sexo' => 'Sexo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosDatos()
    {
        return $this->hasMany(UsuariosDatos::className(), ['genero_id' => 'id'])->inverseOf('genero');
    }
}
