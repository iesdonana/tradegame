<?php

namespace app\controllers;

use Yii;
use app\models\Reportes;
use app\models\Usuarios;
use app\models\ReportesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ReportesController implements the CRUD actions for Reportes model.
 */
class ReportesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user;
                            if (!$user->isGuest &&
                            ($user->identity->usuario != Yii::$app->request->get('usuario'))) {
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
     * Lists all Reportes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reportes model.
     * @param integer $id
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
     * Creates a new Reportes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $usuario
     * @return mixed
     */
    public function actionCreate($usuario)
    {
        $model = new Reportes();
        if (($user = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException('Usuario no encontrado');
        }

        $model->reporta_id = $user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('warning', "Has reportado correctamente a $user->usuario. Tu petición será revisada por los administradores");
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
            'reportado' => $user
        ]);
    }

    /**
     * Deletes an existing Reportes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reportes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reportes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reportes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
