<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $usuario
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $token_val
 * @property string $created_at
 * @property string $updated_at
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * Variable en la que se guarda la repetición de la contraseña
     * a la hora de registrar a un usario.
     * @var string
     */
    public $repeatPassword;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario', 'email', 'password', 'repeatPassword'], 'required'],
            [['usuario'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 255],
            [['usuario'], 'unique'],
            [
                'repeatPassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Las contraseñas deben coincidir.',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['repeatPassword']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'repeatPassword' => 'Repite la contraseña',
        ];
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosDatos()
    {
        return $this->hasOne(UsuariosDatos::className(), ['id_usuario' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioId()
    {
        return $this->hasOne(UsuariosId::className(), ['id' => 'id'])->inverseOf('usuario');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
                do {
                    $val = Yii::$app->security->generateRandomString();
                } while (self::findOne(['token_val' => $val]) !== null);
                $this->token_val = $val;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $datos = new UsuariosDatos();
            $datos->id_usuario = $this->id;
            $datos->save();
        }
        return true;
    }
}
