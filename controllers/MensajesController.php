<?php

namespace app\controllers;

use app\models\Mensajes;
use app\models\MensajesSearch;
use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MensajesController implements the CRUD actions for Mensajes model.
 */
class MensajesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['listado', 'nuevo'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['listado'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['nuevo'],
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest &&
                            (Yii::$app->user->identity->usuario != Yii::$app->request->get('receptor'))) {
                                return true;
                            }

                            return false;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mensajes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MensajesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mensajes model.
     * @param int $userSelect Id del usuario que vamos a seleccionar en la vista del listado
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionListado($userSelect = null)
    {
        $conversaciones = Mensajes::find()
            ->select('emisor_id')
            ->where(['receptor_id' => Yii::$app->user->id])
            ->distinct()
            ->all();

        $params = [
            'conversaciones' => $conversaciones,
            'model' => new Mensajes(),
        ];

        if ($userSelect !== null) {
            $params = array_merge($params, ['userSelect' => $userSelect]);
        }
        return $this->render('listado', $params);
    }

    /**
     * Devuelve la vista de mensajes de una conversación con un usuario específico,
     * pasado por parámetro. Se buscara la conversación con el usuario logueado.
     * @param  string $usuario Usuario del cuál queremos buscar la conversación
     * @return mixed
     */
    public function actionConversacion($usuario)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (($u = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException('No se ha encontrado el usuario');
        }

        $me = Yii::$app->user->id;
        Yii::$app->db
            ->createCommand('UPDATE mensajes SET leido = true WHERE (emisor_id = :emisor AND receptor_id = :receptor)')
            ->bindValues([
                ':emisor' => $u->id,
                ':receptor' => $me,
            ])->execute();

        $lista = Mensajes::find()
            ->where(['and',
                ['emisor_id' => $u->id],
                ['receptor_id' => $me],
            ])
            ->orWhere([
                'and',
                ['emisor_id' => $me],
                ['receptor_id' => $u->id],
            ])->orderBy('created_at ASC')
            ->all();

        return $this->renderAjax('mensajes', [
            'lista' => $lista,
            'model' => new Mensajes(),
        ]);
    }

    /**
     * Creates a new Mensajes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Mensajes();
        $model->emisor_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $model;
        }

        return $model;
    }

    /**
     * Crea un nuevo modelo de Mensajes, enviado por el usuario logueado.
     * @param  string $receptor Usuario al que se le va a enviar el mensaje
     * @return mixed
     */
    public function actionNuevo($receptor)
    {
        $model = new Mensajes();

        if (($u = Usuarios::findOne(['usuario' => $receptor])) === null) {
            throw new NotFoundHttpException('No se ha encontrado el usuario');
        }

        $model->emisor_id = Yii::$app->user->id;
        $model->receptor_id = $u->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Has enviado el mensaje correctamente');
            return $this->goHome();
        }

        return $this->render('nuevo', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mensajes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mensajes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mensajes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Mensajes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mensajes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
