--
-- PostgreSQL database dump
--

\restrict qswCUbKqwORCKqeEroNIucEtQnJWBigjAMNrnlt0K7ee6Xxt33ulOjQmNAy89uP

-- Dumped from database version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)
-- Dumped by pg_dump version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)

-- Started on 2026-03-03 15:51:47 +08

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 240 (class 1255 OID 156018)
-- Name: enforce_account_lock(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.enforce_account_lock() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- ADMIN or SUCCESSFUL RESET
    IF NEW.failed_attempts = 0 THEN
        NEW.lock_until := NULL;
        RETURN NEW;
    END IF;

    -- Not extend existing lock
    IF OLD.lock_until IS NOT NULL AND OLD.lock_until > NOW() THEN
        RETURN NEW;
    END IF;

    -- ADMIN: fixed 3-minute lock
    IF TG_TABLE_NAME = 'clinic_administrator'
       AND NEW.failed_attempts >= 3 THEN
        NEW.failed_attempts := 3;
        NEW.lock_until := NOW() + INTERVAL '3 minutes';
        RETURN NEW;
    END IF;

    -- OWNER / VET: admin-only unlock
    IF NEW.failed_attempts >= 3 THEN
        NEW.failed_attempts := 3;
        NEW.lock_until := NULL;
        RETURN NEW;
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.enforce_account_lock() OWNER TO postgres;

--
-- TOC entry 249 (class 1255 OID 164216)
-- Name: enforce_admin_account_lock(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.enforce_admin_account_lock() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Successful login or admin reset
    IF NEW.failed_attempts = 0 THEN
        NEW.lock_until := NULL;
        RETURN NEW;
    END IF;

    -- Do not extend existing lock
    IF OLD.lock_until IS NOT NULL AND OLD.lock_until > NOW() THEN
        RETURN NEW;
    END IF;

    -- Fixed 3-minute lock
    IF NEW.failed_attempts >= 3 THEN
        NEW.failed_attempts := 3;
        NEW.lock_until := NOW() + INTERVAL '3 minutes';
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.enforce_admin_account_lock() OWNER TO postgres;

--
-- TOC entry 247 (class 1255 OID 164213)
-- Name: enforce_user_account_lock(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.enforce_user_account_lock() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Admin unlock or successful login
    IF NEW.failed_attempts = 0 THEN
        RETURN NEW;
    END IF;

    -- Lock owner / vet at 3 attempts (admin unlock only)
    IF NEW.failed_attempts >= 3 THEN
        NEW.failed_attempts := 3;
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.enforce_user_account_lock() OWNER TO postgres;

--
-- TOC entry 235 (class 1255 OID 164226)
-- Name: gen_audit_id(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.gen_audit_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.audit_id := 'AU' || LPAD(
        COALESCE(
            (SELECT MAX(CAST(SUBSTRING(audit_id, 3) AS INTEGER)) FROM audit_log),
            0
        ) + 1,
        5,
        '0'
    );
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.gen_audit_id() OWNER TO postgres;

--
-- TOC entry 232 (class 1255 OID 139647)
-- Name: generate_owner_id(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.generate_owner_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.owner_id := 'OW' || LPAD(nextval('owner_seq')::text, 3, '0');
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.generate_owner_id() OWNER TO postgres;

--
-- TOC entry 234 (class 1255 OID 139654)
-- Name: generate_pet_id(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.generate_pet_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.pet_id := 'P' || LPAD(nextval('pet_seq')::text, 4, '0');
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.generate_pet_id() OWNER TO postgres;

--
-- TOC entry 233 (class 1255 OID 139651)
-- Name: generate_vet_id(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.generate_vet_id() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.vet_id := 'VT' || LPAD(nextval('vet_seq')::text, 3, '0');
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.generate_vet_id() OWNER TO postgres;

--
-- TOC entry 231 (class 1255 OID 115080)
-- Name: set_updated_at(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.set_updated_at() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.set_updated_at() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 230 (class 1259 OID 164218)
-- Name: audit_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.audit_log (
    audit_id character varying(12) NOT NULL,
    user_id character varying(20),
    user_role character varying(20),
    action_type character varying(10),
    table_name character varying(50),
    record_id character varying(50),
    source_system character varying(30),
    action_time timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.audit_log OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 41186)
-- Name: clinic_administrator; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.clinic_administrator (
    admin_id character varying(5) NOT NULL,
    admin_name character varying(100) NOT NULL,
    phone_num character varying(11) NOT NULL,
    username character varying(15) NOT NULL,
    password character varying(255) NOT NULL,
    failed_attempts integer DEFAULT 0,
    lock_until timestamp without time zone,
    CONSTRAINT clinic_administrator_admin_name_check CHECK (((admin_name)::text ~ '^[A-Za-z ]+$'::text)),
    CONSTRAINT clinic_administrator_password_check CHECK ((length((password)::text) >= 6)),
    CONSTRAINT clinic_administrator_phone_num_check CHECK (((phone_num)::text ~ '^[0-9]{10,11}$'::text))
);


ALTER TABLE public.clinic_administrator OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 123247)
-- Name: emergency_case; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.emergency_case (
    case_id integer NOT NULL,
    owner_id integer,
    guest_name character varying(100),
    pet_name character varying(100) NOT NULL,
    species character varying(50) NOT NULL,
    severity character varying(20),
    symptoms text NOT NULL,
    contact_number character varying(20) NOT NULL,
    status character varying(20) DEFAULT 'Pending'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT emergency_case_severity_check CHECK (((severity)::text = ANY ((ARRAY['Life Threatening'::character varying, 'Critical'::character varying, 'Severe'::character varying, 'Moderate'::character varying])::text[])))
);


ALTER TABLE public.emergency_case OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 123246)
-- Name: emergency_case_case_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.emergency_case_case_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.emergency_case_case_id_seq OWNER TO postgres;

--
-- TOC entry 3565 (class 0 OID 0)
-- Dependencies: 225
-- Name: emergency_case_case_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.emergency_case_case_id_seq OWNED BY public.emergency_case.case_id;


--
-- TOC entry 220 (class 1259 OID 41227)
-- Name: owner; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.owner (
    owner_id character varying(5) NOT NULL,
    owner_name character varying(100) NOT NULL,
    phone_num character varying(11) NOT NULL,
    email character varying(50) NOT NULL,
    address character varying(100),
    username character varying(15) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    failed_attempts integer DEFAULT 0,
    CONSTRAINT owner_email_check CHECK (((email)::text ~~ '%@%'::text)),
    CONSTRAINT owner_owner_name_check CHECK (((owner_name)::text ~ '^[A-Za-z ]+$'::text)),
    CONSTRAINT owner_password_check CHECK ((length((password)::text) >= 6)),
    CONSTRAINT owner_phone_num_check CHECK (((phone_num)::text ~ '^[0-9]{10,11}$'::text))
);


ALTER TABLE public.owner OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 139649)
-- Name: owner_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.owner_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.owner_seq OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 74140)
-- Name: pet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pet (
    pet_id character varying(5) NOT NULL,
    pet_name character varying(100) NOT NULL,
    species character varying(50) NOT NULL,
    breed character varying(50),
    gender character varying(10) NOT NULL,
    color character varying(50),
    dob date NOT NULL,
    pet_image character varying(255),
    owner_id character varying(5) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT pet_color_check CHECK (((color IS NULL) OR ((color)::text ~ '^[A-Za-z ]+( & [A-Za-z ]+)?$'::text))),
    CONSTRAINT pet_dob_check CHECK ((dob <= CURRENT_DATE)),
    CONSTRAINT pet_pet_name_check CHECK (((pet_name)::text ~ '^[A-Za-z ]+$'::text))
);


ALTER TABLE public.pet OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 139653)
-- Name: pet_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pet_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pet_seq OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 82287)
-- Name: vet_availability; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vet_availability (
    availability_id integer NOT NULL,
    vet_id character varying(10),
    day_of_week character varying(10) NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone,
    admin_id character varying(10)
);


ALTER TABLE public.vet_availability OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 82286)
-- Name: vet_availability_availability_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vet_availability_availability_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vet_availability_availability_id_seq OWNER TO postgres;

--
-- TOC entry 3567 (class 0 OID 0)
-- Dependencies: 223
-- Name: vet_availability_availability_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vet_availability_availability_id_seq OWNED BY public.vet_availability.availability_id;


--
-- TOC entry 228 (class 1259 OID 139650)
-- Name: vet_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vet_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vet_seq OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 41300)
-- Name: veterinarian; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.veterinarian (
    vet_id character varying(5) NOT NULL,
    vet_name character varying(100) NOT NULL,
    phone_num character varying(11) NOT NULL,
    email character varying(50) NOT NULL,
    specialization character varying(100) NOT NULL,
    username character varying(15) NOT NULL,
    password character varying(255) NOT NULL,
    admin_id character varying(5) NOT NULL,
    vet_image character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    failed_attempts integer DEFAULT 0,
    CONSTRAINT veterinarian_email_check CHECK (((email)::text ~~ '%@%'::text)),
    CONSTRAINT veterinarian_password_check CHECK ((length((password)::text) >= 6)),
    CONSTRAINT veterinarian_phone_num_check CHECK (((phone_num)::text ~ '^[0-9]{10,11}$'::text)),
    CONSTRAINT veterinarian_vet_name_check CHECK (((vet_name)::text ~ '^[A-Za-z ]+$'::text))
);


