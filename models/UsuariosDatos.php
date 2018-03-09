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
            [['id_usuario'], 'default', 'value' => null],
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
        $rutaS3 = Yii::getAlias('@avatares_s3/') . $nombreFichero;

        $res = $this->foto->saveAs($ruta);
        if ($res) {
            Image::crop($ruta, 300, 300, [5, 5]);
        }

        $s3 = Yii::$app->get('s3');
        try {
            $s3->upload($rutaS3, $ruta);
        } catch (\Exception $e) {
            unlink($ruta);
            return false;
        }
        return $res;
    }

    /**
     * Borra el avatar anterior de un usuario, si ya existiese alguno.
     */
    public function borrarAnteriores()
    {
        $ficheros = glob(Yii::getAlias('@avatares/') . $this->id_usuario . '.*');
        foreach ($ficheros as $fichero) {
            unlink($fichero);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'id_usuario'])->inverseOf('usuariosDatos');
    }
}
