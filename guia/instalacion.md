
# Instrucciones de instalación y despliegue

## En local

***Requisitos mínimos:***

 - PHP 7.1.0 o superior
 - [Composer](https://getcomposer.org/)
 - PostgreSQL
 - Cuenta en Amazon S3
 - Cuenta de correo electrónico

***Instalación:***

 1. Clonamos el repositorio:

   ```bash
   git clone https://github.com/jlnarvaez/tradegame.git
   ```

 2. Nos dirigimos al directorio raíz del proyecto donde hayamos clonado el repositorio, y ejecutamos el siguiente comando para iniciar el servidor:

   ```bash
   make serve
   ```

 3. Instalamos los paquetes de composer necesarios:

   ```bash
   composer install
   ```

 4. Creamos la base de datos y las respectivas tablas para hacer funcionar la aplicación:
   ```bash
   db/create.sh
   db/load.sh
   ```
   > La base de datos creada tendrá como nombre **tradegame**. El nombre de usuario y la contraseña tendrán el mismo valor que el nombre de la misma.

 5. Creamos un archivo con el nombre `env` en la raíz del proyecto con las siguientes variables de entornos:

  * SECRET_S3: Código secreto de Amazon S3.
  * KEY_S3: Clave secreta de Amazon S3.
  * SMTP_PASS: Clave generada usando *Contraseña de aplicación*
  * MAPS_KEY: Clave para el acceso a Google Maps. Para obtener la clave visita la [consola de Google](https://code.google.com/apis/console/).

  &nbsp;
 6. Nos dirigimos al navegador e introducimos al siguiente URL para acceder a la aplicación:

 > http://localhost:8080


## En la nube

***Requisitos mínimos:***

 - [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli)

***Instalación:***

 1. Nos creamos una cuenta en [Heroku](https://dashboard.heroku.com/).
 2. Creamos una nueva aplicación.
 3. En el apartado *Resources*, nos iremos a *Add-ons* y añadiremos el add-on **Heroku Postgres**.
 4. Añadimos las variables de entorno que podemos ver en el punto 5 de la *Instalación en local*, desde *Settings > Config Vars*.
 5. Hacemos login en Heroku a través de Heroku CLI con el siguiente comando:
 ```bash
 heroku login
 ```
 5. Ejecutamos los siguientes comandos desde la raíz del proyecto:

 ```bash
heroku git:remote --app nombre_app_heroku
heroku config:set YII_ENV=prod
 ```

 6. Insertamos las tablas y datos en la base de datos de Heroku:

 ```bash
 heroku psql < db/tradegame.sql
 heroku psql < db/tradegame_inserciones.sql
 ```

 7. Sincronizamos nuestro proyecto de Heroku con nuestro repositorio en Github, desde el apartado *Settings > Deployment method*.

 8. Una vez sincronizado con nuestro proyecto en Github, indicaremos en qué rama queremos que se hagan los *deploy*.
