<?php

namespace app\controllers;

use app\models\OfertasUsuariosSearch;
use Yii;
use yii\web\Response;

use yii\filters\VerbFilter;
use yii\web\Controller;

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
     * @param string $estado El estado en el cuál se encuentra la oferta
     *                       Puede ser: 'pendientes', 'aceptadas', 'rechazadas' o null.
     * @param string $tipo   El tipo de ofertas a ver.
     *                       Puede ser: 'recibidas' o 'enviadas'.
     * @return mixed
     */
    public function actionIndex($estado = 'todas', $tipo = 'recibidas')
    {
        // Evitamos que el usuario pase por parámetro cualquier cosa
        $validos = ['pendientes', 'aceptadas', 'rechazadas', 'todas'];
        if (!in_array($estado, $validos)) {
            return $this->goHome();
        }
        $searchModel = new OfertasUsuariosSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            Yii::$app->user->identity->usuario,
            $estado,
            $tipo
        );

        $params = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->renderAjax('index', $params);
        }
        return $this->render('listado', $params);
    }
}