ALTER TABLE public.veterinarian OWNER TO postgres;

--
-- TOC entry 3349 (class 2604 OID 123250)
-- Name: emergency_case case_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.emergency_case ALTER COLUMN case_id SET DEFAULT nextval('public.emergency_case_case_id_seq'::regclass);


--
-- TOC entry 3347 (class 2604 OID 82290)
-- Name: vet_availability availability_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vet_availability ALTER COLUMN availability_id SET DEFAULT nextval('public.vet_availability_availability_id_seq'::regclass);


--
-- TOC entry 3557 (class 0 OID 164218)
-- Dependencies: 230
-- Data for Name: audit_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.audit_log (audit_id, user_id, user_role, action_type, table_name, record_id, source_system, action_time) FROM stdin;
\.


--
-- TOC entry 3546 (class 0 OID 41186)
-- Dependencies: 219
-- Data for Name: clinic_administrator; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.clinic_administrator (admin_id, admin_name, phone_num, username, password, failed_attempts, lock_until) FROM stdin;
AD001	Clinic Admin	01111244959	admin	$2y$10$XbXEwa9zzUxXFXVjvQRVWuG4NC3nAITfZ0S5PlDHP3rDADIysWaMi	0	\N
\.


--
-- TOC entry 3553 (class 0 OID 123247)
-- Dependencies: 226
-- Data for Name: emergency_case; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.emergency_case (case_id, owner_id, guest_name, pet_name, species, severity, symptoms, contact_number, status, created_at) FROM stdin;
\.


