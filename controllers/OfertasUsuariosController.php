<?php

namespace app\controllers;

use app\models\OfertasUsuarios;
use app\models\OfertasUsuariosSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\AccessControl;

/**
 * OfertasUsuariosController implements the CRUD actions for OfertasUsuarios model.
 */
class OfertasUsuariosController extends Controller
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
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lista las ofertas que ha recibido el usuario logueado
     * @param null|mixed $estado El estado en el cuál se encuentra la oferta
     *                           Puede ser: 'pendientes', 'aceptadas', 'rechazadas' o null.
     * @return mixed
     */
    public function actionIndex($estado = null)
    {
        $query = Yii::$app->request->queryParams;

        // Evitamos que el usuario pase por parámetro cualquier cosa
        $validos = ['pendientes', 'aceptadas', 'rechazadas', null];
        if (!in_array($estado, $validos)) {
            return $this->goHome();
        }
        $searchModel = new OfertasUsuariosSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            Yii::$app->user->identity->usuario,
            $estado
        );

        return $this->render('listado', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
