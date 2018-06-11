# Dificultades encontradas

***Dificultades:***

#### Subir archivos más pesados de 2MB

PHP por defecto sólo permite subida de archivos de 2MB como máximo, y para la funcionalidad de subir imágenes de tus videojuegos en las publicaciones, necesitaba que se pudiese subir archivos un poco más pesados, ya que la mayoría de cámaras móviles (con la que se suelen hacer estas fotos) realizan fotos con un tamaño un poco mayor a 2MB.

Para ello he tenido que investigar cómo cambiar las directivas del núcleo `php.ini` en Heroku.
Tras un poco de investigación, logré encontrar que Heroku tiene un sistema para poder cambiar estas directivas rápidamente, creando un fichero `.user.ini` con las distintas directivas que se quieran modificar en la carpeta web del proyecto, sin tener que entrar en el código del programa.

#### Baja de usuarios

Se trata de la incidencia [#4](https://github.com/jlnarvaez/tradegame/issues/4) del proyecto.
Realicé esta incidencia lo más pronto posible porque pensaba que más adelante iba a ser peor, pero a medida que iba realizando la aplicación y creando más tablas en la base de datos, me dí cuenta que iba a tener que retocar si o sí la incidencia, por lo que la tuve que reabrir y dejarla pendiente.

El funcionamiento de la aplicación hace que esta incidencia sea un poco compleja, ya que algunos datos no deberían ser borrados a pesar de que el usuario se dé de baja.

Es por eso que decidí dejarla un poco de lado, hasta que ya más o menos tenía todas las tablas y posibilidades planteadas, y así poder realizarla cubriendo todos los casos posibles.


---

***Elementos de innovación:***

 - **Amazon S3**

 He usado Amazon S3 para alojar las imágenes de la aplicación web, ya que el modo gratuito de Heroku no permite la persistencia de archivos.

 Para ello he tenido que crearme una cuenta en Amazon AWS y solicitar una prueba gratuita, además de hacer uso de la API de Amazon AWS para subir y descargar imágenes de los bucket.

 - **i18n**

 Para que la aplicación sea multiidioma he hecho uso de la [internacionalización](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n) de Yii 2.

 Me resultaba interesante el hecho de tener más de un idioma, y además es algo que atrae a más público a tu sitio web.

 - **Google Translate**

 Al igual que en el caso de la internacionalización, he usado la API de Google Translate para traducir al idioma secundario de la aplicación (Inglés).

 Se hace uso de la API de Google Translate para traducir los textos que vienen directamente de la base de datos y que son modificables por los usuarios.
