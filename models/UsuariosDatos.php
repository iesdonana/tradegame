<?php

namespace app\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "usuarios_datos".
 *
 * @property int $id_usuario
 * @property string $nombre_real
 * @property string $localidad
 * @property string $biografia
 * @property string $telefono
 * @property int $genero_id
 * @property string $provincia
 * @property string $fecha_nacimiento
 *
 * @property Usuarios $usuario
 */
class UsuariosDatos extends \yii\db\ActiveRecord
{
    /**
     * Contiene la foto del usuario subida en el formulario.
     * @var UploadedFile
     */
    public $foto;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_datos';
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['foto']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario'], 'required'],
            [['id_usuario', 'geoloc', 'direccion', 'localidad', 'provincia'], 'default', 'value' => null],
            [['id_usuario', 'genero_id'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['nombre_real', 'localidad', 'biografia', 'provincia'], 'string', 'max' => 255],
            [['telefono'], 'string', 'max' => 9],
            [['id_usuario'], 'unique'],
            [['foto'], 'file', 'extensions' => 'jpg, png'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['id_usuario' => 'id']],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsuariosGeneros::className(), 'targetAttribute' => ['genero_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'nombre_real' => 'Nombre Real',
            'localidad' => 'Localidad',
            'biografia' => 'Biografia',
            'foto' => 'Avatar',
            'fecha_nacimiento' => 'Fecha Nacimiento',
        ];
    }

    /**
     * Devuelve el avatar del usuario desde Amazon S3 si lo tiene. Si no
     * existe ningún avatar del usuario en S3, devolverá la ruta hacia el
     * avatar por defecto.
     * @return string Ruta del avatar
     */
    public function getAvatar()
    {
        $s3 = Yii::$app->get('s3');
        $id = $this->usuario->id;
        $avatares = Yii::getAlias('@avatares/');

        $archivos = glob($avatares . "$id.*");
        if (count($archivos) > 0) {
            return '/' . $archivos[0];
        }

        $ruta = $avatares . $id . '.jpg';
        if (!$s3->exist($ruta)) {
            $ruta = $avatares . $id . '.png';
        }

        if ($s3->exist($ruta)) {
            $archivo = $avatares . $id . '.' . pathinfo($ruta, PATHINFO_EXTENSION);
            $s3->commands()->get($ruta)
                ->saveAs($archivo)
                ->execute();
            return "/$ruta";
        }

        return "/{$avatares}default.png";
    }

    /**
     * Sube una foto/avatar al directorio de avatares de la aplicación.
     * @return mixed
     */
    public function upload()
    {
        if ($this->foto === null) {
            return true;
        }
        $this->borrarAnteriores();
        $extension = $this->foto->extension;
        $nombreFichero = $this->id_usuario . '.' . $extension;
        $ruta = Yii::getAlias('@avatares/') . $nombreFichero;

        $res = $this->foto->saveAs($ruta);
        if ($res) {
            Image::crop($ruta, 300, 300);
        }

        $s3 = Yii::$app->get('s3');
        try {
            $s3->upload($ruta, $ruta);
        } catch (\Exception $e) {
            unlink($ruta);
            return false;
        }
        return $res;
    }

    /**
     * Borra el avatar anterior de un usuario, si ya existiese alguno, tando
     * de la carpeta uploads, como de Amazon S3.
     */
    public function borrarAnteriores()
    {
        $id = $this->id_usuario;
        $ficheros = glob(Yii::getAlias('@avatares/') . $id . '.*');
        foreach ($ficheros as $fichero) {
            return unlink($fichero);
        }
        $s3 = Yii::$app->get('s3');

        $ruta = Yii::getAlias('@avatares/') . $id . '.jpg';
        if ($s3->exist($ruta)) {
            $s3->delete($ruta);
        } else {
            $ruta = Yii::getAlias('@avatares/') . $id . '.png';
            $s3->delete($ruta);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'id_usuario'])->inverseOf('usuariosDatos');
    }

    public function getLat()
    {
        return explode(',', $this->geoloc)[0];
    }

    public function getLng()
    {
        return explode(',', $this->geoloc)[1];
    }
}
