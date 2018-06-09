<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\Videojuegos;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosUsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['publicar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['publicar'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Publica un videojuego para ponerlo visible para los demás usuarios y que
     * así nos puedan hacer ofertas por él.
     * @return mixed
     */
    public function actionPublicar()
    {
        $model = new VideojuegosUsuarios();
        $model->usuario_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->fotos = UploadedFile::getInstances($model, 'fotos');
            if ($model->validate()) {
                $model->save();
                if ($model->upload()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Has publicado el videojuego correctamente'));
                    return $this->redirect(['videojuegos-usuarios/ver', 'id' => $model->id]);
                }
            }
        }

        return $this->render('publicar', [
            'model' => $model,
        ]);
    }

    /**
     * Hace una búsqueda de videojuegos publicados de un usuariom por el nombre
     * del videojuego.
     * @param  int $id_usuario    Usuario por el cual vamos a filtrar
     * @param  int $id_videojuego Id del videojuego publicado
     * @param  string $q          Búsqueda del título
     * @return string             Respuesta en JSON
     */
    public function actionBuscarPublicados($id_usuario, $id_videojuego, $q = null)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuegos['results'] = [];
        if ($q !== null && $q !== '') {
            $subQuery = VideojuegosUsuarios::find()
                ->select('videojuego_id')
                ->where(['usuario_id' => $id_usuario])
                ->andWhere(['visible' => true])
                ->andWhere(['borrado' => false])
                ->andWhere(['!=', 'videojuego_id',  $id_videojuego]);

            $videojuegos['results'] = Videojuegos::find()
                ->select(['videojuegos_usuarios.id', 'videojuegos.nombre',
                    'p.nombre as plataforma', 'plataforma_id', ])
                ->joinWith('plataforma as p')
                ->joinWith('videojuegosUsuarios')
                ->where(['ilike', 'videojuegos.nombre', $q])
                ->andWhere(['visible' => true])
                ->andWhere(['borrado' => false])
                ->andWhere(['videojuegos.id' => $subQuery])
                ->andWhere(['usuario_id' => $id_usuario])
                ->limit(10)
                ->orderBy('videojuegos.nombre')
                ->asArray()->all();
        }
        return $videojuegos;
    }

    /**
     * Muestra las publicaciones de un usuario pasado por parámetro.
     * @param  string $usuario Nombre de usuario
     * @param null|mixed $layout Layout en el que vamos a renderizar la vista
     *                           Si es null la renderizaremos en el layout que usa todas las páginas
     * @return mixed
     * @throws NotFoundHttpException Si no se ha encontrado el usuario
     */
    public function actionPublicaciones($usuario, $layout = null)
    {
        if (($model = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException(Yii::t('app', "No se ha encontrado el usuario '{username}'", [
                'username' => Html::encode($usuario),
            ]));
        }

        $query = VideojuegosUsuarios::find()
            ->with('videojuego')
            ->where(['usuario_id' => $model->id])
            ->andWhere(['visible' => true])
            ->andWhere(['borrado' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if ($layout !== null) {
            $this->layout = $layout;
            return $this->render('listado_ventana', [
                'listado' => $query->all(),
            ]);
        }
        return $this->render('publicaciones', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un listado con todas las publicaciones que se han realizado en la aplicación
     * @return mixed
     */
    public function actionAllPublicaciones()
    {
        $query = VideojuegosUsuarios::find()
            ->with('videojuego')
            ->andWhere(['visible' => true])
            ->andWhere(['borrado' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('publicaciones', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Hace un soft-delete de un VideojuegoUsuario.
     * @return mixed
     */
    public function actionRemove()
    {
        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se ha podido borrar la publicación'));
        }

        if (($publicacion = VideojuegosUsuarios::findOne($id)) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No existe la publicación'));
        }

        $publicacion->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Se ha borrado la publicación correctamente'));
        return $this->redirect(['videojuegos-usuarios/publicaciones', 'usuario' => Yii::$app->user->identity->usuario]);
    }

    /**
     * Renderiza una vista en la que vemos todos los detalles de una publicación
     * de un videojuego del usuario.
     * @param  int   $id Id del videojuego_usuario
     * @return mixed
     */
    public function actionVer($id)
    {
        if (($videojuego = VideojuegosUsuarios::findOne($id)) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se encontró la publicación'));
        }

        if ($videojuego->borrado) {
            throw new NotFoundHttpException(Yii::t('app', 'Esta publicación ha sido eliminada'));
        }
        return $this->render('view', [
            'model' => $videojuego,
        ]);
    }
}
