<?php

namespace app\helpers;

use app\models\OfertasUsuarios;
use app\models\Valoraciones;
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

    public static function tagPosicion($pos, $texto)
    {
        switch ($pos) {
            case 1:
            case 2:
            case 3:
                return Html::tag("h$pos", $texto);
            default:
                return $texto;
        }
    }

    /**
     * Pinta un número de estrellas coloreadas, dependiendo de el número que se le pase por
     * parámetro.
     * @param  int    $numEstrellas Número de estrellas que se van a pintar de color
     * @return string
     */
    public static function pintarEstrellas($numEstrellas)
    {
        $res = '';
        for ($i = 0; $i < 5; $i++) {
            $class = ['class' => 'fas fa-lg'];
            if ($i < $numEstrellas) {
                $class = ['class' => 'fas fa-lg puntuacion'];
            }
            $res .= self::FA('star', $class);
        }
        return $res;
    }

    public static function badgeNotificacionesPendientes($clase)
    {
        $pendientes = call_user_func($clase . '::getPendientes');
        if ($pendientes > 0) {
            return Html::tag('span', $pendientes, ['class' => 'badge badge-custom']);
        }
        return '';
    }

    public static function badgeNotificacionesTotales()
    {
        $sum = 0;
        $valores = [OfertasUsuarios::className(), Valoraciones::className()];
        foreach ($valores as $clase) {
            $sum += call_user_func($clase . '::getPendientes');
        }
        if ($sum > 0) {
            return Html::tag('span', $sum, ['class' => 'badge badge-custom']);
        }
        return '';
    }

    public static function distancia($geoloc1, $geoloc2)
    {
        $theta = $geoloc1->lng - $geoloc2->lng;
        $dist = sin(deg2rad($geoloc1->lat)) * sin(deg2rad($geoloc2->lat)) +
            cos(deg2rad($geoloc1->lat)) * cos(deg2rad($geoloc2->lat)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344;
    }
}