--
-- TOC entry 3547 (class 0 OID 41227)
-- Dependencies: 220
-- Data for Name: owner; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.owner (owner_id, owner_name, phone_num, email, address, username, password, created_at, failed_attempts) FROM stdin;
OW063	TAUFIQ BIN AZHAR	0134567890	taufiq@gmail.com	JALAN MELAKA,, 33600 BATU BERENDAM, MELAKA	taufiq	$2y$10$zL5YOnm3eumieojyBhwjTOJf0zzV/zcqWOxt3M24llFN3tGUmSkbu	2026-01-15 00:56:58.001793	0
OW064	HIDAYAH	01234567890	dayah@gmail.com		dayah	$2y$10$9RQpIQwOlEOGyifpueBNAet2jZ88aEG2h9jWBmdJLNHnvubLascIK	2026-01-15 09:20:52.6395	0
OW060	UMAR BIN KUMAR	0134567891	umar@gmail.com	JALAN MELAKA,, 33600 BATU BERENDAM, MELAKA	umar	$2y$10$WFVBUHtRYqjcg0.U/oYiJ.40JJJ0wIMDJDaF4Xs0rfl1Hj6JDJP0W	2026-01-15 00:54:42.231715	0
OW009	SYARMIMI HUSNA	0175762531	mimi@gmail.com	LOT 3002, JALAN KERDAS 5, 53100 KERDAS, SELANGOR	syyarmimi_	$2y$10$cueIibnZVSfWvctIIWg.EOOQ0ZjQunGBy2xR87p.Xe4p7lD.0VGUG	2025-09-05 11:58:36.60752	0
OW005	SITI AMINAH BT RAHMAN	0126574902	minah@gmail.com	NO 10, TAMAN SEJAHTERA 5, 76100 DURIAN TUNGGAL, MELAKA	minah17	$2y$10$QyC9yns6NAkSBcj2k4ztbOoNjaRAbevk0a1t7SdfmBfwk/q/qPA/6	2025-09-03 11:51:52.513067	0
OW061	AIDIL	0174709987	muhammadrukaini@gmail.com	UTENG, 10201 TAMAN, SRI	aedil	$2y$10$eYQ.pnd4nLDIqkChvrqQ5.6ADwEJfvIVjTzGSJRWxha3J.yzOwJT2	2026-01-15 00:55:51.255658	0
OW016	TAN WEI JIE	0168899001	weijie@gmail.com	NO 22, JALAN HARMONI, 76100 DURIAN TUNGGAL, MELAKA	weijie	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-11 11:17:41.506728	0
OW034	NUR HAZIRAH	0134455667	hazirah@gmail.com	NO 8, JALAN MUTIARA 6, 75450 AYER KEROH, MELAKA	hazirah	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-06 18:31:53.908011	0
OW057	ASHWIN MENON	0186677994	ashwin@gmail.com	\N	ashwin	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-03 14:46:01.995937	0
OW054	KIRAN PATEL	0174455663	kiran@gmail.com	NO 33, JALAN TAMAN DESA, 75400 MELAKA TENGAH, MELAKA	kiran	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-02 17:10:12.912247	0
OW015	MUHAMMAD HAZIQ	0197788123	haziq@gmail.com	NO 18, JALAN KENANGA, 75450 BUKIT BERUANG, MELAKA	haziq	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-11 11:27:35.975731	0
OW043	NUR SYAZWANI	0135566779	syazwani@gmail.com	\N	syazwani	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-03 13:57:09.777648	0
OW047	GOH XIN YI	0165566771	xinyi@gmail.com	\N	xinyi	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-04 11:25:48.503433	0
OW014	NUR AINA SYAFIQAH	0135567890	aina@gmail.com	\N	aina	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-10 10:52:17.70304	0
OW030	DEEPAK SINGH	0178899001	deepak@gmail.com	NO 16, JALAN TAMAN MUTIARA, 75450 AYER KEROH, MELAKA	deepak	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-03 09:29:02.762123	0
OW012	FARAH	01124499999	fara@gmail.com		farahh	$2y$10$pnVvxxMJ2RmzN0VHIH2XdeQlET8lgHRSr/pf5nzBgsOaf12PNlE.6	2025-09-05 17:01:24.502491	0
OW013	AHMAD FIRDAUS	0173456678	firdaus@gmail.com	NO 5, JALAN MUTIARA 1, 75450 AYER KEROH, MELAKA	firdaus	$2y$10$qFLHkV8kdmsGKHlgf/2A8OK9hV9dWzyiOrUs8yB0X4fDQlGlUrkc.	2025-09-10 15:28:05.693448	0
OW001	MIRA	0167788990	mira@gmail.com	NO 148, TAMAN SAUJANA, 75350 AYER KEROH, MELAKA	mira	$2y$10$Az/nnO4wHn2am6hy8PQ/wej2cZkLEH/Hgjn3M2yTBu.d199KPqQ8m	2025-09-01 11:42:10.7359	0
OW033	MANOJ KUMAR	0185566779	manoj@gmail.com	\N	manoj	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-06 18:00:36.632555	0
OW011	MAISARA BINTI SHARIFUDIN	0111209527	sara@gmail.com	NO 330, TAMAN DESA IDAMAN, 76100 DURIAN TUNGGAL, MELAKA	sara01	$2y$10$KwlSh7.Mclc9lqFa.7V45.wQpkgkYXWRrgRzlhhvEcR.MbjtTVp2a	2025-09-05 17:07:52.758261	0
OW035	CHEONG MIN YU	0167788990	minyu@gmail.com	\N	minyu	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-06 18:27:31.205395	0
OW023	CHAN MING HAO	0165544332	minghao@gmail.com	NO 9, JALAN KERDAS, 75400 MELAKA TENGAH, MELAKA	minghao	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-15 13:04:13.613151	0
OW018	ONG KAI XIN	0189988776	kaixin@gmail.com	NO 7, JALAN DESA IDAMAN, 76100 DURIAN TUNGGAL, MELAKA	kaixin	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-12 18:26:03.933259	0
OW039	ANITA DEVI	0189988774	anita@gmail.com	\N	anita	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-08 09:42:37.963256	0
OW044	YAP ZHI HAO	0123344889	zhihao@gmail.com	NO 10, JALAN KERDAS, 75400 MELAKA TENGAH, MELAKA	zhihao	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-03 15:29:12.5536	0
OW045	SANJAY RAO	0186677885	sanjay@gmail.com	\N	sanjay	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-03 15:07:01.237451	0
OW048	VIKRAM NAIR	0179988442	vikram@gmail.com	NO 28, JALAN TAMAN DESA, 75400 MELAKA TENGAH, MELAKA	vikram	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-05 09:04:17.142155	0
OW051	POOJA MEHTA	0185566991	pooja@gmail.com	\N	pooja	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-06 10:57:41.279452	0
OW053	CHOO MING YU	0168899772	mingyu@gmail.com	\N	mingyu	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-01 17:00:10.702997	0
OW055	HAZREEN IMAN	0193344559	hazreen@gmail.com	\N	hazreen	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-02 10:06:40.544151	0
OW056	LOW JIA QI	0125566990	jiaqi@gmail.com	NO 20, JALAN KENANGA 7, 75450 BUKIT BERUANG, MELAKA	jiaqi	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-03 16:40:34.703206	0
OW040	FARIS AIMAN	0136677884	faris@gmail.com	NO 6, JALAN SAUJANA 2, 75350 AYER KEROH, MELAKA	faris	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-01 11:59:02.13075	0
OW022	SITI NUR AMIRAH	0138899002	amirah@gmail.com	\N	amirah	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-15 16:07:38.308874	0
OW038	HO PEI LING	0125566778	peiling@gmail.com	NO 2, JALAN HARMONI 2, 76100 DURIAN TUNGGAL, MELAKA	peiling	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-07 16:06:51.737859	0
OW019	RAJESH KUMAR	0172233445	rajesh@gmail.com	NO 30, JALAN DESA MUTIARA, 75450 AYER KEROH, MELAKA	rajesh	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-13 09:59:41.16454	0
OW032	NG YI XUAN	0129988773	yixuan@gmail.com	NO 3, JALAN DESA IDAMAN, 76100 DURIAN TUNGGAL, MELAKA	yixuan	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-04 10:12:46.134416	0
OW021	ARUN KUMAR	0195566778	arun@gmail.com	NO 14, JALAN TAMAN SAUJANA, 75350 AYER KEROH, MELAKA	arun	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-15 12:50:28.776112	0
OW010	DANIAL BIN MUSTAFA	0137100198	dani@gmail.com	NO 25, JALAN HARMONI 2, 76100 DURIAN TUNGGAL, MELAKA	danii	$2y$10$.xXR/5xQ6bYUnoG/o2wb7uslYtBxZNCzSRYoG8kn9BejpKytDnUOK	2025-09-05 12:00:17.454014	0
OW041	KOH JIA EN	0164455669	jiaen@gmail.com	\N	jiaen	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-01 17:30:06.358981	0
OW042	NAVEEN KUMAR	0178899443	naveen@gmail.com	NO 21, JALAN DESA MUTIARA, 75450 AYER KEROH, MELAKA	naveen	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-03 11:36:20.193106	0
OW037	IZZAT HAKIM	0194455668	izzat@gmail.com	\N	izzat	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-07 16:12:34.877435	0
OW024	KAVITHA RANI	0179988771	kavitha@gmail.com	\N	kavitha	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-15 13:41:45.15091	0
OW026	LEE JUN WEI	0126677889	junwei@gmail.com	NO 25, JALAN HARMONI, 76100 DURIAN TUNGGAL, MELAKA	junwei	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-01 13:29:12.1319	0
OW017	LIM YEE SHAN	0123344556	yeeshan@gmail.com	\N	yeeshan	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-12 10:13:38.747856	0
OW036	RAVI SHANKAR	0173344558	ravi@gmail.com	NO 19, JALAN TAMAN DESA, 75400 MELAKA TENGAH, MELAKA	ravi	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-06 09:01:34.690997	0
OW046	AMIRUL HAKIM	0197788992	amirul@gmail.com	NO 15, JALAN MUTIARA 8, 75450 AYER KEROH, MELAKA	amirul	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-04 11:12:32.901057	0
OW050	LAM YI CHENG	0124455668	yicheng@gmail.com	NO 1, JALAN HARMONI 5, 76100 DURIAN TUNGGAL, MELAKA	yicheng	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-06 15:07:26.744785	0
OW052	NUR FATIHAH	0137788993	fatihah@gmail.com	NO 17, JALAN SAUJANA 4, 75350 AYER KEROH, MELAKA	fatihah	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-12-01 10:27:32.639994	0
OW027	SURESH NAIDU	0183344556	suresh@gmail.com	\N	suresh	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-02 11:26:13.982251	0
OW028	NOR SYAFIQAH	0139988772	syafiqah@gmail.com	NO 4, JALAN KENANGA 5, 75450 BUKIT BERUANG, MELAKA	syafiqah	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-02 14:29:35.804902	0
OW029	WONG KAI LUN	0162233445	kailun@gmail.com	\N	kailun	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-03 15:06:51.389966	0
OW031	AFIQ HAKIM	0196677882	afiq@gmail.com	\N	afiq	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-04 14:08:32.108139	0
OW049	DANISH ARIF	0136677881	danish@gmail.com	\N	danish	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-11-05 12:02:32.37386	0
OW006	WONG ZHI WEI	0172398473	zhiwei@gmail.com	NO 137, JALAN KENANGA, 75450 BUKIT BERUANG, MELAKA	zhiweii	$2y$10$kd6jnZgL/.Ou2I1q0svn6OH6cyHmFGwLncrGuKE9HWMa6euWeIimS	2025-09-03 11:53:38.157616	0
OW062	RASHID KAMAL BIN ZAMRI	01364500125	rashid@gmail.com		rashid	$2y$10$QpoRPQHRqsLQvCsHmoDzNe.9Y/omBufF2tG.vh7uEOoNDYeo05Vpa	2026-01-15 00:56:35.822333	0
OW003	ATHIRAH	0139371920	tya@gmail.com	\N	tyarose	$2y$10$akajxLs3MAtnH0Aox2qF5eIGEyEFKTryIjoZkJ0vQuuWKj2Li2wBO	2025-09-01 11:48:23.288702	0
OW059	ZAFIRAH ANIS	0172673885	zaf@gmail.com		zaff	$2y$10$EdJBIGEYYrJ98nN8TjUYnOCB12ET4aIkBE8Xe3wA7DgbKVD40Jov.	2026-01-14 13:39:14.14448	0
OW004	AMIRUL AQIL BIN ABDULLAH	0182763892	mirul@gmail.com	BATU 4, PEDAS HILIR, 71400 PEDAS, NEGERI SEMBILAN	mirull	$2y$10$QnZ6ej8TkNjZuDDfqkG7reGj5mb0TJm1G4bSQ/IdJ67VB5vhmPPIy	2025-09-01 11:49:44.476665	0
OW025	MOHD FAIZ	0193344552	faiz@gmail.com	NO 11, JALAN MUTIARA 2, 75450 AYER KEROH, MELAKA	faiz	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-10-01 16:40:00.54983	0
OW020	PRIYA SHARMA	0146677889	priya@gmail.com	\N	priya	$2y$10$E4MKclwK/3Un2.kB20/v.exz0Lt//PX5ufqzw/Tk26ImQ23zPD1/m	2025-09-13 16:41:53.516808	0
OW008	RUKAINI	0174709987	rukai@gmail.com	\N	rukai	$2y$10$4H3YIuN/uYm9R3gmAinNru.rRQhtvhjN90Pvcrppd/HL9W42HdEIa	2025-09-03 11:57:04.133175	0
\.


