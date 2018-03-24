<?php

namespace app\models;

/**
 * This is the model class for table "top_valoraciones".
 *
 * @property string $usuario
 * @property string $avg
 */
class TopValoraciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'top_valoraciones';
    }

    public static function primaryKey()
    {
        return ['usuario'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['avg'], 'number'],
            [['usuario'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario' => 'Usuario',
            'avg' => 'Avg',
        ];
    }
}
