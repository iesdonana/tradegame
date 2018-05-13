<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\Valoraciones;
use app\models\ValoracionesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use yii\helpers\Html;

/**
 * ValoracionesController implements the CRUD actions for Valoraciones model.
 */
class ValoracionesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['valorar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['valorar'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Muestra las valoraciones con un estado concreto.
     * @param null|mixed $estado  Puede ser 'valoradas', 'pendientes' o null
     * @return mixed
     */
    public function actionIndex($estado = null)
    {
        // Evitamos que el usuario pase por parámetro cualquier cosa
        $validos = ['valoradas', 'pendientes', null];
        if (!in_array($estado, $validos)) {
            return $this->goHome();
        }
        $searchModel = new ValoracionesSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $estado
        );

        return $this->render('listado', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Crea una valoración
     * @param mixed $id Id de la valoración
     * @return mixed
     */
    public function actionValorar($id)
    {
        $model = $this->findModel($id);
        // Si ya se ha valorado, mandamos al usuario al inicio
        if ($model->num_estrellas !== null) {
            return $this->goHome();
        }
        $model->scenario = Valoraciones::ESCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Has valorado la oferta correctamente'));
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Muestra una lista con todas las valoraciones que ha recibido un usuario en concreto.
     * @param  string $usuario Nombre de usuario del cuál queremos ver las valoraciones
     * @return mixed
     */
    public function actionListadoNotas($usuario)
    {
        if (($user = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException(Yii::t('app', "No se ha encontrado el usuario '{username}'", [
                'username' => Html::encode($usuario)
            ]));
        }

        if (Valoraciones::find()->where(['usuario_valorado_id' => $user->id])->count() === 0) {
            return $this->goHome();
        }

        return $this->render('listado_notas', [
            'model' => $user,
        ]);
    }

    /**
     * Busca un modelo de Valoraciones mediante AJAX.
     * @param  int     $id Id de la valoración
     * @return object      Modelo de Valoraciones en formato JSON
     */
    public function actionBuscar($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        return $model;
    }

    /**
     * Busca un modelo de Valoraciones
     * @param int $id
     * @return Valoraciones El modelo encontrado
     * @throws NotFoundHttpException Si el modelo no se encuentra
     */
    protected function findModel($id)
    {
        if (($model = Valoraciones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'La página solicitada no existe.'));
    }
}
