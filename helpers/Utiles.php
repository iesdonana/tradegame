<?php

namespace app\helpers;

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
}
