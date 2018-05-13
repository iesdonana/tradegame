<?php

namespace app\helpers;

use Yii;

use Statickidz\GoogleTranslate;

use app\models\Mensajes;
use app\models\Usuarios;
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
        $valores = [OfertasUsuarios::className(), Valoraciones::className(), Mensajes::className()];
        foreach ($valores as $clase) {
            $sum += call_user_func($clase . '::getPendientes');
        }
        if ($sum > 0) {
            return Html::tag('span', $sum, ['class' => 'badge badge-custom']);
        }
        return '';
    }

    /**
     * Calcula la distancia entre dos localizaciones
     * @param  UsuariosDatos $geoloc1 Primer usuario
     * @param  UsuariosDatos $geoloc2 Segundo usuario
     * @return float          Distancia
     */
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

    /**
     * Genera una eiqueta span con la clase 'label' de Bootstrap, con un número
     * dentro de él.
     * @param  int    $maxCaracteres Número dentro de la etiqueta
     * @return string                Etiqueta html
     */
    public static function contadorCaracteres($maxCaracteres)
    {
        return Html::tag('span', $maxCaracteres, [
            'class' => 'label label-primary caracteresRestantes',
            'title' => 'Carácteres restantes'
        ]);
    }

    /**
     * Genera un nombre de usuario a través de su correo electrónico.
     * @param  string $email Correo electrónico
     * @return string        Nombre de usuario generado
     */
    public static function generarUsername($email)
    {
        $user = explode('@', $email)[0];
        $cont = 0;
        do {
            if (($exist = (Usuarios::findOne(['usuario' => $user]) !== null))) {
                $user = $user . ++$cont;
            }
        } while ($exist);

        return $user;
    }

    /**
     * Devuelve un array para añadir al filtro en un ActiveQuery. El array se forma
     * a través del parámetro datos.
     * @param  string $col   Columna de la base de datos
     * @param  string $datos Datos a filtrar separados por comas
     * @return array         Array preparado para colocar en ActiveQuery (where)
     */
    public static function filtroAvanzado($col, $datos)
    {
        $arr = explode(',', $datos);
        $res = ['or'];
        foreach ($arr as $dato) {
            $res[] = [$col => $dato];
        }
        return $res;
    }

    /**
     * Borra imágenes de S3 que ya existan con el nombre pasado por parámetro,
     * en la carpeta pasada por parámetro.
     * @param  string $carpeta Carpeta en la que se va a buscar en S3
     * @param  string $name    Nombre del fichero
     * @return bool true si se ha borrado correctamente.
     */
    public static function borrarAnteriores($carpeta, $name)
    {
        $path = Yii::getAlias('@' . $carpeta . '/');
        $ficheros = glob($path . $name . '.*');
        foreach ($ficheros as $fichero) {
            return unlink($fichero);
        }
        $s3 = Yii::$app->get('s3');

        $ruta = $path . $name . '.jpg';
        if ($s3->exist($ruta)) {
            $s3->delete($ruta);
        } else {
            $ruta = $path . $name . '.png';
            $s3->delete($ruta);
        }
    }

    /**
     * Aplica la internacionalización a un array
     * @param  array $arr Array que queremos traducir
     * @return array      Array traducido
     */
    public static function translateArray($arr)
    {
        $res = $arr;
        foreach ($arr as $key => $value) {
            $res[$key] = Yii::t('app', $value);
        }
        return $res;
    }

    /**
     * Traduce, si fuera necesario, la cadena pasada por parámetro al idioma de la aplicación
     * @param  string $text Texto a traducir
     * @return string       Texto traducido
     */
    public static function translate($text)
    {
        $trans = new GoogleTranslate();
        $source = array_keys(Yii::$app->params['sourceLanguage'])[0];
        if ($source !== Yii::$app->language) {
            $text = $trans->translate($source, Yii::$app->language, $text);
        }
        return $text;
    }
}
