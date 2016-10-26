--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.1

-- Started on 2016-10-26 14:43:41

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 8 (class 2615 OID 18155)
-- Name: adm_sso; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA adm_sso;


SET search_path = adm_sso, pg_catalog;

--
-- TOC entry 182 (class 1259 OID 18156)
-- Name: modulo_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE modulo_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 183 (class 1259 OID 18158)
-- Name: modulo; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE modulo (
    uid integer DEFAULT nextval('modulo_uid_seq'::regclass) NOT NULL,
    descricao character varying,
    sistema integer
);


--
-- TOC entry 184 (class 1259 OID 18165)
-- Name: perfil; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE perfil (
    uid integer NOT NULL,
    descricao character varying,
    sistema integer
);


--
-- TOC entry 185 (class 1259 OID 18171)
-- Name: perfil_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE perfil_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2184 (class 0 OID 0)
-- Dependencies: 185
-- Name: perfil_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE perfil_uid_seq OWNED BY perfil.uid;


--
-- TOC entry 186 (class 1259 OID 18173)
-- Name: permissao; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE permissao (
    uid integer NOT NULL,
    usuario integer,
    modulo integer,
    permissao integer
);


--
-- TOC entry 187 (class 1259 OID 18176)
-- Name: permissao_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE permissao_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2185 (class 0 OID 0)
-- Dependencies: 187
-- Name: permissao_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE permissao_uid_seq OWNED BY permissao.uid;


--
-- TOC entry 188 (class 1259 OID 18178)
-- Name: privilegio; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE privilegio (
    uid integer NOT NULL,
    modulo integer,
    permissao integer,
    perfil integer
);


--
-- TOC entry 189 (class 1259 OID 18181)
-- Name: privilegio_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE privilegio_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2186 (class 0 OID 0)
-- Dependencies: 189
-- Name: privilegio_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE privilegio_uid_seq OWNED BY privilegio.uid;


--
-- TOC entry 190 (class 1259 OID 18183)
-- Name: role; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE role (
    uid integer NOT NULL,
    usuario integer,
    perfil integer
);


--
-- TOC entry 191 (class 1259 OID 18186)
-- Name: role_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE role_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2187 (class 0 OID 0)
-- Dependencies: 191
-- Name: role_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE role_uid_seq OWNED BY role.uid;


--
-- TOC entry 192 (class 1259 OID 18188)
-- Name: sessions; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE sessions (
    id integer NOT NULL,
    session character varying,
    access integer,
    data text,
    active boolean DEFAULT false,
    ip character varying
);


--
-- TOC entry 193 (class 1259 OID 18195)
-- Name: sessions_id_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE sessions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2188 (class 0 OID 0)
-- Dependencies: 193
-- Name: sessions_id_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE sessions_id_seq OWNED BY sessions.id;


--
-- TOC entry 194 (class 1259 OID 18197)
-- Name: sistema; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE sistema (
    uid integer NOT NULL,
    nome character varying,
    apikey character varying
);


--
-- TOC entry 195 (class 1259 OID 18203)
-- Name: sistema_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE sistema_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2189 (class 0 OID 0)
-- Dependencies: 195
-- Name: sistema_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE sistema_uid_seq OWNED BY sistema.uid;


--
-- TOC entry 196 (class 1259 OID 18205)
-- Name: usuario; Type: TABLE; Schema: adm_sso; Owner: -
--

CREATE TABLE usuario (
    uid integer NOT NULL,
    nome character varying,
    email character varying,
    cpf character varying,
    passwd character varying,
    creation timestamp without time zone,
    update timestamp without time zone,
    token character varying NOT NULL,
    ativo boolean,
    proxy character varying
);


--
-- TOC entry 197 (class 1259 OID 18211)
-- Name: usuario_uid_seq; Type: SEQUENCE; Schema: adm_sso; Owner: -
--

CREATE SEQUENCE usuario_uid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2190 (class 0 OID 0)
-- Dependencies: 197
-- Name: usuario_uid_seq; Type: SEQUENCE OWNED BY; Schema: adm_sso; Owner: -
--

ALTER SEQUENCE usuario_uid_seq OWNED BY usuario.uid;


--
-- TOC entry 2030 (class 2604 OID 18213)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY perfil ALTER COLUMN uid SET DEFAULT nextval('perfil_uid_seq'::regclass);


--
-- TOC entry 2031 (class 2604 OID 18214)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY permissao ALTER COLUMN uid SET DEFAULT nextval('permissao_uid_seq'::regclass);


