<?php

use app\models\BanForm;

use app\helpers\Utiles;

use yii\grid\ActionColumn;

use yii\helpers\Html;

use yii\widgets\ActiveForm;

use yii\bootstrap\Modal;

use kartik\grid\GridView;

use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReportesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            $('#id-reporte').val($(this).closest('tr').data('key'));
            $('#modal-delete').modal('show');
        });
        $('.popup-ban').click(function(e) {
            e.preventDefault();
            $('.texto-ban').text('Banear hasta:');
            var usuario = $(this).closest('td').siblings('td[data-col-seq=1]').text();
            $('#banform-usuario').val(usuario);
            $('#modal-ban').modal('show');
        });
        $('.ban-btn').on('click', function() {
            $('#form-ban').submit();
        });
    });"
);

$this->title = Yii::t('app', 'Reportes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reportes-index">
    <div class="panel panel-default panel-trade">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?= Yii::t('app', 'Listado de reportes') ?>
            </div>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'responsive' => true,
                'summary' => '',
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'header' => Yii::t('app', 'Enviado por'),
                        'format' => 'text',
                        'attribute' => 'reporta.usuario',
                        'headerOptions' => ['style' => 'width:15%'],
                    ],
                    [
                        'header' => Yii::t('app', 'Reportado'),
                        'format' => 'text',
                        'attribute' => 'reportado.usuario',
                        'headerOptions' => ['style' => 'width:15%'],
                    ],
                    [
                        'header' => Yii::t('app', 'Mensaje'),
                        'format' => 'text',
                        'attribute' => 'mensaje',
                        'headerOptions' => ['style' => 'width:60%'],
                    ],
                    [
                        'header' => Yii::t('app', 'Acción'),
                        'class' => ActionColumn::className(),
                        'headerOptions' => ['style' => 'width:10%'],
                        'template' => '{banear} {delete}',
                        'buttons' => [
                            'banear' => function ($url, $model, $key) {
                                if ($model->reportado->ban === null) {
                                    return Html::a(Utiles::FA('ban'), null,
                                    ['class' => 'btn btn-xs btn-tradegame popup-ban']);
                                }
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a(Utiles::FA('trash-alt'), null,
                                ['class' => 'btn btn-xs btn-danger popup-modal']);
                            }
                        ]
                    ]
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php Modal::begin([
 'header' => '<h2 class="modal-title">Borrar reporte</h2>',
 'id'     => 'modal-delete',
 'footer' => Html::beginForm(['/reportes/delete'], 'post') .
            Html::hiddenInput('id', -1, ['id' => 'id-reporte']) .
             Html::submitButton(
                 Utiles::FA('trash-alt') . ' Borrar',
                 ['class' => 'btn btn-danger']
             )
             . Html::endForm()
 ])
 ?>
 <p>¿Estás seguro de que deseas borrar este reporte?</p>
 <?php Modal::end(); ?>

 <?php Modal::begin([
  'header' => '<h2 class="modal-title">Banear usuario</h2>',
  'id'     => 'modal-ban',
  'footer' => Html::a(Utiles::FA('ban') . ' Banear', null,
    ['class' => 'btn btn-tradegame ban-btn'])
  ]) ?>
  <p class="texto-ban"></p>
  <?php $banForm = new BanForm ?>
  <?php $form = ActiveForm::begin([
     'id' => 'form-ban',
     'method' => 'post',
     'action' => ['usuarios/banear'],
 ]) ?>
  <?= $form->field($banForm, 'fecha')->widget(DateControl::classname(), [
      'type' => DateControl::FORMAT_DATE,
      'saveTimezone' => 'UTC',
      'readonly' => true,
      'widgetOptions' => [
          'layout' => '{picker}{input}{remove}',
          'pluginOptions' => [
              'autoclose' => true,
              'startDate' => '+1d'
          ]
      ],
      ])->label(false) ?>
      <?= $form->field($banForm, 'usuario')->hiddenInput()->label(false) ?>
  <?php ActiveForm::end() ?>
<?php Modal::end(); ?>
