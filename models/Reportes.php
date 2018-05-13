<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reportes".
 *
 * @property int $id
 * @property int $reporta_id
 * @property int $reportado_id
 * @property string $mensaje
 *
 * @property Usuarios $reporta
 * @property Usuarios $reportado
 */
class Reportes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reportes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reportado_id', 'mensaje'], 'required'],
            [['reporta_id', 'reportado_id'], 'default', 'value' => null],
            [['reporta_id', 'reportado_id'], 'integer'],
            [['mensaje'], 'filter', 'filter' => 'trim'],
            [['mensaje'], function ($attribute, $params) {
                if (mb_strlen(trim($this->$attribute)) < 20) {
                    $this->addError($attribute, Yii::t('app', 'El mensaje debe contener al menos 20 carácteres'));
                }
            }],
            [['mensaje'], 'string', 'max' => 255],
            [['reporta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['reporta_id' => 'id']],
            [['reportado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['reportado_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reporta_id' => 'Reporta ID',
            'reportado_id' => 'Reportado ID',
            'mensaje' => 'Mensaje',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporta()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'reporta_id'])->inverseOf('reportesReporta');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportado()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'reportado_id'])->inverseOf('reportesReportado');
    }
}
