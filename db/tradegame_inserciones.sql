
-- INSERCIONES --

INSERT INTO usuarios_generos (sexo)
    VALUES ('Hombre'), ('Mujer');

INSERT INTO roles (tipo)
    VALUES ('Administrador'), ('Usuario');

INSERT INTO usuarios_id (id) VALUES (DEFAULT), (DEFAULT), (DEFAULT), (DEFAULT);

INSERT INTO usuarios (id, usuario, email, password, auth_key, rol_id)
    VALUES (1, 'admin', 'admin@admin.com', crypt('admin123', gen_salt('bf', 13)), 'GnT4M2ZjLDGxNrGe-2THbAjqFLwyJ1fa', 1),
        (2, 'celu', 'joseluis.narvaez@iesdonana.org', crypt('celu123', gen_salt('bf', 13)), 'qmjxYKMqeOqrIfDwpt0Badk4VvPfts-n', 2),
        (3, 'ivan', 'ivan@ivan.com', crypt('ivan123', gen_salt('bf', 13)), 'aIL6v0fpj42nuBmouXekziMa1yOCLpa4', 2),
        (4, 'pepe', 'pepe@pepe.com', crypt('pepe123', gen_salt('bf', 13)), 'nykqGk2mEA6XjmNOlR2tiinDPQinG7A8', 2);

INSERT INTO usuarios_datos (id_usuario, nombre_real, biografia, localidad, geoloc)
    VALUES (1, 'Administrador', 'Soy el administrador que todo lo sabe', 'Sanlúcar de Barrameda', '36.7725774,-6.352968899999951'),
            (2, 'Jose Luis Narváez', 'Me gustan los videojuegos y la programación', 'Jerez de la Frontera', '36.6850064,-6.126074399999993'),
            (3, 'Iván Herrera', DEFAULT, 'Chipiona', '36.7348614,-6.4316989999999805'),
            (4, 'Pepe Rodríguez', DEFAULT, 'Córdoba', '37.8881751,-4.7793834999999945');

INSERT INTO mensajes (emisor_id, receptor_id, contenido, leido, created_at)
    VALUES (1, 2, 'Hola que tal', true, localtimestamp - 'P3M'::interval),
            (2, 1, 'Hola soy celu', true, localtimestamp - 'P2M'::interval),
            (1, 2, 'Hola otra vez', true, localtimestamp - 'P1M'::interval),
            (3, 2, 'Hola otra vez', true, localtimestamp - 'P1M'::interval),
            (4, 2, 'Hola otra vez', true, localtimestamp - 'P2M'::interval);

INSERT INTO reportes (reporta_id, reportado_id, mensaje)
    VALUES (1, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' ||
    'Nam vel lectus malesuada neque interdum ultricies vitae sit amet elit. Etiam.'),
    (2, 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' ||
    'Nam vel lectus malesuada neque interdum ultricies vitae sit amet elit. Etiam.');

INSERT INTO generos_videojuegos (nombre)
    VALUES ('Acción'), ('Terror'), ('Shooter'), ('Deportes'), ('Aventuras')
         , ('Conducción'), ('Rol'), ('Party'), ('Plataforma');

INSERT INTO plataformas (nombre)
    VALUES ('PlayStation 4'), ('PlayStation 3'), ('PlayStation 2')
         , ('Nintendo Switch'), ('PC'), ('XBOX 360'), ('XBOX One');

INSERT INTO desarrolladores_videojuegos (compania)
    VALUES ('Naughty Dog'), ('EA Sports'), ('Vicarious Visions'), ('Konami'),
           ('BitBox Ltd.'), ('Guerrilla Games'), ('Ubisoft'), ('Real Time Worlds'),
           ('Capcom'), ('Bluehole Studio'), ('Turn 10'), ('Valve'), ('Nintendo');

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
        'Super Mario Odyssey es el primer juego de Mario en un mundo abierto ' ||
        'desde Super Mario 64 para Nintendo 64 y Super Mario Sunshine para ' ||
        'Nintendo GameCube. El título, totalmente tridimensional o en 3D, se ' ||
        'desarrolla en el planeta Tierra, ya que Mario deja el Reino Champiñón ' ||
        'para embarcarse en un viaje por lugares misteriosos y vivir nuevas ' ||
        'aventuras a bordo de una aeronave, demostrando el hábil manejo de ' ||
        'su gorra, ya que gracias a ella Mario puede tomar el control de los ' ||
        'enemigos. En el juego hay mucho plataformeo, secretos y sorpresas, ' ||
        'pero también abundantes partes de acción y hasta pruebas que parecen puzles.',
        '2017-10-27', 12, 3, 5),
        -- Nintendo Switch --
        ('Super Mario Odyssey',
        'Counter-Strike Source es un juego de acción (shooter) desarrollado ' ||
        'por Valve y distribuido por VU Games para PC. La fecha de lanzamiento ' ||
        'de este videojuego es el 16 de noviembre de 2004.',
        '2004-11-16', 13, 9, 4),
        ('Zelda: Breath of the Wild',
        'El videojuego más grande en la historia de Nintendo. Esta es la carta ' ||
        'de presentación de The Legend of Zelda: Breath of the Wild para Wii U y ' ||
        'Switch, una épica aventura que lleva la acción de esta veterana ' ||
        'franquicia a un gigantesco mundo abierto que podemos explorar con ' ||
        'total libertad. ¡No hay límites! Link puede coger un caballo, o ' ||
        'cualquier otra montura, y explorar la nueva Hyrule siguiendo el orden ' ||
        'que desee el jugador, pues la historia ya no sigue un camino lineal. ',
        '2017-03-03', 13, 1, 4);

INSERT INTO videojuegos_usuarios (videojuego_id, usuario_id)
    VALUES (1, 1), (2, 1), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2), (8, 2);

INSERT INTO ofertas (videojuego_publicado_id, videojuego_ofrecido_id)
    VALUES (1, 3), (2, 3);
