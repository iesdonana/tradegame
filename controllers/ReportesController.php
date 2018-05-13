<?php

namespace app\controllers;

use Yii;
use app\models\Reportes;
use app\models\Usuarios;
use app\models\ReportesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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
     * Lista los reportes enviados por los usuarios
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
     * Crea un nuevo Reporte.
     * Si se reporta correctamente, se mandará al usuario a la página de inicio.
     * @param string $usuario Nombre de usuario al cuál se va a reportar.
     * @return mixed
     */
    public function actionCreate($usuario)
    {
        $model = new Reportes();
        if (($user = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException('No se ha podido encontrar el usuario.');
        }

        $model->reporta_id = $user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', "Has reportado correctamente a $user->usuario. Tu petición será revisada por los administradores");
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
            'reportado' => $user
        ]);
    }

    /**
     * Elimina un Reporte de la base de datos.
     * Si se reporta correctamente, se mandará al usuario al listado de reportes.
     * @return mixed
     * @throws NotFoundHttpException Si no se puede encontrar el reporte
     */
    public function actionDelete()
    {
        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'No se ha podido completar la solicitud.'));
        }

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Has borrado correctamente el reporte'));
        return $this->redirect(['reportes/index']);
    }

    /**
     * Busca un modelo de Reportes a través de su id.
     * @param int $id Id del Reporte a buscar
     * @return Reportes El modelo del Reporte encontrado
     * @throws NotFoundHttpException Si el modelo no se puede encontrar.
     */
    protected function findModel($id)
    {
        if (($model = Reportes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'No se ha podido completar la solicitud.'));
    }
}