--
-- TOC entry 2032 (class 2604 OID 18215)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY privilegio ALTER COLUMN uid SET DEFAULT nextval('privilegio_uid_seq'::regclass);


--
-- TOC entry 2033 (class 2604 OID 18216)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY role ALTER COLUMN uid SET DEFAULT nextval('role_uid_seq'::regclass);


--
-- TOC entry 2035 (class 2604 OID 18217)
-- Name: id; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY sessions ALTER COLUMN id SET DEFAULT nextval('sessions_id_seq'::regclass);


--
-- TOC entry 2036 (class 2604 OID 18218)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY sistema ALTER COLUMN uid SET DEFAULT nextval('sistema_uid_seq'::regclass);


--
-- TOC entry 2037 (class 2604 OID 18219)
-- Name: uid; Type: DEFAULT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY usuario ALTER COLUMN uid SET DEFAULT nextval('usuario_uid_seq'::regclass);


--
-- TOC entry 2039 (class 2606 OID 18221)
-- Name: modulo_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY modulo
    ADD CONSTRAINT modulo_pkey PRIMARY KEY (uid);


--
-- TOC entry 2041 (class 2606 OID 18223)
-- Name: perfil_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY perfil
    ADD CONSTRAINT perfil_pkey PRIMARY KEY (uid);


--
-- TOC entry 2043 (class 2606 OID 18225)
-- Name: permissao_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY permissao
    ADD CONSTRAINT permissao_pkey PRIMARY KEY (uid);


--
-- TOC entry 2045 (class 2606 OID 18227)
-- Name: privilegio_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY privilegio
    ADD CONSTRAINT privilegio_pkey PRIMARY KEY (uid);


--
-- TOC entry 2047 (class 2606 OID 18229)
-- Name: role_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (uid);


--
-- TOC entry 2049 (class 2606 OID 18231)
-- Name: sessions_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 2051 (class 2606 OID 18233)
-- Name: sessions_session_key; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_session_key UNIQUE (session);


--
-- TOC entry 2053 (class 2606 OID 18235)
-- Name: sistema_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY sistema
    ADD CONSTRAINT sistema_pkey PRIMARY KEY (uid);


--
-- TOC entry 2055 (class 2606 OID 18237)
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (uid);


--
-- TOC entry 2057 (class 2606 OID 18239)
-- Name: usuario_token_key; Type: CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_token_key UNIQUE (token);


--
-- TOC entry 2058 (class 2606 OID 18240)
-- Name: modulo_sistema_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY modulo
    ADD CONSTRAINT modulo_sistema_fkey FOREIGN KEY (sistema) REFERENCES sistema(uid);


--
-- TOC entry 2059 (class 2606 OID 18245)
-- Name: perfil_sistema_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY perfil
    ADD CONSTRAINT perfil_sistema_fkey FOREIGN KEY (sistema) REFERENCES sistema(uid);


--
-- TOC entry 2060 (class 2606 OID 18250)
-- Name: permissao_modulo_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY permissao
    ADD CONSTRAINT permissao_modulo_fkey FOREIGN KEY (modulo) REFERENCES modulo(uid);


--
-- TOC entry 2061 (class 2606 OID 18255)
-- Name: permissao_usuario_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY permissao
    ADD CONSTRAINT permissao_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuario(uid);


--
-- TOC entry 2062 (class 2606 OID 18260)
-- Name: privilegio_modulo_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY privilegio
    ADD CONSTRAINT privilegio_modulo_fkey FOREIGN KEY (modulo) REFERENCES modulo(uid);


--
-- TOC entry 2063 (class 2606 OID 18265)
-- Name: privilegio_perfil_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY privilegio
    ADD CONSTRAINT privilegio_perfil_fkey FOREIGN KEY (perfil) REFERENCES perfil(uid);


--
-- TOC entry 2064 (class 2606 OID 18270)
-- Name: role_perfil_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_perfil_fkey FOREIGN KEY (perfil) REFERENCES perfil(uid);


--
-- TOC entry 2065 (class 2606 OID 18275)
-- Name: role_usuario_fkey; Type: FK CONSTRAINT; Schema: adm_sso; Owner: -
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_usuario_fkey FOREIGN KEY (usuario) REFERENCES usuario(uid);


-- Completed on 2016-10-26 14:43:41

--
-- PostgreSQL database dump complete
--