--
-- TOC entry 3549 (class 0 OID 74140)
-- Dependencies: 222
-- Data for Name: pet; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pet (pet_id, pet_name, species, breed, gender, color, dob, pet_image, owner_id, created_at) FROM stdin;
P0017	SEAL	Cat	Ragdoll	Male	Black	2022-01-04	pet_P0017_1767534895.jpg	OW043	2025-11-06 13:57:09.777648
P0031	CHI	Dog	Chihuahua	Female	Black	2024-01-04	pet_P0031_1767536362.jpg	OW050	2025-11-07 15:07:26.744785
P0002	ALBUS	Cat	British Shorthair	Female	Grey	2022-01-04	pet_P0002_1767530331.jpeg	OW013	2025-09-17 15:28:05.693448
P0075	KOKO	Cat	Siamese	Male	Brown	2023-01-04	pet_P0075_1767531362.jpg	OW004	2025-09-09 11:49:44.476665
P0077	BLACKY	Rabbit	None	Male	Black	2020-01-04	pet_P0077_1767531663.jpeg	OW005	2025-09-04 11:51:52.513067
P0003	PERCY	Cat	Persian	Male	Orange	2021-01-04	pet_P0003_1767531847.jpg	OW014	2025-09-14 10:52:17.70304
P0005	KIRA	Dog	Beagle	Male	Black	2023-01-04	pet_P0005_1767532263.jpg	OW016	2025-09-19 11:17:41.506728
P0008	BRO	Dog	Bulldog	Female	White	2019-01-04	pet_P0008_1767532620.jpg	OW018	2025-09-23 18:26:03.933259
P0009	LUNA	Dog	Mixed	Female	White	2019-01-04	pet_P0009_1767532672.jpeg	OW018	2025-09-20 18:26:03.933259
P0032	BENGALL	Cat	Bengal	Male	Grey	2022-01-04	pet_P0032_1767534235.jpg	OW051	2025-11-18 10:57:41.279452
P0024	ZUMBA	Sugar Glider	None	Male	White	2020-01-04	pet_P0024_1767534687.jpg	OW047	2025-11-13 11:25:48.503433
P0058	BULLS	Dog	Bulldog	Male	Black	2023-01-04	pet_P0058_1767535250.jpg	OW030	2025-10-09 09:29:02.762123
P0011	SOUL	Cat	Ragdoll	Male	White	2018-01-04	pet_P0011_1767535603.jpg	OW036	2025-10-17 09:01:34.690997
P0010	MIKI	Cat	Mixed	Female	Golden	2018-01-04	pet_P0010_1767535562.jpg	OW036	2025-10-11 09:01:34.690997
P0056	SIMBA	Cat	Siamese	Male	Brown	2020-01-04	pet_P0056_1767535975.jpg	OW029	2025-10-17 15:06:51.389966
P0042	HIRONO	Dog	Mixed	Female	Brown	2025-01-04	pet_P0042_1767536461.jpg	OW055	2025-12-08 10:06:40.544151
P0040	PNKY	Cat	Sphynx	Male	Pink	2024-01-04	pet_P0040_1767536552.jpg	OW055	2025-12-16 10:06:40.544151
P0041	KIKI	Dog	Beagle	Male	Calico	2020-01-04	pet_P0041_1767536598.jpg	OW055	2025-12-13 10:06:40.544151
P0014	PEERY	Cat	Persian	Male	Mix Colour	2019-01-04	pet_P0014_1767687384.jpg	OW039	2025-10-16 09:42:37.963256
P0013	KITTY	Cat	Persian	Female	White	2024-01-04	pet_P0013_1767687478.jpg	OW039	2025-10-21 09:42:37.963256
P0022	DOLLY	Cat	Ragdoll	Male	White	2020-01-04	pet_P0022_1767687666.png	OW046	2025-11-10 11:12:32.901057
P0020	DOODLE	Dog	Mixed	Male	Golden	2025-01-04	pet_P0020_1767688052.jpg	OW045	2025-11-15 15:07:01.237451
P0043	SIMBA	Dog	Beagle	Female	Brown	2021-01-04	pet_P0043_1767688404.jpg	OW056	2025-12-05 16:40:34.703206
P0062	BELLA	Cat	Ragdoll	Female	White	2018-01-04	pet_P0062_1767796151.jpg	OW022	2025-09-20 16:07:38.308874
P0061	GINGER	Cat	Mixed	Female	White	2019-01-04	pet_P0061_1767796273.jpg	OW022	2025-09-21 16:07:38.308874
P0027	ROCKY	Rabbit	None	Male	White	2022-01-04	pet_P0027_1767796496.jpg	OW048	2025-11-16 09:04:17.142155
P0028	BROWNIE	Dog	Golden Retriever	Female	Golden	2024-01-04	pet_P0028_1767796564.jpg	OW049	2025-11-14 12:02:32.37386
P0035	LUNA	Rabbit	None	Female	Grey	2018-01-04	pet_P0035_1767796772.jpg	OW053	2025-12-14 17:00:10.702997
P0037	LULU	Dog	Mixed	Female	Brown	2024-01-04	pet_P0037_1767796820.jpg	OW053	2025-12-11 17:00:10.702997
P0039	DODOL	Cat	Mixed	Female	Orange	2021-01-04	pet_P0039_1767796950.jpeg	OW054	2025-12-15 17:10:12.912247
P0045	ZORO	Dog	Mixed	Male	Orange	2018-01-04	pet_P0045_1767797139.jpg	OW057	2025-12-16 14:46:01.995937
P0044	GINGER	Dog	Bulldog	Male	Brown	2019-01-04	pet_P0044_1767797317.jpg	OW057	2025-12-16 14:46:01.995937
P0050	KOKO	Dog	Golden Retriever	Male	Golden	2018-01-04	pet_P0050_1767797463.jpg	OW026	2025-10-03 13:29:12.1319
P0049	MOCHI	Rabbit	None	Male	Grey	2021-01-04	pet_P0049_1767797581.jpg	OW026	2025-10-07 13:29:12.1319
P0053	LUCKY	Dog	Chihuahua	Female	White	2023-01-04	pet_P0053_1767797759.jpg	OW027	2025-10-14 11:26:13.982251
P0065	GINGER	Dog	Poodle	Male	Golden	2018-01-04	pet_P0065_1767798770.jpg	OW038	2025-10-16 16:06:51.737859
P0066	BROWNIE	Dog	Bulldog	Female	Golden	2018-01-04	pet_P0066_1767798875.jpg	OW040	2025-11-03 11:59:02.13075
P0069	MIKI	Cat	Ragdoll	Male	White	2025-01-04	pet_P0069_1767799478.jpg	OW042	2025-11-10 11:36:20.193106
P0088	GOLDY	Dog	Golden Retriever	Female	Golden	2018-01-04	pet_P0088_1767530963.jpg	OW008	2025-09-17 11:57:04.133175
P0076	ABU	Cat	Maine Coon	Male	Grey	2024-01-04	pet_P0076_1767531299.jpg	OW004	2025-09-08 11:49:44.476665
P0055	SNOW	Dog	Poodle	Male	White	2022-01-04	pet_P0055_1767797910.jpg	OW028	2025-10-14 14:29:35.804902
P0018	Z	Hamster	None	Female	Brown	2025-01-04	pet_P0018_1767535035.jpg	OW043	2025-11-09 13:57:09.777648
P0023	TIGER	Dog	Mixed	Male	Brown	2020-01-04	pet_P0023_1767534590.jpg	OW047	2025-11-18 11:25:48.503433
P0082	BROWNIE	Cat	Mixed	Female	Gold	2023-01-04	pet_P0082_1767532177.png	OW012	2025-09-11 17:01:24.502491
P0051	HIRONO	Cat	Mixed	Male	White	2025-01-04	pet_P0051_1767797639.jpg	OW027	2025-10-09 11:26:13.982251
P0047	MAX	Dog	Bulldog	Female	White	2024-01-04	pet_P0047_1767535934.jpg	OW020	2025-09-20 16:41:53.516808
P0098	MAX	Cat	Maine Coon	Male	Black	2018-01-04	pet_P0098_1767798016.jpg	OW032	2025-10-06 10:12:46.134416
P0100	BELLA	Dog	Golden Retriever	Female	White	2025-01-04	pet_P0100_1767798143.jpg	OW033	2025-10-19 18:00:36.632555
P0087	WEENIE	Dog	Mixed	Female	Orange	2025-01-04	pet_P0087_1767532005.jpg	OW011	2025-09-09 17:07:52.758261
P0090	LUCKY	Dog	Golden Retriever	Female	Brown	2025-01-04	pet_P0090_1767532070.jpg	OW019	2025-09-16 09:59:41.16454
P0089	LUCKY	Cat	Maine Coon	Male	Grey	2018-01-04	pet_P0089_1767532102.jpg	OW019	2025-09-21 09:59:41.16454
P0007	MILO	Dog	German Shepherd	Female	Brown	2020-01-04	pet_P0007_1767532754.jpeg	OW018	2025-09-25 18:26:03.933259
P0099	LEMON	Rabbit	None	Male	White	2019-01-04	pet_P0099_1767798201.png	OW033	2025-10-07 18:00:36.632555
P0086	CINNAMON	Cat	Ragdoll	Female	White	2024-01-04	pet_P0086_1767531937.jpeg	OW011	2025-09-07 17:07:52.758261
P0015	GINGER	Dog	Mixed	Male	Dark Brown	2018-01-04	pet_P0015_1767687268.jpg	OW039	2025-10-15 09:42:37.963256
P0102	BRO	Cat	Bengal	Male	Golden	2019-01-04	pet_P0102_1767798351.jpg	OW034	2025-10-15 18:31:53.908011
P0103	SIMBA	Cat	Mixed	Male	Golden	2023-01-04	pet_P0103_1767798378.jpg	OW034	2025-10-13 18:31:53.908011
P0019	LUCY	Dog	Golden Retriever	Female	Light Brown	2023-01-04	pet_P0019_1767688136.jpg	OW044	2025-11-11 15:29:12.5536
P0097	MOCHI	Cat	British Shorthair	Male	Beige	2022-01-04	pet_P0097_1767688532.png	OW025	2025-10-02 16:40:00.54983
P0026	BIY	Dog	Bulldog	Male	White	2023-01-04	pet_P0026_1767796470.jpg	OW048	2025-11-08 09:04:17.142155
P0106	SNOWY	Rabbit	None	Male	Black	2019-01-04	pet_P0106_1767798458.jpg	OW035	2025-10-14 18:27:31.205395
P0104	ZORO	Cat	Ragdoll	Male	White	2025-01-04	pet_P0104_1767798501.jpg	OW035	2025-10-09 18:27:31.205395
P0107	ROCKY	Cat	Maine Coon	Female	Grey	2024-01-04	pet_P0107_1767798637.jpg	OW037	2025-10-15 16:12:34.877435
P0081	SIMBA	Cat	Maine Coon	Male	Brown	2021-01-04	pet_P0081_1767627480.jpg	OW010	2025-09-06 12:00:17.454014
P0001	KIRA	Cat	Siamese	Female	Other	2018-01-04	pet_P0001_1767530425.png	OW013	2025-09-22 15:28:05.693448
P0036	HIRONO	Dog	Bulldog	Female	Other	2020-01-04	pet_P0036_1767796739.png	OW053	2025-12-05 17:00:10.702997
P0072	WOLF	Dog	German Shepherd	Female	Black & Brown	2020-01-04	pet_P0072_1767530734.jpg	OW001	2025-09-10 11:42:10.7359
P0101	HIRONO	Cat	Bengal	Female	Grey	2023-01-04	pet_P0101_1767798172.jpg	OW033	2025-10-07 18:00:36.632555
P0012	MEEZ	Cat	Siamese	Male	Dark Brown	2018-01-04	pet_P0012_1767535521.jpg	OW036	2025-10-12 09:01:34.690997
P0073	SNOPPY	Dog	Bulldog	Male	White	2019-01-04	pet_P0073_1767530686.png	OW001	2025-09-12 11:42:10.7359
P0067	MOCHI	Rabbit	None	Male	Orange	2022-01-04	pet_P0067_1767799058.jpg	OW041	2025-11-05 17:30:06.358981
P0052	KOKO	Dog	German Shepherd	Male	Black	2023-01-04	pet_P0052_1767797687.jpg	OW027	2025-10-14 11:26:13.982251
P0048	MEOW	Dog	Mixed	Female	Orange	2019-01-04	pet_P0048_1767797512.jpg	OW026	2025-10-09 13:29:12.1319
P0068	GREY	Cat	Bengal	Female	Grey	2018-01-04	pet_P0068_1767798991.jpg	OW041	2025-11-02 17:30:06.358981
P0070	HARU	Hamster	None	Female	Orange	2025-01-04	pet_P0070_1767799303.jpg	OW042	2025-11-05 11:36:20.193106
P0074	ZORO	Dog	Beagle	Male	Black & White	2021-01-04	pet_P0074_1767530857.jpg	OW003	2025-09-11 11:48:23.288702
P0064	SNOWY	Dog	Beagle	Male	Brown & White	2019-01-04	pet_P0064_1767798809.jpg	OW038	2025-10-19 16:06:51.737859
P0095	LEMON	Cat	Persian	Male	Grey	2025-01-04	pet_P0095_1767535777.jpg	OW023	2025-09-23 13:04:13.613151
P0080	TIGER	Cat	Siamese	Male	White	2023-01-04	pet_P0080_1767531448.jpg	OW006	2025-09-16 11:53:38.157616
P0016	WHITEY	Cat	Kucing Kampung	Male	White	2022-01-04	pet_P0016_1767534966.jpg	OW043	2025-11-04 13:57:09.777648
P0084	BULUS	Cat	Kucing Kampung	Male	Grey	2021-01-04	pet_P0084_1767531731.jpeg	OW009	2025-09-09 11:58:36.60752
P0046	LEMON	Dog	Bulldog	Female	Black & White	2025-01-04	pet_P0046_1767797327.jpg	OW057	2025-12-15 14:46:01.995937
P0083	ZORO	Dog	Mixed	Male	Black & White	2020-01-04	pet_P0083_1767531776.jpg	OW009	2025-09-08 11:58:36.60752
P0091	BRO	Dog	Mixed	Female	Chocolate	2025-01-04	pet_P0091_1767532435.jpg	OW021	2025-09-19 12:50:28.776112
P0071	GIGI	Dog	German Shepherd	Female	Black & Brown	2020-01-04	pet_P0071_1767799398.jpg	OW042	2025-11-06 11:36:20.193106
P0093	MEOW	Cat	Kucing Kampung	Female	Calico	2018-01-04	pet_P0093_1767532489.jpg	OW021	2025-09-25 12:50:28.776112
P0096	OYEN	Cat	Kucing Kampung	Male	Golden	2021-01-04	pet_P0096_1767535866.jpg	OW024	2025-09-26 13:41:45.15091
P0094	LUNA	Cat	Bengal	Male	Brown	2023-01-04	pet_P0094_1767535816.jpg	OW023	2025-09-27 13:04:13.613151
P0092	MILO	Cat	Mixed	Female	Brown	2020-01-04	pet_P0092_1767532518.jpg	OW021	2025-09-25 12:50:28.776112
P0085	GOOFY	Dog	German Shepherd	Female	Black	2020-01-04	pet_P0085_1767531750.jpg	OW009	2025-09-08 11:58:36.60752
P0079	HIRONO	Cat	Mixed	Male	Brown	2019-01-04	pet_P0079_1767531536.jpeg	OW005	2025-09-06 11:51:52.513067
P0078	LUCKY	Cat	Persian	Female	Orange	2025-01-04	pet_P0078_1767531573.jpg	OW005	2025-09-04 11:51:52.513067
P0063	MOO	Cat	Sphynx	Female	Pink	2020-01-04	pet_P0063_1767796220.jpg	OW022	2025-09-28 16:07:38.308874
P0006	BIY	Dog	Bulldog	Male	Black	2024-01-04	pet_P0006_1767532324.jpg	OW017	2025-09-25 10:13:38.747856
P0060	MIZZ	Cat	Mixed	Female	Light Grey	2018-01-04	pet_P0060_1767688468.jpg	OW031	2025-10-09 14:08:32.108139
P0057	DUDU	Dog	Beagle	Male	Brown	2022-01-04	pet_P0057_1767535357.jpg	OW030	2025-10-06 09:29:02.762123
P0059	MEER	Cat	Mixed	Female	Grey	2019-01-04	pet_P0059_1767535167.jpg	OW030	2025-10-11 09:29:02.762123
P0029	MILO	Hamster	None	Female	Dark Brown	2020-01-04	pet_P0029_1767796626.jpg	OW049	2025-11-08 12:02:32.37386
P0030	GERY	Guinea Pig	None	Male	Grey	2021-01-04	pet_P0030_1767536308.jpeg	OW050	2025-11-07 15:07:26.744785
P0033	GENIE	Guinea Pig	None	Female	Calico	2022-01-04	pet_P0033_1767536161.jpg	OW052	2025-12-14 10:27:32.639994
P0034	BIY	Cat	Siamese	Female	White & Brown	2025-01-04	pet_P0034_1767536202.jpg	OW052	2025-12-13 10:27:32.639994
P0054	OYEN	Dog	German Shepherd	Male	Brown	2022-01-04	pet_P0054_1767797869.jpg	OW028	2025-10-04 14:29:35.804902
P0004	BIT	Rabbit	None	Female	White	2023-01-04	pet_P0004_1767686861.jpg	OW015	2025-09-13 11:27:35.975731
P0025	LUCKY	Dog	Mixed	Male	Grey	2018-01-04	pet_P0025_1767534453.jpg	OW047	2025-11-13 11:25:48.503433
P0108	GINGER	Cat	Maine Coon	Male	Orange	2023-01-04	pet_P0108_1767798562.jpg	OW037	2025-10-20 16:12:34.877435
P0105	LEMON	Cat	Persian	Female	White	2020-01-04	pet_P0105_1767798427.jpg	OW035	2025-10-20 18:27:31.205395
P0021	HASH	Hedgehog	None	Female	White	2021-01-04	pet_P0021_1767688213.jpg	OW046	2025-11-07 11:12:32.901057
P0038	MEOW	Dog	Mixed	Female	Black & White	2020-01-04	pet_P0038_1767797038.jpg	OW054	2025-12-15 17:10:12.912247
P0117	MIKI	Cat	Persian	Female	White	2024-06-18	1768370434_persian-cat-1-.jpg	OW059	2026-01-14 14:00:34.297231
P0118	MOCHI	Cat	Bengal	Male	Grey & Brown	2025-07-14	1768409943_ThorBengalCat.jpg	OW063	2026-01-15 00:59:03.397882
P0119	SUSHI	Hamster	\N	Female	Brown & Orange	2025-11-06	1768410014_cuteyyyhamster.jpg	OW063	2026-01-15 01:00:14.62253
P0120	MOMO	Guinea Pig	\N	Male	Grey & Black	2025-06-12	1768410185_gin.jpg	OW062	2026-01-15 01:03:05.715679
P0121	OYEN	Cat	Kucing Kampung	Female	Orange	2025-07-14	1768410344_oyen.jpg	OW061	2026-01-15 01:05:44.066196
P0122	LUCKY	Cat	British Shorthair	Male	Black & Grey	2025-01-15	1768410655_British-Shorthair-cat-facts.jpg	OW061	2026-01-15 01:10:55.204172
P0123	OYEN	Cat	oyen	Male	Brown & White	2025-10-30	1768436260_download1.jpg	OW059	2026-01-15 08:17:40.353374
P0124	ALI ALSYUKUR	Cat	British Shorthair	Female	Grey & Brown	2023-01-02	1768437329_tabby-cat-close-up-portrait-69932.jpeg	OW060	2026-01-15 08:35:29.112986
P0125	KIKI	Cat	British Shorthair	Female	Grey & Black	2025-05-15	1768440212_bsh.jpg	OW064	2026-01-15 09:23:32.981751
P0126	KOKO	Cat	British Shorthair	Male	White	2025-05-15	1768450974_persian-cat-1-.jpg	OW064	2026-01-15 12:22:54.914771
\.


