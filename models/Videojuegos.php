<?php

namespace app\models;

use Yii;

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
    public function rules()
    {
        return [
            [['nombre', 'desarrollador_id', 'genero_id', 'plataforma_id'], 'required'],
            [['descripcion'], 'string'],
            [['fecha_lanzamiento'], 'safe'],
            [['desarrollador_id', 'genero_id', 'plataforma_id'], 'default', 'value' => null],
            [['desarrollador_id', 'genero_id', 'plataforma_id'], 'integer'],
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
            'fecha_lanzamiento' => 'Fecha Lanzamiento',
            'desarrollador_id' => 'Desarrollador ID',
            'genero_id' => 'Genero ID',
            'plataforma_id' => 'Plataforma ID',
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
        $rutaJpg = Yii::getAlias('@caratulas_s3/') . $id . '.jpg';
        $rutaPng = Yii::getAlias('@caratulas_s3/') . $id . '.png';

        if ($s3->exist($rutaJpg)) {
            return $s3->getUrl($rutaJpg);
        } elseif ($s3->exist($rutaPng)) {
            return $s3->getUrl($rutaPng);
        }

        return '@web/caratula.png';
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
