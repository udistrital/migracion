--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: recursosHumanos; Type: COMMENT; Schema: -; Owner: rhumanos
--

COMMENT ON DATABASE "recursosHumanos" IS 'Sistema Recursos Humanos';


--
-- Name: recursos; Type: SCHEMA; Schema: -; Owner: rhumanos
--

CREATE SCHEMA recursos;


ALTER SCHEMA recursos OWNER TO rhumanos;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: rhumanos_bloque; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_bloque (
    id_bloque integer NOT NULL,
    nombre character(50) NOT NULL,
    descripcion character(255) DEFAULT NULL::bpchar,
    grupo character(200) NOT NULL
);


ALTER TABLE public.rhumanos_bloque OWNER TO rhumanos;

--
-- Name: rhumanos_bloque_id_bloque_seq; Type: SEQUENCE; Schema: public; Owner: rhumanos
--

CREATE SEQUENCE rhumanos_bloque_id_bloque_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rhumanos_bloque_id_bloque_seq OWNER TO rhumanos;

--
-- Name: rhumanos_bloque_id_bloque_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rhumanos
--

ALTER SEQUENCE rhumanos_bloque_id_bloque_seq OWNED BY rhumanos_bloque.id_bloque;


--
-- Name: rhumanos_bloque_id_bloque_seq; Type: SEQUENCE SET; Schema: public; Owner: rhumanos
--

SELECT pg_catalog.setval('rhumanos_bloque_id_bloque_seq', 6, true);