--
-- TOC entry 3551 (class 0 OID 82287)
-- Dependencies: 224
-- Data for Name: vet_availability; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vet_availability (availability_id, vet_id, day_of_week, start_time, end_time, created_at, updated_at, admin_id) FROM stdin;
1	VT001	Monday	09:00:00	18:00:00	2025-08-23 13:40:00	2026-01-15 00:37:31.396307	AD001
2	VT002	Monday	09:00:00	18:00:00	2025-08-23 13:41:00	2026-01-15 00:37:31.396307	AD001
3	VT003	Monday	09:00:00	18:00:00	2025-08-23 13:42:00	2026-01-15 00:37:31.396307	AD001
4	VT004	Monday	09:00:00	18:00:00	2025-08-23 13:43:00	2026-01-15 00:37:31.396307	AD001
5	VT005	Tuesday	09:00:00	18:00:00	2025-08-23 13:44:00	2026-01-15 00:37:31.396307	AD001
6	VT006	Tuesday	09:00:00	18:00:00	2025-08-23 13:45:00	2026-01-15 00:37:31.396307	AD001
7	VT008	Tuesday	09:00:00	18:00:00	2025-08-23 13:46:00	2026-01-15 00:37:31.396307	AD001
8	VT010	Tuesday	09:00:00	18:00:00	2025-08-23 13:47:00	2026-01-15 00:37:31.396307	AD001
9	VT001	Wednesday	09:00:00	18:00:00	2025-08-23 13:48:00	2026-01-15 00:37:31.396307	AD001
10	VT003	Wednesday	09:00:00	18:00:00	2025-08-23 13:49:00	2026-01-15 00:37:31.396307	AD001
11	VT005	Wednesday	09:00:00	18:00:00	2025-08-23 13:50:00	2026-01-15 00:37:31.396307	AD001
12	VT006	Wednesday	09:00:00	18:00:00	2025-08-23 13:51:00	2026-01-15 00:37:31.396307	AD001
13	VT002	Thursday	09:00:00	18:00:00	2025-08-23 13:52:00	2026-01-15 00:37:31.396307	AD001
14	VT004	Thursday	09:00:00	18:00:00	2025-08-23 13:53:00	2026-01-15 00:37:31.396307	AD001
15	VT008	Thursday	09:00:00	18:00:00	2025-08-23 13:54:00	2026-01-15 00:37:31.396307	AD001
16	VT010	Thursday	09:00:00	18:00:00	2025-08-23 13:55:00	2026-01-15 00:37:31.396307	AD001
17	VT002	Friday	09:00:00	18:00:00	2025-08-23 13:56:00	2026-01-15 00:37:31.396307	AD001
18	VT004	Friday	09:00:00	18:00:00	2025-08-23 13:57:00	2026-01-15 00:37:31.396307	AD001
19	VT005	Friday	09:00:00	18:00:00	2025-08-23 13:58:00	2026-01-15 00:37:31.396307	AD001
20	VT009	Friday	09:00:00	18:00:00	2025-08-23 13:59:00	2026-01-15 00:37:31.396307	AD001
21	VT001	Saturday	09:00:00	18:00:00	2025-08-23 14:00:00	2026-01-15 00:37:31.396307	AD001
22	VT006	Saturday	09:00:00	18:00:00	2025-08-23 14:01:00	2026-01-15 00:37:31.396307	AD001
23	VT008	Saturday	09:00:00	18:00:00	2025-08-23 14:02:00	2026-01-15 00:37:31.396307	AD001
24	VT010	Saturday	09:00:00	18:00:00	2025-08-23 14:03:00	2026-01-15 00:37:31.396307	AD001
25	VT009	Saturday	10:00:00	17:00:00	2026-01-04 17:41:02.261291	2026-01-15 00:37:31.396307	AD001
26	VT009	Monday	09:00:00	18:00:00	2026-01-15 00:48:13.828397	\N	AD001
\.


