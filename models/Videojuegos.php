<?php

namespace app\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "videojuegos".
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $fecha_lanzamiento
 * @property int $desarrollador_id
 * @property int $genero_id
 * @property int $plataforma_id
 *
 * @property DesarrolladoresVideojuegos $desarrollador
 * @property GenerosVideojuegos $genero
 * @property Plataformas $plataforma
 */
class Videojuegos extends \yii\db\ActiveRecord
{
    public $foto;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videojuegos';
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
            [['nombre', 'desarrollador_id', 'genero_id', 'plataforma_id'], 'required'],
            [['descripcion'], 'string'],
            [['fecha_lanzamiento'], 'safe'],
            [['desarrollador_id', 'genero_id', 'plataforma_id'], 'default', 'value' => null],
            [['desarrollador_id', 'genero_id', 'plataforma_id'], 'integer'],
            [['foto'], 'file', 'extensions' => 'jpg, png'],
            [['nombre'], 'string', 'max' => 255],
            [['desarrollador_id'], 'exist', 'skipOnError' => true, 'targetClass' => DesarrolladoresVideojuegos::className(), 'targetAttribute' => ['desarrollador_id' => 'id']],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenerosVideojuegos::className(), 'targetAttribute' => ['genero_id' => 'id']],
            [['plataforma_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plataformas::className(), 'targetAttribute' => ['plataforma_id' => 'id']],
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
            'descripcion' => 'Descripcion',
            'fecha_lanzamiento' => 'Fecha de lanzamiento',
            'desarrollador_id' => 'Desarrollador',
            'genero_id' => 'Género',
            'plataforma_id' => 'Plataforma',
        ];
    }

    /**
     * Devuelve la carátula del videojuego desde Amazon S3 si la tiene. Si no
     * existe ninguna carátula del videojuego en S3, devolverá la ruta hacia la
     * carátula por defecto.
     * @return string Ruta de la carátula
     */
    public function getCaratula()
    {
        $s3 = Yii::$app->get('s3');
        $id = $this->id;
        $caratulas = Yii::getAlias('@caratulas/');
        if ($id === null) {
            return "/{$caratulas}default.png";
        }

        $archivos = glob($caratulas . "$id.*");
        if (count($archivos) > 0) {
            return '/' . $archivos[0];
        }

        $ruta = $caratulas . $id . '.jpg';
        if (!$s3->exist($ruta)) {
            $ruta = $caratulas . $id . '.png';
        }

        if ($s3->exist($ruta)) {
            $archivo = $caratulas . $id . '.' . pathinfo($ruta, PATHINFO_EXTENSION);
            $s3->commands()->get($ruta)
                ->saveAs($archivo)
                ->execute();
            return "/$ruta";
        }
        return "/{$caratulas}default.png";
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
        $nombreFichero = $this->id . '.' . $extension;
        $ruta = Yii::getAlias('@caratulas/') . $nombreFichero;

        $res = $this->foto->saveAs($ruta);
        if ($res) {
            Image::thumbnail($ruta, 300, null)->save();
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

    public function borrarAnteriores()
    {
        $id = $this->id;
        $ficheros = glob(Yii::getAlias('@caratulas/') . $id . '.*');
        foreach ($ficheros as $fichero) {
            return unlink($fichero);
        }
        $s3 = Yii::$app->get('s3');

        $ruta = Yii::getAlias('@caratulas/') . $id . '.jpg';
        if ($s3->exist($ruta)) {
            $s3->delete($ruta);
        } else {
            $ruta = Yii::getAlias('@caratulas/') . $id . '.png';
            $s3->delete($ruta);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesarrollador()
    {
        return $this->hasOne(DesarrolladoresVideojuegos::className(), ['id' => 'desarrollador_id'])->inverseOf('videojuegos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenero()
    {
        return $this->hasOne(GenerosVideojuegos::className(), ['id' => 'genero_id'])->inverseOf('videojuegos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlataforma()
    {
        return $this->hasOne(Plataformas::className(), ['id' => 'plataforma_id'])->inverseOf('videojuegos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuegosUsuarios()
    {
        return $this->hasMany(VideojuegosUsuarios::className(), ['videojuego_id' => 'id']);
    }
}
