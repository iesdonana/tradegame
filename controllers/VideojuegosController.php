<?php

namespace app\controllers;

use app\helpers\Utiles;
use app\models\DesarrolladoresVideojuegos;
use app\models\GenerosVideojuegos;
use app\models\Plataformas;
use app\models\Videojuegos;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                'only' => ['update', 'create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'create'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest || !Yii::$app->user->identity->esAdmin()) {
                                return false;
                            }

                            return true;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Hace una búsqueda de videojuegos por el nombre.
     * @param  string $q Búsqueda
     * @return string    Respuesta en JSON
     */
    public function actionBuscarVideojuegos($q = null)
    {
        if (!Yii::$app->request->isAjax) {
            $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuegos['results'] = [];
        if ($q !== null && $q !== '') {
            $videojuegos['results'] = Videojuegos::find()
                ->select(['videojuegos.id', 'videojuegos.nombre',
                'plataformas.nombre as plataforma', 'plataforma_id', ])
                ->joinWith('plataforma')
                ->where(['ilike', 'videojuegos.nombre', $q])
                ->limit(10)
                ->orderBy('videojuegos.nombre')
                ->asArray()->all();
        }
        return $videojuegos;
    }

    /**
     * Busca un videojuego a través del input del NavBar.
     * @param null|mixed $q Búsqueda
     * @return string       Respuesta en JSON
     */
    public function actionBuscadorVideojuegos($q = '')
    {
        $res = [];

        $videojuegos = Videojuegos::find()
            ->with('plataforma')
            ->where(['ilike', 'videojuegos.nombre', $q])
            ->orderBy('videojuegos.nombre');

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $videojuegos = $videojuegos->limit(10)->all();
            foreach ($videojuegos as $videojuego) {
                $res[] = [
                    'id' => $videojuego->id,
                    'nombre' => $videojuego->nombre,
                    'plataforma' => $videojuego->plataforma->nombre,
                ];
            }
        } else {
            $resultadosTotales = $videojuegos->count();
            $videojuegos = $videojuegos->limit(5);
            $dataProvider = new ActiveDataProvider([
                'query' => $videojuegos,
                'pagination' => false,
            ]);

            $res = $this->render('busqueda', [
                'dataProvider' => $dataProvider,
                'plataformas' => Plataformas::find()->orderBy('nombre')->all(),
                'generos' => GenerosVideojuegos::find()->orderBy('nombre')->all(),
                'desarrolladores' => DesarrolladoresVideojuegos::find()->orderBy('compania')->all(),
                'resultadosTotales' => $resultadosTotales,
            ]);
        }

        return $res;
    }

    /**
     * Renderiza mediante Ajax una vista con un listado de los videojuegos
     * filtrados a través de los distintos datos pasados por parámetros.
     * Los datos se pasarán separados por coma en un string. Ej: "1,20,33,12".
     * @param  string $q               Búsqueda del título del videojuego
     * @param  string $plataformas     Ids de las plataformas
     * @param  string $generos         Ids de los géneros de los videojuegos
     * @param  string $desarrolladores Ids de los desarrolladores
     * @param  string $salto           Valor del offset de la consulta
     * @return mixed
     */
    public function actionVistaBusqueda(
        $q = '',
        $plataformas = '',
        $generos = '',
        $desarrolladores = '',
        $salto = 0
    ) {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        $res = [];

        $videojuegos = Videojuegos::find()
            ->with('plataforma')
            ->where(['ilike', 'videojuegos.nombre', $q])
            ->offset($salto)
            ->orderBy('videojuegos.nombre')
            ->limit(5);

        if ($plataformas !== '') {
            $videojuegos = $videojuegos->andWhere(Utiles::filtroAvanzado('plataforma_id', $plataformas));
        }
        if ($desarrolladores !== '') {
            $videojuegos = $videojuegos->andWhere(Utiles::filtroAvanzado('desarrollador_id', $desarrolladores));
        }
        if ($generos !== '') {
            $videojuegos = $videojuegos->andWhere(Utiles::filtroAvanzado('genero_id', $generos));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $videojuegos,
            'pagination' => false,
        ]);

        return $this->renderAjax('listado_busqueda', [
            'dataProvider' => $dataProvider,
            'resultadosTotales' => $videojuegos->count(),
        ]);
    }

    /**
     * Busca un videojuego y devuelve su carátula y su título, para poder
     * ver una previsualización del mismo a la hora de hacer una oferta.
     * @param  int   $id Id del videojuego
     * @return mixed
     */
    public function actionOfertaVideojuego($id)
    {
        if (($videojuegoUsuario = VideojuegosUsuarios::find()
                ->where(['id' => $id])
                ->andWhere(['borrado' => false])
                ->andWhere(['visible' => true])->one()) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se encontró el videojuego'));
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuego = $videojuegoUsuario->videojuego;
        $datos = [
            'titulo' => $videojuego->nombre,
            'caratula' => $videojuego->caratula,
        ];

        return $datos;
    }

    /**
     * Devuelve una vista con los detalles de un videojuego concreto.
     * @param  int   $id Id del videojuego
     * @return mixed
     */
    public function actionDetalles($id)
    {
        if (($videojuego = Videojuegos::findOne($id)) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se encontró el videojuego'));
        }

        return $this->renderAjax('detalles', [
            'videojuego' => $videojuego,
        ]);
    }

    /**
     * Renderiza una vista en la que vemos todos los detalles de un videojuego.
     * @param  int   $id Id de un videojuego
     * @return mixed
     */
    public function actionVer($id)
    {
        if (($videojuego = Videojuegos::findOne($id)) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se encontró el videojuego'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $videojuego
                ->getVideojuegosUsuarios()
                ->where(['borrado' => false])
                ->andWhere(['visible' => true])
                ->orderBy('created_at DESC'),
        ]);

        return $this->render('view', [
            'model' => $videojuego,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Modifica o crea un modelo de Videojuegos.
     * @param int $id Id del videojuego
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        if ($id !== null) {
            $model = $this->findModel($id);
        } else {
            $model = new Videojuegos();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->foto = UploadedFile::getInstance($model, 'foto');
            if ($model->save() && $model->upload()) {
                $msg = ($id === null) ? Yii::t('app', 'creado') : Yii::t('app', 'modificado');
                Yii::$app->session->setFlash('success', Yii::t('app', 'Has {msg} el videojuego correctamente', [
                    'msg' => $msg,
                ]));
                return $this->redirect(['ver', 'id' => $model->id]);
            }
        }

        $generos = GenerosVideojuegos::find()
            ->select('nombre')
            ->indexBy('id')
            ->column();
        $plataformas = Plataformas::find()
            ->select('nombre')
            ->indexBy('id')
            ->column();
        $desarrolla = DesarrolladoresVideojuegos::find()
            ->select('compania')
            ->indexBy('id')
            ->column();

        return $this->render('update', [
            'model' => $model,
            'generos' => Utiles::translateArray($generos),
            'plataformas' => $plataformas,
            'desarrolla' => $desarrolla,
        ]);
    }

    /**
     * Borra un videojuego
     * Si el videojuego se ha podido borrar correctamente, se mandará al usuario a la Home.
     * @param  int $id Id del videojuego a eliminar
     * @return mixed
     */
    public function actionRemove($id)
    {
        $model = $this->findModel($id);

        if (VideojuegosUsuarios::findOne(['videojuego_id' => $model->id]) !== null) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'El videojuego no se puede borrar, ya que se ha publicado alguna vez'));
            return $this->redirect(['ver', 'id' => $id]);
        }

        if (Yii::$app->request->isPost) {
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Has eliminado el videojuego correctamente'));
            return $this->goHome();
        }
    }

    /**
     * Busca un modelo de Videojuegos.
     * @param int $id
     * @return Videojuegos El modelo encontrado
     * @throws NotFoundHttpException Si el modelo no se puede encontrar
     */
    protected function findModel($id)
    {
        if (($model = Videojuegos::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
