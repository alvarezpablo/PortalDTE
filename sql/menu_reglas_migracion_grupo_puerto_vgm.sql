-- =============================================================================
-- Migración: alinear menu_reglas con Grupo Puerto y VGM actuales
-- Uso: ejecutar sobre una BD existente que ya tenga la tabla menu_reglas
-- Motor: PostgreSQL
-- =============================================================================

BEGIN;

-- -----------------------------------------------------------------------------
-- Grupo Puerto
-- -----------------------------------------------------------------------------

UPDATE menu_reglas
SET menu = 'Grupo Puerto',
    opcion = 'Carga Excel DTE',
    link = 'laudus/gpuerto.php',
    icono = 'bi-file-earmark-excel',
    tipo_regla = 'especial',
    variable = '_GPUERTO_',
    operador = '==',
    valor = '1',
    descripcion = 'Grupo Puerto o Administradores',
    orden = 20,
    fecha_modificacion = CURRENT_TIMESTAMP
WHERE (menu IN ('Carga DTE', 'Grupo Puerto'))
  AND (orden = 20 OR link IN ('dte/carga_excel_dte.php', 'laudus/gpuerto.php'));

UPDATE menu_reglas
SET menu = 'Grupo Puerto',
    opcion = 'Reenviar DTE',
    link = 'laudus/gpuerto_resend.php',
    icono = 'bi-arrow-repeat',
    tipo_regla = 'especial',
    variable = '_GPUERTO_',
    operador = '==',
    valor = '1',
    descripcion = 'Grupo Puerto o Administradores',
    orden = 21,
    fecha_modificacion = CURRENT_TIMESTAMP
WHERE (menu IN ('Carga DTE', 'Grupo Puerto'))
  AND (orden = 21 OR link IN ('dte/reenviar_dte.php', 'laudus/gpuerto_resend.php'));

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
SELECT 'Grupo Puerto', 'Carga Excel DTE', 'laudus/gpuerto.php', 'bi-file-earmark-excel', 'especial', '_GPUERTO_', '==', '1', 'Grupo Puerto o Administradores', 20
WHERE NOT EXISTS (
    SELECT 1
    FROM menu_reglas
    WHERE menu = 'Grupo Puerto'
      AND link = 'laudus/gpuerto.php'
      AND orden = 20
);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
SELECT 'Grupo Puerto', 'Reenviar DTE', 'laudus/gpuerto_resend.php', 'bi-arrow-repeat', 'especial', '_GPUERTO_', '==', '1', 'Grupo Puerto o Administradores', 21
WHERE NOT EXISTS (
    SELECT 1
    FROM menu_reglas
    WHERE menu = 'Grupo Puerto'
      AND link = 'laudus/gpuerto_resend.php'
      AND orden = 21
);

-- -----------------------------------------------------------------------------
-- VGM Emite DTE
-- -----------------------------------------------------------------------------

UPDATE menu_reglas
SET menu = 'VGM Emite DTE',
    opcion = 'Carga Excel DTE',
    link = 'vgm/vgm.php',
    icono = 'bi-file-earmark-excel',
    tipo_regla = 'rut',
    variable = 'RUT_EMP',
    operador = 'IN',
    valor = '77648628,77648624,77239803',
    descripcion = 'RUTs específicos de VGM',
    orden = 30,
    fecha_modificacion = CURRENT_TIMESTAMP
WHERE menu = 'VGM Emite DTE'
  AND (orden = 30 OR link IN ('vgm/carga_excel.php', 'vgm/vgm.php'));

UPDATE menu_reglas
SET menu = 'VGM Emite DTE',
    opcion = 'Excel Softland',
    link = 'vgm/vgm_excel.php',
    icono = 'bi-file-earmark-spreadsheet',
    tipo_regla = 'rut',
    variable = 'RUT_EMP',
    operador = 'IN',
    valor = '77648628,77648624,77239803',
    descripcion = 'RUTs específicos de VGM',
    orden = 31,
    fecha_modificacion = CURRENT_TIMESTAMP
WHERE menu = 'VGM Emite DTE'
  AND (orden = 31 OR link IN ('vgm/excel_softland.php', 'vgm/vgm_excel.php'));

UPDATE menu_reglas
SET menu = 'VGM Emite DTE',
    opcion = 'Reenviar Email',
    link = 'vgm/vgm_reenviar.php',
    icono = 'bi-envelope-arrow-up',
    tipo_regla = 'rut',
    variable = 'RUT_EMP',
    operador = 'IN',
    valor = '77648628,77648624,77239803',
    descripcion = 'RUTs específicos de VGM',
    orden = 32,
    fecha_modificacion = CURRENT_TIMESTAMP
WHERE menu = 'VGM Emite DTE'
  AND (orden = 32 OR link IN ('vgm/reenviar_email.php', 'vgm/vgm_reenviar.php'));

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
SELECT 'VGM Emite DTE', 'Carga Excel DTE', 'vgm/vgm.php', 'bi-file-earmark-excel', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 30
WHERE NOT EXISTS (
    SELECT 1 FROM menu_reglas WHERE menu = 'VGM Emite DTE' AND link = 'vgm/vgm.php' AND orden = 30
);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
SELECT 'VGM Emite DTE', 'Excel Softland', 'vgm/vgm_excel.php', 'bi-file-earmark-spreadsheet', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 31
WHERE NOT EXISTS (
    SELECT 1 FROM menu_reglas WHERE menu = 'VGM Emite DTE' AND link = 'vgm/vgm_excel.php' AND orden = 31
);

INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, orden)
SELECT 'VGM Emite DTE', 'Reenviar Email', 'vgm/vgm_reenviar.php', 'bi-envelope-arrow-up', 'rut', 'RUT_EMP', 'IN', '77648628,77648624,77239803', 'RUTs específicos de VGM', 32
WHERE NOT EXISTS (
    SELECT 1 FROM menu_reglas WHERE menu = 'VGM Emite DTE' AND link = 'vgm/vgm_reenviar.php' AND orden = 32
);

COMMIT;

-- Verificación sugerida
SELECT id, menu, opcion, link, icono, activo, orden
FROM menu_reglas
WHERE menu IN ('Grupo Puerto', 'VGM Emite DTE')
ORDER BY orden, id;