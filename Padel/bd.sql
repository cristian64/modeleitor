drop table if exists intentos;
drop table if exists reservas;
drop table if exists usuarios;
drop table if exists pistas;

create table pistas
(
    id int not null,
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table usuarios
(
    id int not null auto_increment,
    email varchar(255) not null,
    nombre text not null,
    contrasena varchar(40) not null,
    dni text not null,
    sexo enum ('hombre', 'mujer') not null,
    direccion text not null,
    telefono text not null,
    admin int not null default 0,
    fecha_registro datetime not null,
    primary key (id),
    unique (email)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table reservas
(
    id int not null auto_increment,
    id_usuario int not null,
    id_pista int not null,
    fecha_inicio datetime not null,
    fecha_fin datetime not null,
    fecha_realizacion datetime not null,
    reservable int not null default 1,
    primary key (id),
    unique (id_pista, fecha_inicio),
    unique (id_pista, fecha_fin)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table intentos
(
    ip varchar(40) not null,
    fecha datetime not null,
    primary key (ip)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

insert into pistas (id) values (1);
insert into pistas (id) values (2);
insert into pistas (id) values (3);
insert into pistas (id) values (4);
insert into pistas (id) values (5);
insert into pistas (id) values (6);

insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin, fecha_registro)
values ('cristian', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'cristian@correo.com', '74236860T', 'hombre', '', '+34 630 276 575', 1, now());
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin, fecha_registro)
values ('santi', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'santi@correo.com', '74236860T', 'hombre', '', '+34 600 000 000', 1, now());
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin, fecha_registro)
values ('bea', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'bea@correo.com', '74236860T', 'mujer', '', '+34 600 111 111', 1, now());
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin, fecha_registro)
values ('jose', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'jose@correo.com', '74236860T', 'hombre', '', '+34 600 222 222', 0, now());

insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, fecha_realizacion, reservable)
values (1, 1, '2012/05/14 10:30:00', '2012/05/14 12:00:00', now(), 1);
insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, fecha_realizacion, reservable)
values (1, 1, '2012/05/13 12:00:00', '2012/05/13 14:30:00', now(), 1);