--
-- TOC entry 3548 (class 0 OID 41300)
-- Dependencies: 221
-- Data for Name: veterinarian; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.veterinarian (vet_id, vet_name, phone_num, email, specialization, username, password, admin_id, vet_image, created_at, failed_attempts) FROM stdin;
VT009	DR RAJENDRAN	0182777542	raj@gmail.com	Vaccination & Preventive Care	raj_vet	$2y$10$OQOEu6Bxyl6gmvDSWlDWnOqhiG.nGlEot9vEshtTbyua1O84/oUtm	AD001	vet_VT009_1766813269.jpg	2025-08-21 13:24:36.794781	0
VT005	DR LIYANA BT SHUKRI	0193322445	yana@gmail.com	Emergency & Critical Care	yana_vet	$2y$10$iC3MsPickusCIlwE4clmneILOCnFGxx./Y.xSa1LS36bYnVGDVfgm	AD001	vet_VT005_1766812732.jpg	2025-08-21 13:10:23.155205	0
VT006	DR ANIQ AFIFI	0102694095	aniq@gmail.com	Dentistry	aniq_vet	$2y$10$LIxWKwf92Q6Mphpch4pI0uGh1uHLqmcDFx2k1PZmTfD5PbSKriSYa	AD001	vet_VT006_1766812830.jpg	2025-08-21 13:12:59.236373	0
VT003	DR LEE WEI	0193322445	lee@gmail.com	Dermatology & Skin Issues	lee_vet	$2y$10$NilqiPnX9Pjx95uDM/lldeoliMsbSRE/FWN2IP2xu7i2vUAn3eZJq	AD001	1766811624_staff-6.jpg	2025-08-20 13:00:24.99384	0
VT004	DR JUN MINGHAO	0178899001	jun@gmail.com	Emergency & Critical Care	jun_vet	$2y$10$pVP1VM.cbDngUpdRHW.A2u7Jw1EpULNLnK6fKZYm2Qn/JUY5MWSXy	AD001	1766811961_staff-8.jpg	2025-08-21 13:06:01.973786	0
VT010	DR KYLIE	0182230329	kyle@gmail.com	Nutrition & Weight Management	kylie_vet	$2y$10$rFgOtYA4N8y25DLxCPoRgeU0H4015FH6wiS4g20HjyUudHHGEjw7C	AD001	1767185696_vet.jpg	2025-08-23 20:54:56.674323	0
VT001	DR SYAKIR BIN SYAHMI	0112233445	syak@gmail.com	General Veterinary Care	syak_vet	$2y$10$RgpnXyuwn6x9NyYEwivxROlmxu5jvQAi7zcwV40UTN4aajrAr/KyW	AD001	1766810905_staff-2.jpg	2025-08-20 12:48:25.492347	0
VT008	DR ALIF IMRAN MEGAT	0192348593	alif@gmail.com	Internal Medicine	alif_vet	$2y$10$xO4cnavmeg0p.uNpfMrJ7OSd2V6VKAI/3BmASgP3ZvX8iJGCMgODi	AD001	vet_VT008_1766812779.jpg	2025-08-21 13:16:50.943843	0
VT002	DR SITI ATIQAH BINTI RASHID	0178890217	siti@gmail.com	Surgery & Orthopedics	siti_vet	$2y$10$q79npzbffJVyh3Ean0XV.uoE6Ehb621Rkpe1KOiKZh3FsEHXoORRi	AD001	1766811176_staff-3.jpg	2025-08-20 12:52:57.000229	0
\.


