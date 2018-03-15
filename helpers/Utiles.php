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
     * Define si el icono es de tipo Glyphicon.
     * @var int
     */
    public const GLYPHICON = 0;
    /**
     * Define si el icono es de tipo Font Awesome.
     * @var int
     */
    public const FONT_AWESOME = 1;

    /**
     * Devuelve un template para poner a un campo de un ActiveForm con un
     * icono.
     * @param string  $icon     Nombre del icono
     * @param int     $tipo     GLYPHICON ó FONT_AWESOME
     * @param mixed   $options  Opciones del icono
     * @return string La cadena del template
     */
    public static function inputTemplate($icon, $tipo, $options = [])
    {
        $res = self::glyphicon($icon, $options);
        if ($tipo === self::FONT_AWESOME) {
            $res = self::FA($icon, $options);
        }
        return '<div class="input-group">
                <span class="input-group-addon">' .
                $res .
                '</span>
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

        return Html::tag('i', null, $array);
    }

    /**
     * Devuelve un icono de Font Awesome.
     * @param string  $nombre  Clase del icono
     * @param array   $options Opciones para la etiqueta
     * @param string
     * @param mixed $options
     */
    public static function FA($nombre, $options = [])
    {
        $array = ['class' => 'fa-' . $nombre];
        if (!isset($options['class'])) {
            $options['class'] = 'fas';
        }
        $array['class'] = $options['class'] . ' ' . $array['class'];

        if (isset($options['tooltip'])) {
            $array = array_merge($array, [
                'title' => $options['tooltip'],
                'data-toggle' => 'tooltip',
            ]);
        }

        return Html::tag('i', null, $array);
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
