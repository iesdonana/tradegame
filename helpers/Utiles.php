<?php

namespace app\helpers;

use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Helper con funciones que nos van a hacer falta a lo largo de la aplicación
 * en distintos lugares.
 */
class Utiles
{
    /**
     * Devuelve un template para poner a un campo de un ActiveForm con un
     * glyphicon de Bootstrap.
     * @param  string $glyphicon Nombre del Glyphicon
     * @return string            La cadena del template
     */
    public static function inputGlyphicon($glyphicon)
    {
        return '<div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-' . $glyphicon . '"></i>
                    </span>
                    {input}
               </div>
               {error}{hint}';
    }

    /**
     * Devuelve un Glyphicon.
     * @param string $nombre Nombre del Glyphicon
     * @param string $tooltip Contenido del tooltip (Opcional)
     * @return string
     */
    public static function glyphicon($nombre, $tooltip = null)
    {
        $array = ['class' => 'glyphicon glyphicon-' . $nombre];
        if ($tooltip !== null) {
            $array = array_merge($array, [
                'title' => $tooltip,
                'data-toggle' => 'tooltip',
            ]);
        }

        return Html::tag('span', null, $array);
    }

    public static function optionsSelect2($urlAjax)
    {
        return [
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => $urlAjax,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatVideojuego'),
                'templateSelection' => new JsExpression('function (videojuego) { return videojuego.nombre; }'),
            ],
        ];
    }

    /**
     * Devuelve un 'badge' de Bootstrap con una clase, dependiendo de la plataforma
     * del videojuego.
     * @param  string $plataforma Nombre de la plataforma
     * @return string             <span> con la clase correspondiente
     */
    public static function badgePlataforma($plataforma)
    {
        switch ($plataforma) {
            case 'PlayStation 4':
                $clase = 'badge badge-ps4';
                break;
            case 'PlayStation 3':
                $clase = 'badge';
                break;
            case 'PlayStation 2':
                $clase = 'badge badge-ps2';
                break;
            case 'Nintendo Switch':
                $clase = 'badge badge-switch';
                break;
            case 'PC':
                $clase = 'badge badge-pc';
                break;
            case 'XBOX 360':
                $clase = 'badge badge-xbox360';
                break;
            case 'XBOX One':
                $clase = 'badge badge-xboxone';
                break;
        }

        return Html::tag('span', $plataforma, ['class' => $clase]);
    }
}
