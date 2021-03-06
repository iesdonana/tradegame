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
     * Escenario de modificación del Usuario.
     * @var string
     */
    const ESCENARIO_UPDATE = 'Modificar';

    /**
     * Escenario de creación del Usuario.
     * @var string
     */
    const ESCENARIO_CREATE = 'Registrar';

    /**
     * Escenario de recuperación de contraseña
     * @var string
     */
    const ESCENARIO_RECUPERACION = 'Recuperar';
    /**
     * Escenario de creación de usuario por Google
     * @var string
     */
    const ESCENARIO_GOOGLE = 'Google';

    /**
     * Variable en la que se guarda la repetición de la contraseña
     * a la hora de registrar a un usario.
     * @var string
     */
    public $repeatPassword;

    /**
     * Variable en la que se guarda la contraseña actual (para comprobar
     * cuando vayamos a actualizar la contraseña).
     * @var string
     */
    public $oldPassword;

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
            [['usuario', 'email'], 'required'],
            [['password', 'repeatPassword'], 'required', 'on' => [self::ESCENARIO_CREATE, self::ESCENARIO_RECUPERACION]],
            [['oldPassword'], 'required', 'when' => function ($model) {
                return Yii::$app->request->get('seccion') === 'password';
            }],
            [['usuario'], 'string', 'max' => 20],
            [
                ['usuario'],
                'match',
                'pattern' => '/^[a-zA-Z0-9]+([_\-\.]?[a-zA-Z0-9])*$/',
                'message' => 'El usuario sólo puede contener los siguientes carácteres (A-z 0-9 -_.).',
            ],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 255],
            [['usuario', 'email'], 'unique'],
            [
                'repeatPassword',
                'compare',
                'compareAttribute' => 'password',
                'skipOnEmpty' => false,
                'on' => [self::ESCENARIO_UPDATE, self::ESCENARIO_CREATE, self::ESCENARIO_RECUPERACION],
                'message' => 'Las contraseñas deben coincidir.',
            ],
            [['oldPassword'], function ($attribute, $params, $validator) {
                if (!Yii::$app->security->validatePassword($this->oldPassword, $this->oldAttributes['password'])) {
                    $this->addError($attribute, 'La contraseña no coincide con tu contraseña actual');
                }
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            ['repeatPassword', 'oldPassword']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => Yii::t('app', 'Usuario'),
            'email' => Yii::t('app', 'Correo electrónico'),
            'password' => Yii::t('app', 'Contraseña'),
            'repeatPassword' => Yii::t('app', 'Repite la contraseña'),
            'oldPassword' => Yii::t('app', 'Contraseña actual')
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuegosUsuarios()
    {
        return $this->hasMany(VideojuegosUsuarios::className(), ['usuario_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValoraciones()
    {
        return $this->hasMany(Valoraciones::className(), ['usuario_valorado_id' => 'id'])->inverseOf('usuarioValorado')
            ->where(['is not', 'num_estrellas', null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValoraciones0()
    {
        return $this->hasMany(Valoraciones::className(), ['usuario_valora_id' => 'id'])->inverseOf('usuarioValora');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMensajes()
    {
        return $this->hasMany(Mensajes::className(), ['emisor_id' => 'id'])->inverseOf('emisor');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMensajes0()
    {
        return $this->hasMany(Mensajes::className(), ['receptor_id' => 'id'])->inverseOf('receptor');
    }

    /**
     * Devuelve los $numero útlimos videojuegos que el usuario ha publicado
     * para intercambiar.
     * @param  int   $numero Número de videojuegos del usuario a retornar
     * @return array         Un array de modelos de VideojuegosUsuarios
     */
    public function getUltimosVideojuegos($numero)
    {
        return $this->getVideojuegosUsuarios()
            ->where(['visible' => true])
            ->andWhere(['borrado' => false])
            ->limit($numero)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::className(), ['id' => 'rol_id'])->inverseOf('usuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportesReporta()
    {
        return $this->hasMany(Reportes::className(), ['reporta_id' => 'id'])->inverseOf('reporta');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportesReportado()
    {
        return $this->hasMany(Reportes::className(), ['reportado_id' => 'id'])->inverseOf('reportado');
    }

    public function getNoLeidos()
    {
        return Mensajes::find()
            ->where(['receptor_id' => Yii::$app->user->id])
            ->andWhere(['emisor_id' => $this->id])
            ->andWhere(['leido' => false])
            ->count();
    }

    /**
     * Comprueba si el usuario es Administrador.
     * @return bool true si es administrador, false si no lo es
     */
    public function esAdmin()
    {
        return $this->rol->tipo === 'Administrador';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->rol_id = 2;
                if ($this->password !== null) {
                    $this->auth_key = Yii::$app->security->generateRandomString();
                }
                if ($this->scenario === self::ESCENARIO_CREATE) {
                    $this->password = Yii::$app->security->generatePasswordHash($this->password);
                    do {
                        $val = Yii::$app->security->generateRandomString();
                    } while (self::findOne(['token_val' => $val]) !== null);
                    $this->token_val = $val;
                }
            } else {
                if ($this->scenario === self::ESCENARIO_UPDATE || $this->scenario === self::ESCENARIO_RECUPERACION) {
                    if ($this->password === '' || $this->password === null) {
                        $this->password = $this->getOldAttribute('password');
                    } else {
                        $this->password = Yii::$app->security->generatePasswordHash($this->password);
                    }
                }
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