--
-- TOC entry 3569 (class 0 OID 0)
-- Dependencies: 225
-- Name: emergency_case_case_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.emergency_case_case_id_seq', 1, false);


--
-- TOC entry 3570 (class 0 OID 0)
-- Dependencies: 227
-- Name: owner_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.owner_seq', 64, true);


--
-- TOC entry 3571 (class 0 OID 0)
-- Dependencies: 229
-- Name: pet_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pet_seq', 126, true);


--
-- TOC entry 3572 (class 0 OID 0)
-- Dependencies: 223
-- Name: vet_availability_availability_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vet_availability_availability_id_seq', 26, true);


--
-- TOC entry 3573 (class 0 OID 0)
-- Dependencies: 228
-- Name: vet_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vet_seq', 11, true);


--
-- TOC entry 3387 (class 2606 OID 164224)
-- Name: audit_log audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.audit_log
    ADD CONSTRAINT audit_log_pkey PRIMARY KEY (audit_id);


--
-- TOC entry 3369 (class 2606 OID 41198)
-- Name: clinic_administrator clinic_administrator_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clinic_administrator
    ADD CONSTRAINT clinic_administrator_pkey PRIMARY KEY (admin_id);


--
-- TOC entry 3371 (class 2606 OID 41200)
-- Name: clinic_administrator clinic_administrator_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clinic_administrator
    ADD CONSTRAINT clinic_administrator_username_key UNIQUE (username);


