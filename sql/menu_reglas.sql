-- =============================================================================
-- Tabla: menu_reglas
-- Descripción: Reglas de visibilidad de opciones del menú
-- PortalDTE - Sistema de Facturación Electrónica
-- =============================================================================

-- Crear secuencia
CREATE SEQUENCE IF NOT EXISTS menu_reglas_id_seq START 1 INCREMENT 1;

-- Crear tabla
CREATE TABLE IF NOT EXISTS menu_reglas (
    id INTEGER NOT NULL DEFAULT nextval('menu_reglas_id_seq'),
    menu VARCHAR(100) NOT NULL,           -- Nombre del menú padre
    opcion VARCHAR(200) NOT NULL,         -- Nombre de la opción
    link VARCHAR(255),                    -- URL de la opción
    icono VARCHAR(50),                    -- Clase de icono Bootstrap
    tipo_regla VARCHAR(20) NOT NULL,      -- rol, empresa, rut, permiso, especial, siempre
    variable VARCHAR(50),                 -- Variable de sesión a evaluar
    operador VARCHAR(10) DEFAULT '==',    -- ==, !=, IN, NOT IN
    valor VARCHAR(255),                   -- Valor(es) a comparar
    descripcion VARCHAR(255),             -- Descripción de la regla
    activo CHAR(1) DEFAULT 'S',           -- S=Activo, N=Inactivo
    orden INTEGER DEFAULT 0,              -- Orden de visualización
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP,
    PRIMARY KEY (id)
);

-- Índices
CREATE INDEX IF NOT EXISTS idx_menu_reglas_menu ON menu_reglas(menu);
CREATE INDEX IF NOT EXISTS idx_menu_reglas_tipo ON menu_reglas(tipo_regla);
CREATE INDEX IF NOT EXISTS idx_menu_reglas_activo ON menu_reglas(activo);

-- Comentarios
COMMENT ON TABLE menu_reglas IS 'Reglas de visibilidad de opciones del menú';
COMMENT ON COLUMN menu_reglas.tipo_regla IS 'Tipo: rol, empresa, rut, permiso, especial, siempre';
COMMENT ON COLUMN menu_reglas.operador IS 'Operador: ==, !=, IN, NOT IN';

-- =============================================================================
-- Datos iniciales - Reglas actuales hardcodeadas
-- =============================================================================

-- Consorcio (Empresa 85)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('Consorcio', 'Carga Boletas', 'consorcio/carga_boleta.php', 'bi-upload', 'empresa', '_COD_EMP_USU_SESS', '==', '85', 'Solo visible para empresa Consorcio (ID 85)', 10);

-- Carga DTE (Grupo Puerto)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('Carga DTE', 'Carga Excel DTE', 'dte/carga_excel_dte.php', 'bi-file-earmark-excel', 'especial', '_GPUERTO_', '==', '1', 'Grupo Puerto o Administradores', 20);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('Carga DTE', 'Reenviar DTE', 'dte/reenviar_dte.php', 'bi-envelope-arrow-up', 'especial', '_GPUERTO_', '==', '1', 'Grupo Puerto o Administradores', 21);

-- VGM Emite DTE (RUTs específicos)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('VGM Emite DTE', 'Carga Excel', 'vgm/carga_excel.php', 'bi-file-earmark-excel', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 30);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('VGM Emite DTE', 'Excel Softland', 'vgm/excel_softland.php', 'bi-file-earmark-spreadsheet', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 31);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('VGM Emite DTE', 'Reenviar Email', 'vgm/reenviar_email.php', 'bi-envelope', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 32);

-- DTE No Enviado a SII (Empresas específicas)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('DTE Emitidos', 'DTE No Enviado a SII', 'dte/list_dte_no_enviado.php', 'bi-exclamation-triangle', 'empresa', '_COD_EMP_USU_SESS', 'IN', '72,73,70,74,151,318,71', 'Solo empresas específicas', 40);

-- Boleta Exenta (Permiso emiteWeb)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('Emitir DTE', 'Boleta Exenta Electrónica', 'emitir/emitir.php?tipo=41', 'bi-receipt', 'permiso', '_EMITE_WEB_', '==', '1', 'Usuarios con permiso de emisión', 50);

-- Estado Boletas (Solo Admin)
INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
VALUES ('Mantención', 'Estado Boletas', 'mantencion/list_estado_boleta.php', 'bi-clipboard-data', 'rol', '_COD_ROL_SESS', '==', '1', 'Solo Administradores', 60);

