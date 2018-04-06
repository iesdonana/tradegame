------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios_id CASCADE;

CREATE TABLE usuarios_id
(
    id bigserial PRIMARY KEY
);

DROP TABLE IF EXISTS roles CASCADE;

CREATE TABLE roles
(
    id   bigserial PRIMARY KEY
  , tipo varchar(255)
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id   bigint       PRIMARY KEY REFERENCES usuarios_id (id)
  , usuario      varchar(20)  NOT NULL UNIQUE
  , email        varchar(100) NOT NULL
  , password     varchar(255) NOT NULL
  , token_val    varchar(255)
  , auth_key     varchar(255)
  , token_pass   varchar(255)
  , rol_id       bigint       REFERENCES roles (id)
  , created_at   timestamp(0) NOT NULL DEFAULT localtimestamp
  , updated_at   timestamp(0)
);

DROP TABLE IF EXISTS usuarios_generos CASCADE;

CREATE TABLE usuarios_generos
(
    id   bigserial    PRIMARY KEY
  , sexo varchar(255)
);

DROP TABLE IF EXISTS usuarios_datos CASCADE;

CREATE TABLE usuarios_datos
(
    id_usuario       bigint       PRIMARY KEY REFERENCES usuarios (id)
                                  ON DELETE CASCADE
  , nombre_real      varchar(255)
  , localidad        varchar(255)
  , provincia        varchar(255)
  , direccion        varchar(255)
  , geoloc           varchar(255)
  , telefono         varchar(9)
  , biografia        varchar(255)
  , fecha_nacimiento date
  , genero_id        bigint       REFERENCES usuarios_generos (id)
);

DROP TABLE IF EXISTS plataformas CASCADE;

CREATE TABLE plataformas
(
    id     bigserial    PRIMARY KEY
  , nombre varchar(255) NOT NULL
);

DROP TABLE IF EXISTS generos_videojuegos CASCADE;

CREATE TABLE generos_videojuegos
(
    id     bigserial    PRIMARY KEY
  , nombre varchar(255) NOT NULL
);

DROP TABLE IF EXISTS desarrolladores_videojuegos CASCADE;

CREATE TABLE desarrolladores_videojuegos
(
    id       bigserial    PRIMARY KEY
  , compania varchar(255) NOT NULL
);

DROP TABLE IF EXISTS videojuegos CASCADE;

CREATE TABLE videojuegos
(
    id                bigserial    PRIMARY KEY
  , nombre            varchar(255) NOT NULL
  , descripcion       text
  , fecha_lanzamiento date
  , desarrollador_id  bigint       NOT NULL REFERENCES desarrolladores_videojuegos (id)
                                   ON DELETE NO ACTION ON UPDATE CASCADE
  , genero_id         bigint       NOT NULL REFERENCES generos_videojuegos (id)
                                   ON DELETE NO ACTION ON UPDATE CASCADE
  , plataforma_id     bigint       NOT NULL REFERENCES plataformas (id)
                                   ON DELETE NO ACTION ON UPDATE CASCADE
);

DROP TABLE IF EXISTS videojuegos_usuarios CASCADE;

CREATE TABLE videojuegos_usuarios
(
    id            bigserial    PRIMARY KEY
  , videojuego_id bigint       NOT NULL REFERENCES videojuegos (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , usuario_id    bigint       NOT NULL REFERENCES usuarios_id (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , mensaje       varchar(255)
  , created_at    timestamp(0) NOT NULL DEFAULT localtimestamp
  , visible       boolean      DEFAULT true
  , borrado       boolean      DEFAULT false
);

DROP TABLE IF EXISTS ofertas CASCADE;

CREATE TABLE ofertas
(
    id                        bigserial    PRIMARY KEY
  , videojuego_publicado_id   bigint       NOT NULL REFERENCES videojuegos_usuarios (id)
                                           ON DELETE NO ACTION ON UPDATE CASCADE
  , videojuego_ofrecido_id    bigint       NOT NULL REFERENCES videojuegos_usuarios (id)
                                           ON DELETE NO ACTION ON UPDATE CASCADE
  , contraoferta_de           bigint       REFERENCES ofertas (id)
                                           ON DELETE NO ACTION ON UPDATE CASCADE
  , created_at                timestamp(0) NOT NULL DEFAULT localtimestamp
  , aceptada                  boolean      DEFAULT NULL
);

DROP TABLE IF EXISTS valoraciones CASCADE;

CREATE TABLE valoraciones
(
    id                  bigserial    PRIMARY KEY
  , usuario_valorado_id    bigint    NOT NULL REFERENCES usuarios (id)
                                     ON DELETE NO ACTION ON UPDATE CASCADE
  , usuario_valora_id      bigint    NOT NULL REFERENCES usuarios (id)
                                     ON DELETE NO ACTION ON UPDATE CASCADE
  , comentario          varchar(255)
  , num_estrellas       numeric(1)   CONSTRAINT ck_estrellas_correctas
                                     CHECK (num_estrellas > 0 AND num_estrellas <= 5)
);

DROP TABLE IF EXISTS mensajes CASCADE;

CREATE TABLE mensajes
(
    id          bigserial    PRIMARY KEY
  , emisor_id   bigint       NOT NULL REFERENCES usuarios (id)
                             ON DELETE NO ACTION ON UPDATE CASCADE
  , receptor_id bigint       NOT NULL REFERENCES usuarios (id)
                             ON DELETE NO ACTION ON UPDATE CASCADE
  , contenido   varchar(255) NOT NULL
  , leido       boolean      NOT NULL DEFAULT false
  , created_at   timestamp(0) NOT NULL DEFAULT localtimestamp
);

DROP TABLE IF EXISTS reportes CASCADE;

CREATE TABLE reportes
(
    id          bigserial    PRIMARY KEY
  , reporta_id bigint NOT NULL REFERENCES usuarios (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , reportado_id bigint NOT NULL REFERENCES usuarios (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , mensaje varchar(255) NOT NULL CHECK (length(mensaje) >= 20)
);

DROP VIEW IF EXISTS ofertas_usuarios;

CREATE VIEW ofertas_usuarios as
SELECT o.*,
       vup.id as id_publicado, v1.nombre as publicado,
       vuo.id as id_ofrecido, v2.nombre as ofrecido,
       u1.usuario as usuario_publicado,
       u2.usuario as usuario_ofrecido
FROM ofertas as o
    LEFT JOIN videojuegos_usuarios as vup
    ON videojuego_publicado_id = vup.id
    LEFT JOIN videojuegos_usuarios as vuo
    ON videojuego_ofrecido_id = vuo.id
    LEFT JOIN videojuegos as v1
    ON v1.id = vup.videojuego_id
    LEFT JOIN videojuegos as v2
    ON v2.id = vuo.videojuego_id
    LEFT JOIN usuarios as u1
    ON u1.id = vup.usuario_id
    LEFT JOIN usuarios as u2
    ON u2.id = vuo.usuario_id;

DROP VIEW IF EXISTS top_valoraciones;

CREATE VIEW top_valoraciones as
SELECT ur.usuario, avg(num_estrellas), count(*) as totales
FROM valoraciones LEFT JOIN usuarios as ur
    ON usuario_valorado_id = ur.id
WHERE num_estrellas IS NOT null
GROUP BY ur.usuario;
