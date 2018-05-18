<?php

namespace app\models;

use Yii;

use yii\web\NotFoundHttpException;

use yii\imagine\Image;

/**
 * This is the model class for table "videojuegos_usuarios".
 *
 * @property int $id
 * @property int $videojuego_id
 * @property int $usuario_id
 * @property string $mensaje
 * @property string $created_at
 * @property bool $visible
 *
 * @property Usuarios $usuario
 * @property Videojuegos $videojuego
 */
class VideojuegosUsuarios extends \yii\db\ActiveRecord
{
    /**
     * Contiene la fotos de los videojuegos
     * @var UploadedFile[]
     */
    public $fotos;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videojuegos_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['videojuego_id', 'usuario_id'], 'required'],
            [['videojuego_id', 'usuario_id'], 'default', 'value' => null],
            [['visible'], 'default', 'value' => true],
            [['borrado'], 'default', 'value' => false],
            [['videojuego_id', 'usuario_id'], 'integer'],
            [['mensaje'], 'string', 'max' => 255],
            [['fotos'], 'file', 'extensions' => 'jpg, png', 'maxFiles' => 3],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsuariosId::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['videojuego_id'], 'exist', 'skipOnError' => true, 'targetClass' => Videojuegos::className(), 'targetAttribute' => ['videojuego_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'videojuego_id' => Yii::t('app', 'Título del videojuego'),
            'usuario_id' => 'Usuario ID',
            'mensaje' => Yii::t('app', 'Comentarios'),
            'fotos' => Yii::t('app', 'Fotos')
        ];
    }

    /**
     * Soft-delete.
     * @return int Número de filas afectadas
     */
    public function delete()
    {
        self::beforeDelete();
        $this->borrado = true;
        $this->save();
        self::afterDelete();
        return 1;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('videojuegosUsuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuego()
    {
        return $this->hasOne(Videojuegos::className(), ['id' => 'videojuego_id'])->inverseOf('videojuegosUsuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfertasPublicados()
    {
        return $this->hasMany(Ofertas::className(), ['videojuego_publicado_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfertasOfrecidos()
    {
        return $this->hasMany(Ofertas::className(), ['videojuego_ofrecido_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        if (!$this->visible) {
            throw new NotFoundHttpException('Este videojuego ya se ha intercambiado');
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $ofrecidos = $this->getOfertasOfrecidos()->where(['is', 'aceptada', null])->all();
        foreach ($ofrecidos as $v) {
            $v->delete();
        }
        $publicados = $this->getOfertasPublicados()->where(['is', 'aceptada', null])->all();
        foreach ($publicados as $v) {
            $v->delete();
        }

        return true;
    }

    /**
     * Sube una foto al directorio de fotos_videojuegos de la aplicación.
     * @return mixed
     */
    public function upload()
    {
        $fotos = $this->fotos;
        if ($fotos === null) {
            return true;
        }

        $cont = 1;
        foreach ($fotos as $foto) {
            $extension = $foto->extension;
            $nombreFichero = $this->id . '_' . $cont . '.' . $extension;
            $ruta = Yii::getAlias('@fotos_videojuegos/') . $nombreFichero;
            $res = $foto->saveAs($ruta);
            if ($res) {
                Image::thumbnail($ruta, 300, null)
                    ->save($ruta, ['quality' => 80]);
            }
            $s3 = Yii::$app->get('s3');
            try {
                $s3->upload($ruta, $ruta);
                $cont++;
            } catch (\Exception $e) {
                unlink($ruta);
            }
        }

        return $res;
    }
}
