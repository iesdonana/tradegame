------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios_id CASCADE;

CREATE TABLE usuarios_id
(
    id bigserial PRIMARY KEY
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
  , telefono         varchar(9)
  , biografia        varchar(255)
  , fecha_nacimiento date
  , genero_id        bigint       REFERENCES usuarios_generos (id)
);

-- INSERCIONES --

INSERT INTO usuarios_generos (sexo)
    VALUES ('Hombre'), ('Mujer');

INSERT INTO usuarios_id (id) VALUES (DEFAULT), (DEFAULT);

INSERT INTO usuarios (id, usuario, email, password, auth_key)
    VALUES (1, 'admin', 'admin@admin.com', crypt('admin123', gen_salt('bf', 13)), 'GnT4M2ZjLDGxNrGe-2THbAjqFLwyJ1fa'),
            (2, 'celu', 'celu@celu.com', crypt('celu123', gen_salt('bf', 13)), 'qmjxYKMqeOqrIfDwpt0Badk4VvPfts-n');

INSERT INTO usuarios_datos (id_usuario, nombre_real, biografia)
    VALUES (1, 'Administrador', 'Soy el administrador que todo lo sabe'),
            (2, 'Jose Luis Narváez', 'Me gustan los videojuegos y la programación');
