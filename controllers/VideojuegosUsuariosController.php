<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\Videojuegos;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosUsuariosController extends Controller
{
    public function behaviors()
    {
        return [
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Has publicado el videojuego correctamente');
            return $this->goHome();
        }

        return $this->render('publicar', [
            'model' => $model,
        ]);
    }

    /**
     * Hace una búsqueda de videojuegos publicados de un usuariom por el nombre
     * del videojuego.
     * @param  int $id_usuario    Usuario por el cual vamos a filtrar
     * @param  string $q          Búsqueda
     * @param  int $id_videojuego Id del videojuego publicado
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
                ->andWhere(['!=', 'videojuego_id',  $id_videojuego]);

            $videojuegos['results'] = Videojuegos::find()
                ->select(['videojuegos_usuarios.id', 'videojuegos.nombre',
                    'p.nombre as plataforma', 'plataforma_id', ])
                ->joinWith('plataforma as p')
                ->joinWith('videojuegosUsuarios')
                ->where(['ilike', 'videojuegos.nombre', $q])
                ->andWhere(['visible' => true])
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
     * @return mixed
     * @throws NotFoundHttpException Si no se ha encontrado el usuario
     */
    public function actionPublicaciones($usuario)
    {
        if (($model = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException("No se ha encontrado el usuario '$usuario'");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => VideojuegosUsuarios::find()
                ->with('videojuego')
                ->where(['usuario_id' => $model->id])
                ->andWhere(['visible' => true]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('publicaciones', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
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
            throw new NotFoundHttpException('No se encontró la publicación');
        }

        return $this->render('view', [
            'model' => $videojuego,
        ]);
    }
}
