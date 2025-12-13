CREATE TABLE public.gpuerto_enc
(
    tipo_docu character varying(100)  NOT NULL,
    folio_erp character varying(100)  NOT NULL,
    rut_emisor character varying(100)  NOT NULL,
    dv_emisor character varying(100) ,
    folio_dte character varying(100) ,
    fecha_dte character varying(100) ,
    fecha_genera time without time zone,
    rut_recep character varying(100) ,
    nom_clie character varying(100) ,
    dir_clie character varying(100) ,
    comu_clie character varying(100) ,
    giro_clie character varying(100) ,
    email_clie character varying(1000) ,
    for_pago character varying(10) ,
    neto character varying(20) ,
    exento character varying(20) ,
    iva character varying(20) ,
    tasa character varying(10) ,
    total character varying(20) ,
    xml text ,
    resp_openb text ,
    estado character varying(1) ,
    pdf character varying(1000) ,
    json_laudus text ,
    resp_laudus text ,
    CONSTRAINT gpuerto_enc_pkey PRIMARY KEY (tipo_docu, folio_erp, rut_emisor)
);

CREATE TABLE public.gpuerto_det
(
    num_lin character varying(10)  NOT NULL,
    tipo_docu character varying(100)  NOT NULL,
    rut_emisor character varying(100)  NOT NULL,
    folio_erp character varying(100)  NOT NULL,
    id character varying(100) ,
    nom character varying(100) ,
    descrip character varying(500) ,
    cant character varying(20) ,
    exencion character varying(10) ,
    precio character varying(20) ,
    total character varying(20) ,
    CONSTRAINT gpuerto_det_pkey PRIMARY KEY (num_lin, tipo_docu, rut_emisor, folio_erp)
);

CREATE TABLE public.gpuerto_ref
(
    num_lin character varying(100)  NOT NULL,
    tipo_docu character varying(100)  NOT NULL,
    rut_emisor character varying(100)  NOT NULL,
    folio_erp character varying(100)  NOT NULL,
    tipo_ref character varying(100) ,
    folio_ref character varying(100) ,
    fecha_ref character varying(100) ,
    cod_ref character varying(100) ,
    motivo character varying(100) ,
    CONSTRAINT gpuerto_ref_pkey PRIMARY KEY (num_lin, tipo_docu, rut_emisor, folio_erp)
);

ALTER TABLE public.gpuerto_enc OWNER to opendte;
ALTER TABLE public.gpuerto_det OWNER to opendte;
ALTER TABLE public.gpuerto_ref OWNER to opendte;


