<?php

namespace app\helpers;

use yii\helpers\Html;

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
     * @param  string $nombre Nombre del Glyphicon
     * @return string
     */
    public static function glyphicon($nombre)
    {
        return '<span class="glyphicon glyphicon-' . $nombre . '"></span>';
    }

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