--
-- TOC entry 3385 (class 2606 OID 123262)
-- Name: emergency_case emergency_case_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.emergency_case
    ADD CONSTRAINT emergency_case_pkey PRIMARY KEY (case_id);


--
-- TOC entry 3373 (class 2606 OID 41242)
-- Name: owner owner_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.owner
    ADD CONSTRAINT owner_pkey PRIMARY KEY (owner_id);


--
-- TOC entry 3375 (class 2606 OID 41244)
-- Name: owner owner_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.owner
    ADD CONSTRAINT owner_username_key UNIQUE (username);


--
-- TOC entry 3381 (class 2606 OID 74157)
-- Name: pet pet_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_pkey PRIMARY KEY (pet_id);


--
-- TOC entry 3383 (class 2606 OID 82296)
-- Name: vet_availability vet_availability_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vet_availability
    ADD CONSTRAINT vet_availability_pkey PRIMARY KEY (availability_id);


--
-- TOC entry 3377 (class 2606 OID 41317)
-- Name: veterinarian veterinarian_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinarian
    ADD CONSTRAINT veterinarian_pkey PRIMARY KEY (vet_id);


--
-- TOC entry 3379 (class 2606 OID 41319)
-- Name: veterinarian veterinarian_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinarian
    ADD CONSTRAINT veterinarian_username_key UNIQUE (username);


--
-- TOC entry 3391 (class 2620 OID 164217)
-- Name: clinic_administrator trg_admin_lock; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_admin_lock BEFORE UPDATE ON public.clinic_administrator FOR EACH ROW EXECUTE FUNCTION public.enforce_admin_account_lock();


--
-- TOC entry 3398 (class 2620 OID 164227)
-- Name: audit_log trg_gen_audit_id; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_gen_audit_id BEFORE INSERT ON public.audit_log FOR EACH ROW EXECUTE FUNCTION public.gen_audit_id();


--
-- TOC entry 3392 (class 2620 OID 139648)
-- Name: owner trg_owner_id; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_owner_id BEFORE INSERT ON public.owner FOR EACH ROW WHEN ((new.owner_id IS NULL)) EXECUTE FUNCTION public.generate_owner_id();


--
-- TOC entry 3393 (class 2620 OID 164214)
-- Name: owner trg_owner_lock; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_owner_lock BEFORE UPDATE ON public.owner FOR EACH ROW EXECUTE FUNCTION public.enforce_user_account_lock();


--
-- TOC entry 3396 (class 2620 OID 139655)
-- Name: pet trg_pet_id; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_pet_id BEFORE INSERT ON public.pet FOR EACH ROW WHEN ((new.pet_id IS NULL)) EXECUTE FUNCTION public.generate_pet_id();


--
-- TOC entry 3397 (class 2620 OID 115081)
-- Name: vet_availability trg_vet_availability_updated; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_vet_availability_updated BEFORE UPDATE ON public.vet_availability FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- TOC entry 3394 (class 2620 OID 139652)
-- Name: veterinarian trg_vet_id; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_vet_id BEFORE INSERT ON public.veterinarian FOR EACH ROW WHEN ((new.vet_id IS NULL)) EXECUTE FUNCTION public.generate_vet_id();


--
-- TOC entry 3395 (class 2620 OID 164215)
-- Name: veterinarian trg_vet_lock; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_vet_lock BEFORE UPDATE ON public.veterinarian FOR EACH ROW EXECUTE FUNCTION public.enforce_user_account_lock();


--
-- TOC entry 3389 (class 2606 OID 74158)
-- Name: pet pet_owner_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pet
    ADD CONSTRAINT pet_owner_id_fkey FOREIGN KEY (owner_id) REFERENCES public.owner(owner_id);


--
-- TOC entry 3390 (class 2606 OID 82297)
-- Name: vet_availability vet_availability_vet_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vet_availability
    ADD CONSTRAINT vet_availability_vet_id_fkey FOREIGN KEY (vet_id) REFERENCES public.veterinarian(vet_id) ON DELETE CASCADE;


--
-- TOC entry 3388 (class 2606 OID 41320)
-- Name: veterinarian veterinarian_admin_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.veterinarian
    ADD CONSTRAINT veterinarian_admin_id_fkey FOREIGN KEY (admin_id) REFERENCES public.clinic_administrator(admin_id) ON DELETE CASCADE;


--
-- TOC entry 3563 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT USAGE ON SCHEMA public TO tya_pg;


--
-- TOC entry 3564 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE clinic_administrator; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.clinic_administrator TO tya_pg;


--
-- TOC entry 3566 (class 0 OID 0)
-- Dependencies: 220
-- Name: TABLE owner; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.owner TO tya_pg;


--
-- TOC entry 3568 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE veterinarian; Type: ACL; Schema: public; Owner: postgres
--

GRANT SELECT ON TABLE public.veterinarian TO tya_pg;


-- Completed on 2026-03-03 15:51:48 +08

--
-- PostgreSQL database dump complete
--

\unrestrict qswCUbKqwORCKqeEroNIucEtQnJWBigjAMNrnlt0K7ee6Xxt33ulOjQmNAy89uP

