CREATE SCHEMA recursos
  AUTHORIZATION rhumanos;


CREATE TABLE recursos.estado_civil
(
  id_estado_civil serial NOT NULL,
  desc_estado_civil character varying(80),
  estado character(2),
  CONSTRAINT pk_estado_civil PRIMARY KEY (id_estado_civil)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.estado_civil
  OWNER TO rhumanos;

  CREATE TABLE recursos.funcionario
(
  id_persona bigint NOT NULL,
  id_regimen integer,
  id_cargo integer,
  id_dependencia integer,
  fecha_ingreso date,
  fecha_retiro date,
  correo_institucional character varying(150),
  estado character(2),
  CONSTRAINT pk_funcionario PRIMARY KEY (id_persona)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.funcionario
  OWNER TO rhumanos;


CREATE TABLE recursos.pais
(
  paiscodigo character(3) NOT NULL DEFAULT ''::bpchar,
  paisnombre character(80) NOT NULL DEFAULT ''::bpchar,
  CONSTRAINT pais_pkey PRIMARY KEY (paiscodigo)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.pais
  OWNER TO rhumanos;



CREATE TABLE recursos.persona
(
  id_persona serial NOT NULL,
  codigo_interno bigint,
  id_tipo_identificacion integer,
  nume_identificacion bigint,
  primer_nombre character varying(150),
  segundo_nombre character varying(150),
  primer_apellido character varying(150),
  segundo_apellido character varying(150),
  fecha_nacimiento date,
  lugar_nacimiento integer,
  id_sexo integer,
  id_estado_civil integer,
  direccion character varying(200),
  ciudad integer,
  telefono bigint,
  celular bigint,
  correo character varying(200),
  estado character(2),
  CONSTRAINT pk_persona PRIMARY KEY (id_persona)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.persona
  OWNER TO rhumanos;



CREATE TABLE recursos.sexo
(
  id_sexo serial NOT NULL,
  desc_sexo character varying(50),
  estado character(2),
  CONSTRAINT pk_sexo PRIMARY KEY (id_sexo)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.sexo
  OWNER TO rhumanos;


CREATE TABLE recursos.tipo_identificacion
(
  id_tipo serial NOT NULL,
  codi_tipo character(2),
  nombre_tipo character varying(150),
  estado character(2),
  CONSTRAINT pk_tipo_identificacion PRIMARY KEY (id_tipo)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE recursos.tipo_identificacion
  OWNER TO rhumanos;

INSERT INTO recursos.estado_civil(id_estado_civil, desc_estado_civil, estado) VALUES (3, 'Union Libre', 'AC');
INSERT INTO recursos.estado_civil(id_estado_civil, desc_estado_civil, estado) VALUES (5, 'Viudo(a)', 'AC');
INSERT INTO recursos.estado_civil(id_estado_civil, desc_estado_civil, estado) VALUES (4, 'Divorsiado(a)', 'AC');
INSERT INTO recursos.estado_civil(id_estado_civil, desc_estado_civil, estado) VALUES (2, 'Casado(a)', 'AC');	
INSERT INTO recursos.estado_civil(id_estado_civil, desc_estado_civil, estado) VALUES (1, 'Soltero(a)', 'AC');
INSERT INTO recursos.funcionario(id_persona, id_regimen, id_cargo, id_dependencia, fecha_ingreso, fecha_retiro, correo_institucional, estado) VALUES (1, 1, 14, 25, '2013-06-05', null, 'esanchez1988@gmail.com', 'AC');
INSERT INTO recursos.funcionario(id_persona, id_regimen, id_cargo, id_dependencia, fecha_ingreso, fecha_retiro, correo_institucional, estado) VALUES (2, 1, 17, 25, '2009-08-13', null, 'paulo_cesar@udistrital.edu.co', 'AC');
INSERT INTO recursos.persona(id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, direccion, ciudad, telefono, celular, correo, estado) VALUES (1, 3645, 1, 1022348774, 'Edwin', 'Mauricio', 'Sanchez', 'Cespedes', '1988-05-12', 2257, 1, 3, 'Calle 49 sur 87A 04', 2257, 3018946, 3006411061, 'esanchez1988@gmail.com', 'AC');
INSERT INTO recursos.persona(id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, direccion, ciudad, telefono, celular, correo, estado) VALUES (2, 3675, 1, 79708124, 'Paulo', 'Cesar', 'Coronado', 'Sanchez', '1974-06-20', 2257, 1, 3, 'Calle 46B Sur 84 81', 2257, 3534543, 3172342223, 'pcoronado@gmail.com', 'AC');
INSERT INTO recursos.persona(id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, direccion, ciudad, telefono, celular, correo, estado) VALUES (3, 3578, 1, 79323149, 'Luis', 'Eduardo', 'Perez', null, '1964-09-25', 2257, 1, 3, 'Calle 97 # 87 02', 2257, 2568741, 3002487745, 'lsanchez@gmail.com', 'AC');
INSERT INTO recursos.persona(id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, direccion, ciudad, telefono, celular, correo, estado) VALUES (4, 2587, 1, 39638551, 'Pedro', 'Antonio', 'Lopez', 'Rodriguez', '1955-05-23', 2257, 1, 3, 'Calle 7 # 105-34 ', 2257, 3215681, 3216548741, 'plopez@gmail.com', 'AC');
INSERT INTO recursos.sexo(id_sexo, desc_sexo, estado) VALUES (1, 'Masculino', 'AC');	
INSERT INTO recursos.sexo(id_sexo, desc_sexo, estado) VALUES (2, 'Femenino', 'AC');
INSERT INTO recursos.tipo_identificacion(id_tipo, codi_tipo, nombre_tipo, estado) VALUES (1, 'CC', 'Cedula de Ciudadania', 'AC');
INSERT INTO recursos.tipo_identificacion(id_tipo, codi_tipo, nombre_tipo, estado) VALUES (2, 'NI', 'Nit', 'AC');
INSERT INTO recursos.tipo_identificacion(id_tipo, codi_tipo, nombre_tipo, estado) VALUES (3, 'PA', 'Pasaporte', 'AC');
INSERT INTO recursos.tipo_identificacion(id_tipo, codi_tipo, nombre_tipo, estado) VALUES (4, 'CE', 'Cedula de Extranjeria', 'AC');
