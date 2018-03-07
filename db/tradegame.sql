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
  , nombre_real  varchar(255)
  , localidad    varchar(255)
);

INSERT INTO usuarios (usuario, email, password, auth_key)
    VALUES ('admin', 'admin@admin.com', crypt('admin123', gen_salt('bf', 13)), 'GnT4M2ZjLDGxNrGe-2THbAjqFLwyJ1fa');
