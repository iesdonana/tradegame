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
                'only' => ['listado'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['listado'],
                        'roles' => ['@'],
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
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionListado()
    {
        $conversaciones = Mensajes::find()
            ->select('emisor_id')
            ->where(['receptor_id' => Yii::$app->user->id])
            ->distinct()
            ->all();

        return $this->render('listado', [
            'conversaciones' => $conversaciones,
            'model' => new Mensajes(),
        ]);
    }

    public function actionConversacion($usuario)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (($u = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException('No se ha encontrado el usuario');
        }

        $me = Yii::$app->user->id;
        $lista = Mensajes::find()
            ->where(['and',
                ['emisor_id' => $u->id],
                ['receptor_id' => $me],
            ])
            ->orWhere([
                'and',
                ['emisor_id' => $me],
                ['receptor_id' => $u->id],
            ])->all();

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
