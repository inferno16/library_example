--
-- PostgreSQL database dump
--

-- Dumped from database version 13.1
-- Dumped by pg_dump version 13.0

-- Started on 2020-12-04 15:32:53

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
-- TOC entry 2 (class 3079 OID 16545)
-- Name: citext; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS citext WITH SCHEMA public;


--
-- TOC entry 3083 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION citext; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION citext IS 'data type for case-insensitive character strings';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 202 (class 1259 OID 16652)
-- Name: books; Type: TABLE; Schema: public; Owner: library
--

CREATE TABLE public.books (
    id integer NOT NULL,
    name public.citext NOT NULL,
    author public.citext NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.books OWNER TO library;

--
-- TOC entry 201 (class 1259 OID 16650)
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: library
--

CREATE SEQUENCE public.books_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.books_id_seq OWNER TO library;

--
-- TOC entry 3084 (class 0 OID 0)
-- Dependencies: 201
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: library
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- TOC entry 2942 (class 2604 OID 16655)
-- Name: books id; Type: DEFAULT; Schema: public; Owner: library
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- TOC entry 2945 (class 2606 OID 16663)
-- Name: books books_name_author_key; Type: CONSTRAINT; Schema: public; Owner: library
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_name_author_key UNIQUE (name, author);


--
-- TOC entry 2947 (class 2606 OID 16661)
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: library
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


-- Completed on 2020-12-04 15:32:53

--
-- PostgreSQL database dump complete
--

