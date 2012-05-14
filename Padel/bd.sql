drop table if exists reservas;
drop table if exists usuarios;
drop table if exists pistas;

create table pistas
(
	id int not null,
	primary key (id)
) engine = mysam default charset=utf8 collate=utf8_general_ci;

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
	primary key (id),
	unique (email)
) engine = mysam default charset=utf8 collate=utf8_general_ci;

create table reservas
(
	id int not null auto_increment,
	id_usuario int not null,
	id_pista int not null,
	fecha_inicio datetime not null,
	fecha_fin datetime not null,
	primary key (id),
	foreign key (id_usuario) references usuarios (id),
	foreign key (id_pista) references pistas (id)
) engine = mysam default charset=utf8 collate=utf8_general_ci;


insert into pistas (id) values (1);
insert into pistas (id) values (2);
insert into pistas (id) values (3);
insert into pistas (id) values (4);
insert into pistas (id) values (5);
insert into pistas (id) values (6);

insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin)
values ('cristian', '123456', 'cristian@correo.com', '74236860T', 'hombre', '', '+34 630 276 575', 1);
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin)
values ('santi', '123456', 'santi@correo.com', 74236860T', 'hombre', '', '+34 600 000 000', 1);
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin)
values ('bea', '123456', 'bea@correo.com', 74236860T', 'mujer', '', '+34 600 111 111', 1);
insert into usuarios (nombre, contrasena, email, dni, sexo, direccion, telefono, admin)
values ('jose', '123456', 'jose@correo.com', 74236860T', 'hombre', '', '+34 600 222 222', 0);

insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin)
values (0, 1, '2012/05/12 00:10:00', '2012/05/12 00:11:00');
insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin)
values (0, 1, '2012/05/13 00:11:00', '2012/05/13 00:12:00');
