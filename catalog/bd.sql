drop table if exists pedidos_linea;
drop table if exists pedidos;
drop table if exists usuarios;
drop table if exists fotos;
drop table if exists categorias_modelos;
drop table if exists categorias;
drop table if exists modelos;
drop table if exists marcas;
drop table if exists fabricantes;
drop table if exists accesos;

create table accesos
(
    id int not null auto_increment,
    ip char(15) not null,
    fecha datetime not null,
    id_usuario int not null,
    exito tinyint(1) not null default 0,
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table fabricantes
(
    id int not null auto_increment,
    nombre varchar(100) not null,
    telefono varchar(100) not null default '',
    descripcion text not null default '',
    email varchar(256) not null default '',
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table marcas
(
    id int not null auto_increment,
    nombre varchar(100) not null,
    logo varchar(256) not null default '',
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table modelos
(
    id int not null auto_increment,
    id_fabricante int not null,
    id_marca int not null,
    referencia varchar(100) not null,
    nombre varchar(100) not null,
    talla_menor int not null default 0,
    talla_mayor int not null default 0,
    descripcion text not null default '',
    precio decimal(10,2) not null,
    precio_oferta decimal(10,2) not null,
    oferta tinyint(1) not null default 0,
    prioridad int not null default 5,
    descatalogado tinyint(1) not null default 0,
    foto varchar(256) not null default '',
    fecha_creacion datetime not null,
    fecha_modificacion datetime not null,
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table categorias
(
    id int not null auto_increment,
    id_padre int not null default 0,
    nombre varchar(100) not null,
    mostrar tinyint(1) not null default 1,
    zindex int not null default 0,
    descripcion text not null default '',
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table categorias_modelos
(
    id_categoria int not null,
    id_modelo int not null,
    primary key (id_categoria, id_modelo)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table usuarios
(
    id int not null auto_increment,
    email varchar(256) not null,
    contrasena char(128) not null,
    nombre varchar(100) not null default '',
    telefono varchar(100) not null default '',
    direccion text not null default '',
    cif varchar(100) not null default '',
    admin tinyint(1) not null default 0,
    activo tinyint(1) not null default 0,
    fecha_registro datetime not null,
    primary key (id),
    unique (email)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table fotos
(
    id int not null auto_increment,
    id_modelo int not null,
    nombre_original varchar(256) not null,
    fecha_creacion datetime not null,
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table pedidos
(
    id int not null auto_increment,
    id_usuario int not null,
    fecha_creacion datetime not null,
    detalles text not null default '',
    primary key (id)
) engine = myisam default charset=utf8 collate=utf8_general_ci;

create table pedidos_linea
(
    id int not null auto_increment,
    id_pedido int not null,
    id_modelo int not null,
    numero int not null,
    cantidad int not null,
    primary key (id)    
) engine = myisam default charset=utf8 collate=utf8_general_ci;



insert into categorias (id_padre, nombre, mostrar, descripcion) values (0, "Mujer", 1, "Zapatos de mujer");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (0, "Hombre", 1, "Zapatos de hombre");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (0, "Niño y Niña", 1, "Zapatos de niño y niña");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (0, "Tallas Especiales", 1, "Números de tamaño especial tanto de mujer como hombre");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (0, "Ofertas", 1, "Modelos en oferta");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Zapatos", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Botas", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Botas y Botines", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Anchos Especiales", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Fiesta", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Anatómicos", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Sandalias", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (1, "Anchos Especiales", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Zapatos", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Casual", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Suela de Cuero", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Deportivos", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Mocasines", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Clásicos", 1, "");
insert into categorias (id_padre, nombre, mostrar, descripcion) values (2, "Trenzados", 1, "");

insert into fabricantes (nombre, telefono, descripcion, email) values ("Antonio Méndez", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("Par y Medio", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("Carlos Mora", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("José Cutillas", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("Paquito", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("Juan", "", "", "");
insert into fabricantes (nombre, telefono, descripcion, email) values ("Herminio", "", "", "");

insert into marcas (nombre) values ("Agata Shoes");
insert into marcas (nombre) values ("Candy");
insert into marcas (nombre) values ("Ibérico");
insert into marcas (nombre) values ("Anchos Calzados");
insert into marcas (nombre) values ("JAM");
insert into marcas (nombre) values ("Wheti's");
insert into marcas (nombre) values ("Beppi");
insert into marcas (nombre) values ("Annora");
insert into marcas (nombre) values ("Morxiva");


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

create table intentos
(
    ip varchar(50) not null,
    fecha datetime not null
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

insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, fecha_realizacion, reservable, notas)
values (1, 1, '2012/05/14 10:30:00', '2012/05/14 12:00:00', now(), 1, '');
insert into reservas (id_usuario, id_pista, fecha_inicio, fecha_fin, fecha_realizacion, reservable, notas)
values (1, 1, '2012/05/13 12:00:00', '2012/05/13 14:30:00', now(), 1, '');

