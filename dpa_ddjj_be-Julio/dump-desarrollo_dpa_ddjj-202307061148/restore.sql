--
-- NOTE:
--
-- File paths need to be edited. Search for $$PATH$$ and
-- replace it with the path to the directory containing
-- the extracted data files.
--
--
-- PostgreSQL database dump
--

-- Dumped from database version 12.3
-- Dumped by pg_dump version 12.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE desarrollo_dpa_ddjj;
--
-- Name: desarrollo_dpa_ddjj; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE desarrollo_dpa_ddjj WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'Spanish_Bolivia.1252' LC_CTYPE = 'Spanish_Bolivia.1252';


\connect desarrollo_dpa_ddjj

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: insert_new_usuario(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.insert_new_usuario(apellido_paterno character varying DEFAULT NULL::character varying, apellido_materno character varying DEFAULT NULL::character varying, primer_nombre character varying DEFAULT NULL::character varying, segundo_nombre character varying DEFAULT NULL::character varying, email character varying DEFAULT NULL::character varying, carnet_identidad character varying DEFAULT NULL::character varying, pwd character varying DEFAULT NULL::character varying, tk character varying DEFAULT NULL::character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare response int default 0;
	begin
		if pwd is not null and tk is not null then
			insert into public.usuario(paterno,materno,nombre1,nombre2,email,ci,"password","token")values(apellido_paterno,apellido_materno,primer_nombre,segundo_nombre,email,carnet_identidad,pwd,tk);
			response = 1;
		end if;
		return response;
	end;
$$;


--
-- Name: verify_user_login(character varying, character varying); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.verify_user_login(email_input character varying DEFAULT NULL::character varying, passwd_input character varying DEFAULT NULL::character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $$
	declare counter_user int;
	declare response int default 0;
	begin
		if email_input is not null and passwd_input is not null then
			select count(*) into counter_user from public.usuario where email=email_input and "password"=passwd_input;
			if counter_user = 1 then 
				response = 1;
			end if;
		else
		    response = -1;
		end if;
		return response;
	END;
$$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_item (
    name character varying(64) NOT NULL,
    type smallint NOT NULL,
    description text,
    rule_name character varying(64),
    data bytea,
    created_at integer,
    updated_at integer
);


--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.auth_rule (
    name character varying(64) NOT NULL,
    data bytea,
    created_at integer,
    updated_at integer
);


--
-- Name: categoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categoria (
    id_categoria integer NOT NULL,
    nombre character varying NOT NULL
);


--
-- Name: categoria_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categoria_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categoria_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categoria_id_categoria_seq OWNED BY public.categoria.id_categoria;


--
-- Name: declaracion_jurada; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.declaracion_jurada (
    id_declaracion_jurada integer NOT NULL,
    fecha date NOT NULL,
    mes character varying(20) NOT NULL,
    gestion character varying(4) NOT NULL,
    fk_usuario integer NOT NULL,
    estado character varying(3) NOT NULL
);


--
-- Name: COLUMN declaracion_jurada.estado; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.declaracion_jurada.estado IS '''PEN'' = pendiente; ''DEC''=declarado';


--
-- Name: declaracion_jurada_categoria; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.declaracion_jurada_categoria (
    id_declaracion_jurada integer NOT NULL,
    id_categoria integer NOT NULL,
    id integer NOT NULL,
    remuneracion real NOT NULL
);


--
-- Name: declaracion_jurada_id_declaracion_jurada_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.declaracion_jurada_id_declaracion_jurada_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: declaracion_jurada_id_declaracion_jurada_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.declaracion_jurada_id_declaracion_jurada_seq OWNED BY public.declaracion_jurada.id_declaracion_jurada;


--
-- Name: declaracion_jurada_institucion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.declaracion_jurada_institucion (
    id integer NOT NULL,
    id_declaracion_jurada integer NOT NULL,
    id_institucion integer NOT NULL,
    total_ganado real NOT NULL,
    cargo character varying NOT NULL,
    tipo character varying(1) NOT NULL
);


--
-- Name: institucion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.institucion (
    id_institucion integer NOT NULL,
    nombre character varying NOT NULL
);


--
-- Name: institucion_id_institucion_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.institucion_id_institucion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: institucion_id_institucion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.institucion_id_institucion_seq OWNED BY public.institucion.id_institucion;


--
-- Name: migration; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    paterno character varying,
    materno character varying,
    nombre1 character varying,
    nombre2 character varying,
    email character varying,
    ci character varying,
    password character varying NOT NULL,
    token character varying NOT NULL
);


--
-- Name: usuario_categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_categoria_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_categoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_categoria_id_seq OWNED BY public.declaracion_jurada_categoria.id;


--
-- Name: usuario_institucion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuario_institucion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuario_institucion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuario_institucion_id_seq OWNED BY public.declaracion_jurada_institucion.id;


--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuarios_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuarios_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- Name: categoria id_categoria; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.categoria_id_categoria_seq'::regclass);


--
-- Name: declaracion_jurada id_declaracion_jurada; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada ALTER COLUMN id_declaracion_jurada SET DEFAULT nextval('public.declaracion_jurada_id_declaracion_jurada_seq'::regclass);


--
-- Name: declaracion_jurada_categoria id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_categoria ALTER COLUMN id SET DEFAULT nextval('public.usuario_categoria_id_seq'::regclass);


--
-- Name: declaracion_jurada_institucion id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_institucion ALTER COLUMN id SET DEFAULT nextval('public.usuario_institucion_id_seq'::regclass);


--
-- Name: institucion id_institucion; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.institucion ALTER COLUMN id_institucion SET DEFAULT nextval('public.institucion_id_institucion_seq'::regclass);


--
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuarios_id_usuario_seq'::regclass);


--
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2926.dat

--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2924.dat

--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2925.dat

--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2923.dat

--
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2910.dat

--
-- Data for Name: declaracion_jurada; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2912.dat

--
-- Data for Name: declaracion_jurada_categoria; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2913.dat

--
-- Data for Name: declaracion_jurada_institucion; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2915.dat

--
-- Data for Name: institucion; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2916.dat

--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2918.dat

--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: -
--

\i $$PATH$$/2919.dat

--
-- Name: categoria_id_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categoria_id_categoria_seq', 22, true);


--
-- Name: declaracion_jurada_id_declaracion_jurada_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.declaracion_jurada_id_declaracion_jurada_seq', 9, true);


--
-- Name: institucion_id_institucion_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.institucion_id_institucion_seq', 127, true);


--
-- Name: usuario_categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_categoria_id_seq', 425, true);


--
-- Name: usuario_institucion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuario_institucion_id_seq', 247, true);


--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuarios_id_usuario_seq', 52, true);


--
-- Name: auth_assignment auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: categoria categoria_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pk PRIMARY KEY (id_categoria);


--
-- Name: declaracion_jurada_categoria declaracion_jurada_categoria_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_categoria
    ADD CONSTRAINT declaracion_jurada_categoria_pk PRIMARY KEY (id);


--
-- Name: declaracion_jurada_institucion declaracion_jurada_institucion_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_institucion
    ADD CONSTRAINT declaracion_jurada_institucion_pk PRIMARY KEY (id);


--
-- Name: declaracion_jurada declaracion_jurada_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada
    ADD CONSTRAINT declaracion_jurada_pk PRIMARY KEY (id_declaracion_jurada);


--
-- Name: institucion institucion_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.institucion
    ADD CONSTRAINT institucion_pk PRIMARY KEY (id_institucion);


--
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: usuario usuarios_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuarios_pk PRIMARY KEY (id_usuario);


--
-- Name: idx-auth_assignment-user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx-auth_assignment-user_id" ON public.auth_assignment USING btree (user_id);


--
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX "idx-auth_item-type" ON public.auth_item USING btree (type);


--
-- Name: auth_assignment auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES public.auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES public.auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: declaracion_jurada_categoria declaracion_jurada_categoria_categoria_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_categoria
    ADD CONSTRAINT declaracion_jurada_categoria_categoria_fk FOREIGN KEY (id_categoria) REFERENCES public.categoria(id_categoria);


--
-- Name: declaracion_jurada_categoria declaracion_jurada_categoria_declaracion_jurada_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_categoria
    ADD CONSTRAINT declaracion_jurada_categoria_declaracion_jurada_fk FOREIGN KEY (id_declaracion_jurada) REFERENCES public.declaracion_jurada(id_declaracion_jurada);


--
-- Name: declaracion_jurada_institucion declaracion_jurada_institucion_declaracion_jurada_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_institucion
    ADD CONSTRAINT declaracion_jurada_institucion_declaracion_jurada_fk FOREIGN KEY (id_declaracion_jurada) REFERENCES public.declaracion_jurada(id_declaracion_jurada);


--
-- Name: declaracion_jurada_institucion declaracion_jurada_institucion_institucion_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada_institucion
    ADD CONSTRAINT declaracion_jurada_institucion_institucion_fk FOREIGN KEY (id_institucion) REFERENCES public.institucion(id_institucion);


--
-- Name: declaracion_jurada usuario_declaracion_jurada_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.declaracion_jurada
    ADD CONSTRAINT usuario_declaracion_jurada_fk FOREIGN KEY (fk_usuario) REFERENCES public.usuario(id_usuario);


--
-- PostgreSQL database dump complete
--

