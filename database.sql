/**
 * Author:  Ivan CG
 * Created: 14/06/2019
 */

USE bdgilga;

CREATE TABLE IF NOT EXISTS users(
    id int(255) auto_increment not null,
    role varchar(20),
    name varchar(100),
    surname varchar(200),
    nick varchar(100),
    email varchar(255),
    password varchar(255),
    image varchar(255),
    created_at datetime,
    updated_at datetime,
    remember_token varchar(255),
    CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

/*insert into users 
values (null,'user','ivan','calzadas','ivan25gtr','ivan25gtr','12345',null,CURTIME(),CURTIME(),null); 

insert into users 
values (null,'user','ivan2','calzadas','ivan25gtr','ivan25gtr','12345',null,CURTIME(),CURTIME(),null); 

insert into users 
values (null,'user','ivan3','calzadas','ivan25gtr','ivan25gtr','12345',null,CURTIME(),CURTIME(),null); */

CREATE TABLE IF NOT EXISTS incidencias(
    id int(255) auto_increment not null,
    id_usuario int(255) not null,
    created_at datetime,
    updated_at datetime,
    folio varchar(10) not null,
    estacion varchar(10) not null,
    fecha_incidencia datetime not null,
    id_area_estacion int(255) not null,
    id_equipo int(255) not null,
    asunto varchar(50) not null,
    descripcion varchar(255) not null,
    id_area_atencion int(255) not null,
    foto_ruta varchar(255),
    estatus_incidencia varchar(10) not null,
    tipo_solicitud varchar(15) not null,--incidencia o requerimiento
    prioridad varchar(10) not null, --alta,media,baja
    CONSTRAINT pk_incidencias PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS detalle_incidencias(
    id int(255) auto_increment not null,
    id_incidencia int(255) not null,
    id_usuario int(255) not null,
    created_at datetime,
    updated_at datetime,
    fecha_detalle_incidencia datetime not null,
    comentarios varchar(255) not null,
    foto_ruta varchar(255),
    estatus varchar(10) not null,
    CONSTRAINT pk_detalle_incidencias PRIMARY KEY(id)    
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS companias(
    id_usuario int(255) not null,
    created_at datetime,
    updated_at datetime,
    id int(255) auto_increment not null,
    razon_social varchar(50) not null,
    CONSTRAINT pk_companias PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE IF NOT EXISTS estaciones(
    id_usuario int(255) not null,
    created_at datetime,
    updated_at datetime,
    estacion varchar(10) not null,
    id_compania int(255) not null,
    nombre_corto varchar(30),
    direccion varchar(255),
    permiso_expedido varchar(30),
    CONSTRAINT pk_estaciones PRIMARY KEY(estacion)	
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS usuarios_estaciones(
    id_usuario int(255) not null,
    created_at datetime,
    updated_at datetime,
    id_usuario_permiso int(255) not null,
    estacion varchar(10) not null,
    CONSTRAINT usuarios_estaciones PRIMARY KEY(id_usuario_permiso,estacion)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS areas_estacion(
    id int(255) auto_increment not null,
    estacion varchar(10) not null,
    descripcion varchar(50) not null,
    CONSTRAINT pk_areas_estacion PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS areas_atencion(
    id int(255) auto_increment not null,
    descripcion varchar(30) not null,
    CONSTRAINT pk_areas_atencion PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS equipos(
    id int(255) auto_increment not null,    
    estacion varchar(10) not null,
    descripcion varchar(100) not null,
    marca varchar(50),
    modelo varchar(50),
    serie varchar(50),
    id_area_estacion int(255) not null,
    id_area_atencion int(255) not null,
    CONSTRAINT pk_equipos PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS encargados_areas_atencion(
    id int(255) auto_increment not null,
    id_usuario int(255) not null,
    id_area_atencion int(255) not null,
    CONSTRAINT pk_encargados_areas_atencion PRIMARY KEY(id)
)ENGINE=InnoDb;