drop table if exists accesos;
drop table if exists registro;

drop table if exists catalogos_modelos;
drop table if exists catalogos;

drop table if exists precios;
drop table if exists anos_modelos;

drop table if exists temporadas_modelos;
drop table if exists temporadas;

drop table if exists generos_modelos;
drop table if exists generos;

drop table if exists estados_modelos;
drop table if exists estados;
drop table if exists colores_modelos;
drop table if exists colores;

drop table if exists notas;
drop table if exists fotos;
drop table if exists modelos;

drop table if exists fabricantes_telefonos;
drop table if exists fabricantes;
drop table if exists usuarios;






create table usuarios
(
	id int auto_increment,
	nombre varchar(50) not null,
	contrasena char(40) not null,
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;









create table fabricantes
(
	id int auto_increment,
	nombre varchar(50),
	telefono varchar(500),
	informacion_adicional varchar(5000),
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

insert into fabricantes (nombre, informacion_adicional, fecha_insercion) values ('--NO ESPECIFICADO--', 'Éste es el fabricante ficticio que debe seleccionarse cuando se desconoce el fabricante real o no es muy importante indicar quién es.\n\nNo se puede modificar ni eliminar.', now());

create table fabricantes_telefonos
(
	id int auto_increment,
	id_fabricante int not null,
	telefono varchar(50),
	descripcion varchar(5000),
	fecha_insercion datetime not null,
	primary key (id),
	foreign key (id_fabricante) references fabricantes (id) on delete cascade on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;




























create table modelos
(
	id int auto_increment,
	modelo varchar(50) not null,
	descripcion varchar(5000),
	precio_venta decimal(6,2) not null default -1,
	precio_compra decimal(6,2) not null default -1,
	precio_venta_minorista decimal(6,2) not null default -1,
	primer_ano int(4) not null default -1,
	id_fabricante int not null,
	fecha_insercion datetime not null,
	primary key (id),
	foreign key (id_fabricante) references fabricantes (id) on delete restrict on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table fotos
(
	id int auto_increment,
	id_modelo int not null,
	descripcion varchar(5000),
	fecha_insercion datetime not null,
	primary key (id),
	foreign key (id_modelo) references modelos (id) on delete restrict on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

/*create table notas
(
	id int auto_increment,
	id_modelo int not null,
	id_usuario int,
	texto varchar(5000),
	fecha_insercion datetime not null,
	primary key (id),
	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade,
	foreign key (id_usuario) references usuarios (id) on delete restrict on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;





create table colores
(
	id int auto_increment,
	nombre varchar(50) not null,
	rgb char(6) not null,
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table colores_modelos
(
	id_color int,
	id_modelo int,
	fecha_insercion datetime not null,
	primary key (id_color, id_modelo),
	foreign key (id_color) references colores (id) on delete restrict on update cascade,
	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;






create table estados
(
	id int auto_increment,
	nombre varchar(50) not null,
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table estados_modelos
(
	id_estado int,
	id_modelo int,
	fecha_insercion datetime not null,
	primary key (id_estado, id_modelo),
	foreign key (id_estado) references estados (id) on delete restrict on update cascade,
	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade	 
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;






create table generos
(
	id int auto_increment,
	nombre varchar(50) not null,
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table generos_modelos
(
	id_genero int,
	id_modelo int,
	fecha_insercion datetime not null,
	primary key (id_genero, id_modelo),
	foreign key (id_genero) references generos (id) on delete restrict on update cascade,
	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade 
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;






create table temporadas
(
	id int auto_increment,
	nombre varchar(50) not null,
	fecha_insercion datetime not null,
	primary key (id),
	unique (nombre)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table temporadas_modelos
(
	id_temporada int,
	id_modelo int,
	fecha_insercion datetime not null,
	primary key (id_temporada, id_modelo),
	foreign key (id_temporada) references temporadas (id) on delete restrict on update cascade,
	foreign key (id_modelo) references modelos (id)	on delete cascade on update cascade 
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;*/

/*
--create table anos_modelos
--(
--	ano int(4),
--	id_modelo int,
--	fecha_insercion datetime not null,
--	primary key (id_modelo, ano),
--	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade
--) engine = innodb default charset=utf8 collate=utf8_spanish_ci;
*/

/*
create table precios
(
	id_modelo int,
	precio decimal(6,2) not null,
	descripcion varchar(5000),
	fecha datetime not null,
	fecha_insercion datetime not null,
	primary key (id_modelo, fecha),
	foreign key (id_modelo) references modelos (id)	 on delete cascade on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;
*/

create table catalogos
(
	id int auto_increment,
	titulo varchar(100) not null,
	id_usuario int not null,
	fecha_insercion datetime not null,
	primary key (id),
	foreign key (id_usuario) references usuarios (id) on delete cascade on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table catalogos_modelos
(
	id_catalogo int not null,
	id_modelo int not null,
	fecha_insercion datetime not null,
	primary key (id_catalogo, id_modelo),
	foreign key (id_catalogo) references catalogos (id) on delete cascade on update cascade,
	foreign key (id_modelo) references modelos (id) on delete cascade on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table registro
(
	id int auto_increment,
	fecha datetime not null,
	nombre_usuario varchar(50) not null,
	accion varchar(50) not null,
	detalles varchar(10000) not null,
	ip varchar(56) not null,
	primary key (id),
	foreign key (nombre_usuario) references usuarios (nombre) on delete restrict on update cascade
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;

create table accesos
(
	id int auto_increment,
	fecha datetime not null,
	ip varchar(56) not null,
	exito varchar(50) not null,
	intento varchar(100) not null,
	primary key (id)
) engine = innodb default charset=utf8 collate=utf8_spanish_ci;


insert into usuarios (nombre, contrasena, fecha_insercion) values ('cristian', '7c4a8d09ca3762af61e59520943dc26494f8941b', now());

insert into colores (nombre, rgb, fecha_insercion) values ('negro', '000000', now());
insert into colores (nombre, rgb, fecha_insercion) values ('blanco', 'ff0000', now());
insert into colores (nombre, rgb, fecha_insercion) values ('rojo', 'ff0000', now());
insert into colores (nombre, rgb, fecha_insercion) values ('verde', '00ff00', now());
insert into colores (nombre, rgb, fecha_insercion) values ('azul', '0000ff', now());
insert into colores (nombre, rgb, fecha_insercion) values ('gris', 'aaaaaa', now());

insert into estados (nombre, fecha_insercion) values ('descatalogado', now());
insert into estados (nombre, fecha_insercion) values ('desurtido', now());
insert into estados (nombre, fecha_insercion) values ('stock', now());
insert into estados (nombre, fecha_insercion) values ('pendiente de recibir', now());

insert into fabricantes (nombre, fecha_insercion) values ('paquito', now());
insert into fabricantes (nombre, fecha_insercion) values ('carlos', now());
insert into fabricantes (nombre, fecha_insercion) values ('mistral', now());
insert into fabricantes (nombre, fecha_insercion) values ('antonio méndez', now());

insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (1, '965441622', 'casa', now());
insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (1, '654123312', 'movil', now());
insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (1, '961235412', 'fabrica', now());
insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (2, '612341221', 'movil', now());
insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (2, '6677812', 'movil nuevo', now());
insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion) values (3, '965345331', 'taller', now());

insert into modelos (modelo, descripcion, precio_venta, precio_compra, precio_venta_minorista, id_fabricante, fecha_insercion)
values ('552', 'Zapato caballero piel, Ibérico, desde el nº 39 hasta el 46', 15.95, 15.00, 24.00, 4, now());

insert into modelos (modelo, descripcion, precio_venta, precio_compra, precio_venta_minorista, id_fabricante, fecha_insercion)
values ('502', 'Zapato caballero piel, Ibérico, desde el nº 39 hasta el 46', 15.95, 15.00, 24.00, 4, now());

insert into modelos (modelo, descripcion, precio_venta, precio_compra, precio_venta_minorista, id_fabricante, fecha_insercion)
values ('404', 'Zapato caballero piel, Ibérico, desde el nº 39 hasta el 46', 15.95, 15.00, 24.00, 4, now());

insert into modelos (modelo, descripcion, precio_venta, precio_compra, precio_venta_minorista, id_fabricante, fecha_insercion)
values ('243', 'Zapato señora piel, desde el nº 35 hasta el 41', 16.40, 15.50, 24.00, 1, now());

insert into fotos (id_modelo, descripcion, fecha_insercion) values (2, 'Foto 1', now());
insert into fotos (id_modelo, descripcion, fecha_insercion) values (2, 'Foto 2', now());
insert into fotos (id_modelo, descripcion, fecha_insercion) values (2, 'Foto 3', now());
insert into fotos (id_modelo, descripcion, fecha_insercion) values (2, 'Foto 4', now());
insert into fotos (id_modelo, descripcion, fecha_insercion) values (3, 'Planta', now());
insert into fotos (id_modelo, descripcion, fecha_insercion) values (3, 'Alzado', now());





/*
Disparador en MySQL

create trigger anuncios_a_usuarios
before insert on anuncios
for each row
begin
       declare cantidad int;
       select count(*) into cantidad from usuarios where nombre_usuario = new.usuario;
       if cantidad = 0
       then
                select ERROR_no_existe_el_usuario into cantidad from usuarios;
       end if;
end |
*/

