------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id           bigserial    PRIMARY KEY
  , usuario      varchar(20)  NOT NULL UNIQUE
  , email        varchar(100) NOT NULL
  , password     varchar(255) NOT NULL
  , token_val    varchar(255)
  , auth_key     varchar(255)
);

INSERT INTO usuarios (usuario, email, password, auth_key)
    VALUES ('admin', 'admin@admin.com', crypt('admin123', gen_salt('bf', 13)), 'GnT4M2ZjLDGxNrGe-2THbAjqFLwyJ1fa');

DROP TABLE IF EXISTS usuarios_datos CASCADE;

CREATE TABLE usuarios_datos
(
    id_usuario       bigint       PRIMARY KEY REFERENCES usuarios (id)
  , nombre_real      varchar(255)
  , localidad        varchar(255)
  , biografia        varchar(255)
  , fecha_nacimiento date
);

INSERT INTO usuarios_datos (id_usuario, nombre_real, biografia)
    VALUES (1, 'Administrador', 'Soy el administrador que todo lo sabe');
