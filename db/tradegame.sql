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
  , fecha_lanzamiento timestamp(0)
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
  , usuario_id    bigint       NOT NULL REFERENCES usuarios (id)
                               ON DELETE CASCADE ON UPDATE CASCADE
  , mensaje       varchar(255)
  , created_at    timestamp(0) NOT NULL DEFAULT localtimestamp
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
  , UNIQUE (videojuego_publicado_id, videojuego_ofrecido_id)
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

INSERT INTO generos_videojuegos (nombre)
    VALUES ('Acción'), ('Terror'), ('Shooter'), ('Deportes'), ('Aventuras')
         , ('Conducción'), ('Rol'), ('Party'), ('Plataforma');

INSERT INTO plataformas (nombre)
    VALUES ('PlayStation 4'), ('PlayStation 3'), ('PlayStation 2')
         , ('Nintendo Switch'), ('PC'), ('XBOX 360'), ('XBOX One');

INSERT INTO desarrolladores_videojuegos (compania)
    VALUES ('Naughty Dog'), ('EA Sports'), ('Vicarious Visions'), ('Konami'),
           ('BitBox Ltd.'), ('Guerrilla Games'), ('Ubisoft'), ('Real Time Worlds'),
           ('Capcom'), ('Bluehole Studio'), ('Turn 10'), ('Valve');

INSERT INTO videojuegos (nombre, descripcion, fecha_lanzamiento,
                        desarrollador_id, genero_id, plataforma_id)
    VALUES
        -- PlayStation 4 --
        ('Crash Bandicoot: N.Sane Trilogy',
        'Un marsupial que rompe cajas y consigue Wumpa Fruit a través de los ' ||
        'distintos niveles, intentando combatir con sus enemigos.',
        '2017-06-30', 3, 9, 1),
        ('FIFA 18',
        'El mejor fútbol de EA Sports regresa con FIFA 18. El videojuego' ||
        'de simulación deportiva, que cuenta con Cristiano Ronaldo como ' ||
        'protagonista de su portada, promete su entrega más ambiciosa hasta ' ||
        'la fecha, ya que desde EA Sports se asegura que para la temporada ' ||
        'de fútbol 2017-2018 se ofrecerá el mayor salto de calidad dentro ' ||
        'del campo de juego.',
        '2017-09-29', 2, 4, 1),
        ('PES 2018',
        'PES 2018 es la apuesta de Konami en el terreno de la simulación ' ||
        'deportiva de fútbol para la temporada 2017-2018. El videojuego ' ||
        'cuenta en esta ocasión con el denominado Juego Magistral, que ' ||
        'ofrece elementos como el toque realista, los regates estratégicos ' ||
        'y una atención renovada por el juego a balón parado, algo olvidada ' ||
        'en las entregas de esta serie de fútbol. Así que en este sentido ' ||
        'Pro Evolution Soccer 2018 apuesta por ofrecer mayor realismo en las ' ||
        'fintas y los pases, así como en los golpes francos y faltas indirectas.',
        '2017-09-14', 4, 4, 1),
        ('Horizon: Zero Dawn',
        'Horizon: Zero Dawn es un videojuego de Guerrilla Games, los creadores ' ||
        'de la saga Killzone, que presenta un cuidado universo de fantasía con ' ||
        'un sugerente planteamiento argumental y jugable. El juego, exclusivo ' ||
        'de PlayStation 4 y con mejoras para PS4 Pro, se ambienta en un mundo ' ||
        'abierto en el que la naturaleza ha reclamado las ruinas de una ' ||
        'civilización olvidada y la humanidad ya no es la especie dominante, ' ||
        'sino unas avanzadas maquinas de origen desconocido.',
        '2017-03-01', 6, 1, 1),
        ('Far Cry 5',
        'Ambientado en la región de Hope County, en Montana, Far Cry 5 es la ' ||
        'quinta entrega numerada de la saga de Ubisoft, Far Cry. Una marca que ' ||
        'nos ha llevado por ambientaciones modernas y también prehistóricas, ' ||
        'así como a través de lugares de lo más exótico. En esta ocasión el ' ||
        'videojuego de acción y aventura o shooter nos lleva de la mano a ' ||
        'Estados Unidos para explorar sorprendentes horizontes en un ' ||
        'planteamiento de mundo abierto en el que los disparos, los vehículos ' ||
        'y los animales salvajes siguen teniendo una importancia capital. ',
        '2018-03-27', 7, 1, 1),
        -- Xbox 360 --
        ('Crackdown',
        'La ciudad Pacific City está totalmente corrupta y repleta de gansters,' ||
        'sólo algunos superhéroes podrán lograr que esto cambie. “Crackdown”, ' ||
        'es un híbrido que mezcla acción y conducción por gigantescos escenarios 3D.',
        '2007-02-23', 8, 1, 6),
        -- PlayStation 2 --
        ('Resident Evil 4',
        'Cuarta entrega de la famosa saga de terror creada por Capcom, ' ||
        'que en esta ocasión traslada su desarrollo hasta una misteriosa' ||
        ' localización europea desde la que Leon S. Kennedy, el protagonista, ' ||
        'deberá rescatar a la hija del presidente de los Estados Unidos de ' ||
        'unos aldeanos aparentemente convertidos en zombis.',
        '2006-03-23', 9, 3, 3),
        -- PC --
        ('PlayerUnknown''s Battlegrounds',
        'Una auténtica Battle Royale es lo que promete el videojuego ' ||
        'Player Unknown''s Battlegrounds. Tienes total libertad para sembrar el ' ||
        'caos en cada rincón de su extenso mapa y utilizar todas las armas ' ||
        'disponibles para acabar con tus rivales sin que se den cuenta de tu ' ||
        'mera presencia. La supervivencia es vital: cada minuto vivo es una ' ||
        'victoria en el juego.',
        '2017-12-20', 10, 3, 5),
        ('Far Cry 5',
        'Ambientado en la región de Hope County, en Montana, Far Cry 5 es la ' ||
        'quinta entrega numerada de la saga de Ubisoft, Far Cry. Una marca que ' ||
        'nos ha llevado por ambientaciones modernas y también prehistóricas, ' ||
        'así como a través de lugares de lo más exótico. En esta ocasión el ' ||
        'videojuego de acción y aventura o shooter nos lleva de la mano a ' ||
        'Estados Unidos para explorar sorprendentes horizontes en un ' ||
        'planteamiento de mundo abierto en el que los disparos, los vehículos ' ||
        'y los animales salvajes siguen teniendo una importancia capital. ',
        '2018-03-27', 7, 1, 5),
        ('Forza 7',
        'Forza 7 es la entrega de la conocida saga de simulación y velocidad ' ||
        'Forza firmada por Turn 10. El Lamborghini Centenario es su "coche de ' ||
        'portada", aunque hay una prometedora asociación con Porsche y con el ' ||
        'potente Porsche 911 GT2RS en cabeza. Forza 7 saca partido de la ' ||
        'potencia de las distintas versiones de la consola y de los ordenadores ' ||
        'de la actualidad para ofrecer la misma exactitud en su experiencia de ' ||
        'conducción y unos gráficos a la altura de lo que la serie ha venido ' ||
        'acostumbrando.',
        '2017-10-03', 11, 6, 5),
        ('Counter Strike: Source',
        'Counter-Strike Source es un juego de acción (shooter) desarrollado ' ||
        'por Valve y distribuido por VU Games para PC. La fecha de lanzamiento ' ||
        'de este videojuego es el 16 de noviembre de 2004.',
        '2004-11-16', 12, 3, 5);
