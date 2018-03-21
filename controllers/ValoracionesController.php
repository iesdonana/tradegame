<?php

namespace app\controllers;

use app\models\Valoraciones;
use app\models\ValoracionesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
     * Lists all Valoraciones models.
     * @return mixed
     * @param null|mixed $estado
     */
    public function actionIndex($estado = null)
    {
        $query = Yii::$app->request->queryParams;

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
     * Displays a single Valoraciones model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Valoraciones model.
     * @return mixed
     * @param mixed $id
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
            Yii::$app->session->setFlash('success', 'Has valorado la oferta correctamente');
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Valoraciones model.
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
     * Finds the Valoraciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Valoraciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Valoraciones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
