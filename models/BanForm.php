<?php

namespace app\models;

use yii\base\Model;

class BanForm extends Model
{
    public $fecha;
    public $usuario;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['fecha', 'required'],
            [['usuario'], 'safe'],
        ];
    }
}