--
-- Name: rhumanos_bloque_pagina; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_bloque_pagina (
    id_pagina integer DEFAULT 0 NOT NULL,
    id_bloque integer DEFAULT 0 NOT NULL,
    seccion character(1) NOT NULL,
    posicion integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.rhumanos_bloque_pagina OWNER TO rhumanos;

--
-- Name: rhumanos_configuracion; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_configuracion (
    id_parametro integer NOT NULL,
    parametro character(255) NOT NULL,
    valor character(255) NOT NULL
);


ALTER TABLE public.rhumanos_configuracion OWNER TO rhumanos;

--
-- Name: rhumanos_configuracion_id_parametro_seq; Type: SEQUENCE; Schema: public; Owner: rhumanos
--

CREATE SEQUENCE rhumanos_configuracion_id_parametro_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rhumanos_configuracion_id_parametro_seq OWNER TO rhumanos;

--
-- Name: rhumanos_configuracion_id_parametro_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rhumanos
--

ALTER SEQUENCE rhumanos_configuracion_id_parametro_seq OWNED BY rhumanos_configuracion.id_parametro;


--
-- Name: rhumanos_configuracion_id_parametro_seq; Type: SEQUENCE SET; Schema: public; Owner: rhumanos
--

SELECT pg_catalog.setval('rhumanos_configuracion_id_parametro_seq', 17, true);


--
-- Name: rhumanos_dbms; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_dbms (
    nombre character(50) NOT NULL,
    dbms character(20) NOT NULL,
    servidor character(50) NOT NULL,
    puerto integer NOT NULL,
    conexionssh character(50) NOT NULL,
    db character(100) NOT NULL,
    usuario character(100) NOT NULL,
    password character(200) NOT NULL
);


ALTER TABLE public.rhumanos_dbms OWNER TO rhumanos;

--
-- Name: rhumanos_estilo; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_estilo (
    usuario character(50) DEFAULT '0'::bpchar NOT NULL,
    estilo character(50) NOT NULL
);


ALTER TABLE public.rhumanos_estilo OWNER TO rhumanos;

--
-- Name: rhumanos_logger; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_logger (
    id_usuario character(5) NOT NULL,
    evento character(255) NOT NULL,
    fecha character(50) NOT NULL
);


ALTER TABLE public.rhumanos_logger OWNER TO rhumanos;

--
-- Name: rhumanos_pagina; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_pagina (
    id_pagina integer NOT NULL,
    nombre character(50) DEFAULT ''::bpchar NOT NULL,
    descripcion character(250) DEFAULT ''::bpchar NOT NULL,
    modulo character(50) DEFAULT ''::bpchar NOT NULL,
    nivel integer DEFAULT 0 NOT NULL,
    parametro character(255) NOT NULL
);


ALTER TABLE public.rhumanos_pagina OWNER TO rhumanos;

--
-- Name: rhumanos_pagina_id_pagina_seq; Type: SEQUENCE; Schema: public; Owner: rhumanos
--

CREATE SEQUENCE rhumanos_pagina_id_pagina_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rhumanos_pagina_id_pagina_seq OWNER TO rhumanos;

--
-- Name: rhumanos_pagina_id_pagina_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rhumanos
--

ALTER SEQUENCE rhumanos_pagina_id_pagina_seq OWNED BY rhumanos_pagina.id_pagina;


--
-- Name: rhumanos_pagina_id_pagina_seq; Type: SEQUENCE SET; Schema: public; Owner: rhumanos
--

SELECT pg_catalog.setval('rhumanos_pagina_id_pagina_seq', 3, true);


--
-- Name: rhumanos_subsistema; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_subsistema (
    id_subsistema integer NOT NULL,
    nombre character varying(250) NOT NULL,
    etiqueta character varying(100) NOT NULL,
    id_pagina integer DEFAULT 0 NOT NULL,
    observacion text
);


ALTER TABLE public.rhumanos_subsistema OWNER TO rhumanos;

--
-- Name: rhumanos_subsistema_id_subsistema_seq; Type: SEQUENCE; Schema: public; Owner: rhumanos
--

CREATE SEQUENCE rhumanos_subsistema_id_subsistema_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rhumanos_subsistema_id_subsistema_seq OWNER TO rhumanos;

--
-- Name: rhumanos_subsistema_id_subsistema_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rhumanos
--

ALTER SEQUENCE rhumanos_subsistema_id_subsistema_seq OWNED BY rhumanos_subsistema.id_subsistema;


--
-- Name: rhumanos_subsistema_id_subsistema_seq; Type: SEQUENCE SET; Schema: public; Owner: rhumanos
--

SELECT pg_catalog.setval('rhumanos_subsistema_id_subsistema_seq', 1, false);


--
-- Name: rhumanos_tempformulario; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_tempformulario (
    id_sesion character(32) NOT NULL,
    formulario character(100) NOT NULL,
    campo character(100) NOT NULL,
    valor text NOT NULL,
    fecha character(50) NOT NULL
);


ALTER TABLE public.rhumanos_tempformulario OWNER TO rhumanos;

--
-- Name: rhumanos_usuario; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_usuario (
    id_usuario integer NOT NULL,
    nombre character varying(50) DEFAULT ''::character varying NOT NULL,
    apellido character varying(50) DEFAULT ''::character varying NOT NULL,
    correo character varying(100) DEFAULT ''::character varying NOT NULL,
    telefono character varying(50) DEFAULT ''::character varying NOT NULL,
    imagen character(255) NOT NULL,
    clave character varying(100) DEFAULT ''::character varying NOT NULL,
    tipo character varying(255) DEFAULT ''::character varying NOT NULL,
    estilo character varying(50) DEFAULT 'basico'::character varying NOT NULL,
    idioma character varying(50) DEFAULT 'es_es'::character varying NOT NULL,
    estado integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.rhumanos_usuario OWNER TO rhumanos;

--
-- Name: rhumanos_usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: rhumanos
--

CREATE SEQUENCE rhumanos_usuario_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rhumanos_usuario_id_usuario_seq OWNER TO rhumanos;

--
-- Name: rhumanos_usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: rhumanos
--

ALTER SEQUENCE rhumanos_usuario_id_usuario_seq OWNED BY rhumanos_usuario.id_usuario;


--
-- Name: rhumanos_usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: rhumanos
--

SELECT pg_catalog.setval('rhumanos_usuario_id_usuario_seq', 1, true);


--
-- Name: rhumanos_usuario_subsistema; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_usuario_subsistema (
    id_usuario integer DEFAULT 0 NOT NULL,
    id_subsistema integer DEFAULT 0 NOT NULL,
    estado integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.rhumanos_usuario_subsistema OWNER TO rhumanos;

--
-- Name: rhumanos_valor_sesion; Type: TABLE; Schema: public; Owner: rhumanos; Tablespace: 
--

CREATE TABLE rhumanos_valor_sesion (
    sesionid character(32) NOT NULL,
    variable character(20) NOT NULL,
    valor character(255) NOT NULL,
    expiracion bigint DEFAULT 0 NOT NULL
);


ALTER TABLE public.rhumanos_valor_sesion OWNER TO rhumanos;

SET search_path = recursos, pg_catalog;

--
-- Name: estado_civil; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE estado_civil (
    id_estado_civil integer NOT NULL,
    desc_estado_civil character varying(80),
    estado character(2)
);


ALTER TABLE recursos.estado_civil OWNER TO rhumanos;

--
-- Name: estado_civil_id_estado_civil_seq; Type: SEQUENCE; Schema: recursos; Owner: rhumanos
--

CREATE SEQUENCE estado_civil_id_estado_civil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recursos.estado_civil_id_estado_civil_seq OWNER TO rhumanos;

--
-- Name: estado_civil_id_estado_civil_seq; Type: SEQUENCE OWNED BY; Schema: recursos; Owner: rhumanos
--

ALTER SEQUENCE estado_civil_id_estado_civil_seq OWNED BY estado_civil.id_estado_civil;


--
-- Name: estado_civil_id_estado_civil_seq; Type: SEQUENCE SET; Schema: recursos; Owner: rhumanos
--

SELECT pg_catalog.setval('estado_civil_id_estado_civil_seq', 5, true);


--
-- Name: funcionario; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE funcionario (
    id_persona bigint NOT NULL,
    id_regimen integer,
    id_cargo integer,
    id_dependencia integer,
    fecha_ingreso date,
    fecha_retiro date,
    correo_institucional character varying(150),
    estado character(2)
);


ALTER TABLE recursos.funcionario OWNER TO rhumanos;

--
-- Name: pais; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE pais (
    paiscodigo character(3) DEFAULT ''::bpchar NOT NULL,
    paisnombre character(80) DEFAULT ''::bpchar NOT NULL
);


ALTER TABLE recursos.pais OWNER TO rhumanos;

--
-- Name: persona; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE persona (
    id_persona integer NOT NULL,
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
    estado character(2)
);


ALTER TABLE recursos.persona OWNER TO rhumanos;

--
-- Name: persona_id_persona_seq; Type: SEQUENCE; Schema: recursos; Owner: rhumanos
--

CREATE SEQUENCE persona_id_persona_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recursos.persona_id_persona_seq OWNER TO rhumanos;

--
-- Name: persona_id_persona_seq; Type: SEQUENCE OWNED BY; Schema: recursos; Owner: rhumanos
--

ALTER SEQUENCE persona_id_persona_seq OWNED BY persona.id_persona;


--
-- Name: persona_id_persona_seq; Type: SEQUENCE SET; Schema: recursos; Owner: rhumanos
--

SELECT pg_catalog.setval('persona_id_persona_seq', 4, true);


--
-- Name: sexo; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE sexo (
    id_sexo integer NOT NULL,
    desc_sexo character varying(50),
    estado character(2)
);


ALTER TABLE recursos.sexo OWNER TO rhumanos;

--
-- Name: sexo_id_sexo_seq; Type: SEQUENCE; Schema: recursos; Owner: rhumanos
--

CREATE SEQUENCE sexo_id_sexo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recursos.sexo_id_sexo_seq OWNER TO rhumanos;

--
-- Name: sexo_id_sexo_seq; Type: SEQUENCE OWNED BY; Schema: recursos; Owner: rhumanos
--

ALTER SEQUENCE sexo_id_sexo_seq OWNED BY sexo.id_sexo;


--
-- Name: sexo_id_sexo_seq; Type: SEQUENCE SET; Schema: recursos; Owner: rhumanos
--

SELECT pg_catalog.setval('sexo_id_sexo_seq', 2, true);


--
-- Name: tipo_identificacion; Type: TABLE; Schema: recursos; Owner: rhumanos; Tablespace: 
--

CREATE TABLE tipo_identificacion (
    id_tipo integer NOT NULL,
    codi_tipo character(2),
    nombre_tipo character varying(150),
    estado character(2)
);


ALTER TABLE recursos.tipo_identificacion OWNER TO rhumanos;

--
-- Name: tipo_identificacion_id_tipo_seq; Type: SEQUENCE; Schema: recursos; Owner: rhumanos
--

CREATE SEQUENCE tipo_identificacion_id_tipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recursos.tipo_identificacion_id_tipo_seq OWNER TO rhumanos;

--
-- Name: tipo_identificacion_id_tipo_seq; Type: SEQUENCE OWNED BY; Schema: recursos; Owner: rhumanos
--

ALTER SEQUENCE tipo_identificacion_id_tipo_seq OWNED BY tipo_identificacion.id_tipo;


--
-- Name: tipo_identificacion_id_tipo_seq; Type: SEQUENCE SET; Schema: recursos; Owner: rhumanos
--

SELECT pg_catalog.setval('tipo_identificacion_id_tipo_seq', 4, true);


SET search_path = public, pg_catalog;

--
-- Name: id_bloque; Type: DEFAULT; Schema: public; Owner: rhumanos
--

ALTER TABLE ONLY rhumanos_bloque ALTER COLUMN id_bloque SET DEFAULT nextval('rhumanos_bloque_id_bloque_seq'::regclass);


--
-- Name: id_parametro; Type: DEFAULT; Schema: public; Owner: rhumanos
--

ALTER TABLE ONLY rhumanos_configuracion ALTER COLUMN id_parametro SET DEFAULT nextval('rhumanos_configuracion_id_parametro_seq'::regclass);


--
-- Name: id_pagina; Type: DEFAULT; Schema: public; Owner: rhumanos
--

ALTER TABLE ONLY rhumanos_pagina ALTER COLUMN id_pagina SET DEFAULT nextval('rhumanos_pagina_id_pagina_seq'::regclass);


--
-- Name: id_subsistema; Type: DEFAULT; Schema: public; Owner: rhumanos
--

ALTER TABLE ONLY rhumanos_subsistema ALTER COLUMN id_subsistema SET DEFAULT nextval('rhumanos_subsistema_id_subsistema_seq'::regclass);


--
-- Name: id_usuario; Type: DEFAULT; Schema: public; Owner: rhumanos
--

ALTER TABLE ONLY rhumanos_usuario ALTER COLUMN id_usuario SET DEFAULT nextval('rhumanos_usuario_id_usuario_seq'::regclass);


SET search_path = recursos, pg_catalog;

--
-- Name: id_estado_civil; Type: DEFAULT; Schema: recursos; Owner: rhumanos
--

ALTER TABLE ONLY estado_civil ALTER COLUMN id_estado_civil SET DEFAULT nextval('estado_civil_id_estado_civil_seq'::regclass);


--
-- Name: id_persona; Type: DEFAULT; Schema: recursos; Owner: rhumanos
--

ALTER TABLE ONLY persona ALTER COLUMN id_persona SET DEFAULT nextval('persona_id_persona_seq'::regclass);


--
-- Name: id_sexo; Type: DEFAULT; Schema: recursos; Owner: rhumanos
--

ALTER TABLE ONLY sexo ALTER COLUMN id_sexo SET DEFAULT nextval('sexo_id_sexo_seq'::regclass);


--
-- Name: id_tipo; Type: DEFAULT; Schema: recursos; Owner: rhumanos
--

ALTER TABLE ONLY tipo_identificacion ALTER COLUMN id_tipo SET DEFAULT nextval('tipo_identificacion_id_tipo_seq'::regclass);


SET search_path = public, pg_catalog;

--
-- Data for Name: rhumanos_bloque; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_bloque (id_bloque, nombre, descripcion, grupo) FROM stdin;
1	login                                             	Login Principal                                                                                                                                                                                                                                                	registro                                                                                                                                                                                                
2	banner                                            	Banner Aplicativo                                                                                                                                                                                                                                              	gui                                                                                                                                                                                                     
3	pie                                               	Pie de pagina                                                                                                                                                                                                                                                  	gui                                                                                                                                                                                                     
4	slider                                            	Slider                                                                                                                                                                                                                                                         	gui                                                                                                                                                                                                     
5	menu                                              	Menu del aplicativo                                                                                                                                                                                                                                            	recursos                                                                                                                                                                                                
6	registrarFuncionario                              	Bloque que permite registrar informacion de los funcionarios                                                                                                                                                                                                   	recursos                                                                                                                                                                                                
\.


--
-- Data for Name: rhumanos_bloque_pagina; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_bloque_pagina (id_pagina, id_bloque, seccion, posicion) FROM stdin;
1	1	C	1
2	2	A	1
2	5	A	2
2	4	C	1
2	3	E	1
3	2	A	1
3	5	A	2
3	6	C	1
3	3	E	1
1	1	C	1
2	2	A	1
2	5	A	2
2	4	C	1
2	3	E	1
3	2	A	1
3	5	A	2
3	6	C	1
3	3	E	1
\.


--
-- Data for Name: rhumanos_configuracion; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_configuracion (id_parametro, parametro, valor) FROM stdin;
1	prefijo                                                                                                                                                                                                                                                        	rhumanos_                                                                                                                                                                                                                                                      
2	nombreAplicativo                                                                                                                                                                                                                                               	Sistema de Información de Recursos Humanos                                                                                                                                                                                                                     
6	nombreAdministrador                                                                                                                                                                                                                                            	administrador                                                                                                                                                                                                                                                  
7	claveAdministrador                                                                                                                                                                                                                                             	KQLVb5lE6lNhHNxg8RiZ91h8tw                                                                                                                                                                                                                                     
8	correoAdministrador                                                                                                                                                                                                                                            	esanchez1988@gmail.com                                                                                                                                                                                                                                         
9	enlace                                                                                                                                                                                                                                                         	data                                                                                                                                                                                                                                                           
10	googlemaps                                                                                                                                                                                                                                                     	                                                                                                                                                                                                                                                               
11	recatchapublica                                                                                                                                                                                                                                                	                                                                                                                                                                                                                                                               
12	recatchaprivada                                                                                                                                                                                                                                                	                                                                                                                                                                                                                                                               
13	expiracion                                                                                                                                                                                                                                                     	54                                                                                                                                                                                                                                                             
14	instalado                                                                                                                                                                                                                                                      	true                                                                                                                                                                                                                                                           
4	host                                                                                                                                                                                                                                                           	http://10.20.0.38                                                                                                                                                                                                                                              
5	site                                                                                                                                                                                                                                                           	/recursosHumanos                                                                                                                                                                                                                                               
15	debugMode                                                                                                                                                                                                                                                      	false                                                                                                                                                                                                                                                          
16	dbPrincipal                                                                                                                                                                                                                                                    	recursosHumanos                                                                                                                                                                                                                                                
3	raizDocumento                                                                                                                                                                                                                                                  	/usr/local/apache/htdocs/recursosHumanos                                                                                                                                                                                                                       
17	hostSeguro                                                                                                                                                                                                                                                     	https://10.20.0.38                                                                                                                                                                                                                                             
\.


--
-- Data for Name: rhumanos_dbms; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_dbms (nombre, dbms, servidor, puerto, conexionssh, db, usuario, password) FROM stdin;
estructura                                        	pgsql               	10.20.0.38                                        	5432	                                                  	recursosHumanos                                                                                     	rhumanos                                                                                            	uwKfsZlE6lMsWTJFz8RGukLZVZamAuMT                                                                                                                                                                        
estructura                                        	pgsql               	10.20.0.38                                        	5432	                                                  	recursosHumanos                                                                                     	rhumanos                                                                                            	uwKfsZlE6lMsWTJFz8RGukLZVZamAuMT                                                                                                                                                                        
\.


--
-- Data for Name: rhumanos_estilo; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_estilo (usuario, estilo) FROM stdin;
\.


--
-- Data for Name: rhumanos_logger; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_logger (id_usuario, evento, fecha) FROM stdin;
\.


--
-- Data for Name: rhumanos_pagina; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_pagina (id_pagina, nombre, descripcion, modulo, nivel, parametro) FROM stdin;
1	index                                             	Pagina Principal recursos humanos                                                                                                                                                                                                                         	Principal                                         	0	jquery=true                                                                                                                                                                                                                                                    
2	indexAdministrador                                	Pagina Principal Administrador                                                                                                                                                                                                                            	Administrador                                     	1	jquery=true                                                                                                                                                                                                                                                    
3	crearFuncionario                                  	Pagina que permite crear funcionarios                                                                                                                                                                                                                     	Administrador                                     	1	jquery=true                                                                                                                                                                                                                                                    
\.


--
-- Data for Name: rhumanos_subsistema; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_subsistema (id_subsistema, nombre, etiqueta, id_pagina, observacion) FROM stdin;
\.


--
-- Data for Name: rhumanos_tempformulario; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_tempformulario (id_sesion, formulario, campo, valor, fecha) FROM stdin;
\.


--
-- Data for Name: rhumanos_usuario; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_usuario (id_usuario, nombre, apellido, correo, telefono, imagen, clave, tipo, estilo, idioma, estado) FROM stdin;
1100003	Edwin	Sanchez	esanchez1988@gmail.com	3018946	                                                                                                                                                                                                                                                               	5994a993cbde3035b4c9cff8146e0cba62121c3b	1	basico	es_es	1
\.


--
-- Data for Name: rhumanos_usuario_subsistema; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_usuario_subsistema (id_usuario, id_subsistema, estado) FROM stdin;
\.


--
-- Data for Name: rhumanos_valor_sesion; Type: TABLE DATA; Schema: public; Owner: rhumanos
--

COPY rhumanos_valor_sesion (sesionid, variable, valor, expiracion) FROM stdin;
\.


SET search_path = recursos, pg_catalog;

--
-- Data for Name: estado_civil; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY estado_civil (id_estado_civil, desc_estado_civil, estado) FROM stdin;
3	Union Libre	AC
5	Viudo(a)	AC
4	Divorsiado(a)	AC
2	Casado(a)	AC
1	Soltero(a)	AC
\.


--
-- Data for Name: funcionario; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY funcionario (id_persona, id_regimen, id_cargo, id_dependencia, fecha_ingreso, fecha_retiro, correo_institucional, estado) FROM stdin;
1	1	14	25	2013-06-05	\N	esanchez1988@gmail.com	AC
2	1	17	25	2009-08-13	\N	paulo_cesar@udistrital.edu.co	AC
\.


--
-- Data for Name: pais; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY pais (paiscodigo, paisnombre) FROM stdin;
\.


--
-- Data for Name: persona; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY persona (id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, direccion, ciudad, telefono, celular, correo, estado) FROM stdin;
1	3645	1	1022348774	Edwin	Mauricio	Sanchez	Cespedes	1988-05-12	2257	1	3	Calle 49 sur 87A 04	2257	3018946	3006411061	esanchez1988@gmail.com	AC
2	3675	1	79708124	Paulo	Cesar	Coronado	Sanchez	1974-06-20	2257	1	3	Calle 46B Sur 84 81	2257	3534543	3172342223	pcoronado@gmail.com	AC
3	3578	1	79323149	Luis	Eduardo	Perez	\N	1964-09-25	2257	1	3	Calle 97 # 87 02	2257	2568741	3002487745	lsanchez@gmail.com	AC
4	2587	1	39638551	Pedro	Antonio	Lopez	Rodriguez	1955-05-23	2257	1	3	Calle 7 # 105-34 	2257	3215681	3216548741	plopez@gmail.com	AC
\.


--
-- Data for Name: sexo; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY sexo (id_sexo, desc_sexo, estado) FROM stdin;
1	Masculino	AC
2	Femenino	AC
\.


--
-- Data for Name: tipo_identificacion; Type: TABLE DATA; Schema: recursos; Owner: rhumanos
--

COPY tipo_identificacion (id_tipo, codi_tipo, nombre_tipo, estado) FROM stdin;
1	CC	Cedula de Ciudadania	AC
2	NI	Nit	AC
3	PA	Pasaporte	AC
4	CE	Cedula de Extranjeria	AC
\.


SET search_path = public, pg_catalog;

--
-- Name: rhumanos_bloque_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_bloque
    ADD CONSTRAINT rhumanos_bloque_pkey PRIMARY KEY (id_bloque);


--
-- Name: rhumanos_configuracion_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_configuracion
    ADD CONSTRAINT rhumanos_configuracion_pkey PRIMARY KEY (id_parametro);


--
-- Name: rhumanos_estilo_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_estilo
    ADD CONSTRAINT rhumanos_estilo_pkey PRIMARY KEY (usuario, estilo);


--
-- Name: rhumanos_pagina_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_pagina
    ADD CONSTRAINT rhumanos_pagina_pkey PRIMARY KEY (id_pagina);


--
-- Name: rhumanos_subsistema_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_subsistema
    ADD CONSTRAINT rhumanos_subsistema_pkey PRIMARY KEY (id_subsistema);


--
-- Name: rhumanos_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_usuario
    ADD CONSTRAINT rhumanos_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: rhumanos_valor_sesion_pkey; Type: CONSTRAINT; Schema: public; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY rhumanos_valor_sesion
    ADD CONSTRAINT rhumanos_valor_sesion_pkey PRIMARY KEY (sesionid, variable);


SET search_path = recursos, pg_catalog;

--
-- Name: pais_pkey; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY pais
    ADD CONSTRAINT pais_pkey PRIMARY KEY (paiscodigo);


--
-- Name: pk_estado_civil; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY estado_civil
    ADD CONSTRAINT pk_estado_civil PRIMARY KEY (id_estado_civil);


--
-- Name: pk_funcionario; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY funcionario
    ADD CONSTRAINT pk_funcionario PRIMARY KEY (id_persona);


--
-- Name: pk_persona; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT pk_persona PRIMARY KEY (id_persona);


--
-- Name: pk_sexo; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY sexo
    ADD CONSTRAINT pk_sexo PRIMARY KEY (id_sexo);


--
-- Name: pk_tipo_identificacion; Type: CONSTRAINT; Schema: recursos; Owner: rhumanos; Tablespace: 
--

ALTER TABLE ONLY tipo_identificacion
    ADD CONSTRAINT pk_tipo_identificacion PRIMARY KEY (id_tipo);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET search_path = public, pg_catalog;

--
-- Name: rhumanos_bloque_pagina; Type: ACL; Schema: public; Owner: rhumanos
--

REVOKE ALL ON TABLE rhumanos_bloque_pagina FROM PUBLIC;
REVOKE ALL ON TABLE rhumanos_bloque_pagina FROM rhumanos;
GRANT ALL ON TABLE rhumanos_bloque_pagina TO rhumanos;
GRANT ALL ON TABLE rhumanos_bloque_pagina TO PUBLIC;


--
-- PostgreSQL database dump complete
--

