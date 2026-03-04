# 🚀 PASO A DESARROLLO - PortalDTE

## Guía de Implementación de Fases 1 y 3

**Fecha:** 2025-11-29  
**Versión:** 1.0.0

---

## 📋 RESUMEN DE CAMBIOS

### Fase 1: Seguridad Crítica ✅
- Hash de contraseñas con bcrypt
- Variables de entorno para credenciales
- Funciones para consultas SQL seguras
- Protección CSRF en formularios
- Funciones de escape XSS
- Headers de seguridad HTTP

### Fase 3: Modernización Frontend ✅
- Nuevo diseño con Bootstrap 5
- Layout sin framesets
- Diseño responsive
- jQuery 3.7.1

---

## 📁 ARCHIVOS NUEVOS A COPIAR

```
portaldte/
├── .env                          # Variables de entorno (NO subir a git)
├── .env.example                  # Plantilla de variables de entorno
├── assets/
│   └── css/
│       └── app.css               # Estilos CSS modernos
├── include/
│   ├── security_lib.php          # Librería de seguridad
│   └── frontend_config.php       # Configuración frontend nuevo/viejo
├── templates/
│   └── layout.php                # Layout principal Bootstrap 5
├── index_new.php                 # Nuevo index sin framesets
├── login_new.php                 # Nuevo login moderno
└── sel_emp_new.php               # Nueva selección de empresa
```

---

## 📝 ARCHIVOS MODIFICADOS

| Archivo | Cambios |
|---------|---------|
| `include/config.php` | Carga security_lib.php y usa variables de entorno |
| `val_user.php` | Validación segura con hash de contraseñas |
| `asig_emp.php` | Usa frontend_config.php para redirecciones |

---

## 🔧 PASOS DE IMPLEMENTACIÓN

### 1. Preparar el Ambiente

```bash
# Crear backup completo
pg_dump -U opendte opendte > backup_$(date +%Y%m%d).sql

# Crear directorio de assets si no existe
mkdir -p assets/css
mkdir -p templates
```

### 2. Copiar Archivos Nuevos

```bash
# Copiar en orden:
# 1. Primero la librería de seguridad
cp include/security_lib.php /opt/opendte/httpdocs/include/

# 2. Luego la configuración del frontend
cp include/frontend_config.php /opt/opendte/httpdocs/include/

# 3. Assets y templates
cp -r assets/ /opt/opendte/httpdocs/
cp -r templates/ /opt/opendte/httpdocs/

# 4. Archivos de frontend
cp index_new.php login_new.php sel_emp_new.php /opt/opendte/httpdocs/

# 5. Archivo de entorno
cp .env.example /opt/opendte/httpdocs/.env
```

### 3. Configurar Variables de Entorno

Editar `/opt/opendte/httpdocs/.env`:

```env
APP_ENV=production
APP_DEBUG=false

DB_HOST=10.30.1.194
DB_PORT=5432
DB_DATABASE=opendte
DB_USERNAME=opendte
DB_PASSWORD=TU_PASSWORD_REAL

SII_CERTIFICACION=false
IVA_TASA=19
```

### 4. Actualizar Archivos Modificados

```bash
# Backup de originales
cp include/config.php include/config.php.bak
cp val_user.php val_user.php.bak
cp asig_emp.php asig_emp.php.bak

# Copiar versiones actualizadas
cp include/config.php /opt/opendte/httpdocs/include/
cp val_user.php /opt/opendte/httpdocs/
cp asig_emp.php /opt/opendte/httpdocs/
```

### 5. Configurar Permisos

```bash
# El archivo .env debe ser legible solo por el servidor web
chmod 640 /opt/opendte/httpdocs/.env
chown www-data:www-data /opt/opendte/httpdocs/.env

# Asegurar que .env no sea accesible via web
# Agregar a .htaccess o configuración de Apache/Nginx
```

### 6. Agregar a .htaccess (si no existe)

```apache
# Proteger archivo .env
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

---

## 🧪 VERIFICACIÓN

### Test 1: Login Nuevo
1. Acceder a `https://tu-servidor/login_new.php`
2. Verificar que carga el diseño Bootstrap 5
3. Ingresar credenciales válidas
4. Confirmar redirección al nuevo layout

### Test 2: Login Antiguo (compatibilidad)
1. Acceder a `https://tu-servidor/login.php`
2. Verificar que sigue funcionando
3. Editar `include/frontend_config.php` y cambiar `USE_NEW_FRONTEND` a `false`
4. Confirmar que usa el frontend antiguo

### Test 3: Migración de Contraseñas
1. Hacer login con usuario existente
2. Verificar en BD que `pass_usu` ahora tiene formato hash (`$2y$...`)

### Test 4: Headers de Seguridad
```bash
curl -I https://tu-servidor/login_new.php
# Debe mostrar:
# X-Frame-Options: SAMEORIGIN
# X-Content-Type-Options: nosniff
# X-XSS-Protection: 1; mode=block
```

---

## ⚠️ CONSIDERACIONES IMPORTANTES

### Codificación ISO-8859-1
> **CRÍTICO**: Mantener codificación ISO-8859-1 en todos los archivos por compatibilidad con documentos DTE del SII.

### Migración Gradual de Contraseñas
- Las contraseñas existentes en texto plano se migran automáticamente a hash en el primer login
- No es necesario resetear contraseñas de usuarios

### Rollback
Si hay problemas, revertir:
```bash
# Restaurar archivos originales
cp include/config.php.bak include/config.php
cp val_user.php.bak val_user.php
cp asig_emp.php.bak asig_emp.php

# Cambiar a frontend antiguo
# Editar include/frontend_config.php
# define('USE_NEW_FRONTEND', false);
```

---

## 📊 CONTROL DE CAMBIOS

| Fecha | Autor | Descripción |
|-------|-------|-------------|
| 2025-11-29 | OpenDTE | Implementación inicial Fase 1 y 3 |
| 2026-03-04 | OpenDTE | Migración PHPExcel a PhpSpreadsheet |

---

## 📦 MIGRACIÓN PHPEXCEL A PHPSPREADSHEET

### Archivos Migrados:
| Archivo | Función |
|---------|---------|
| `dte/excel_dte_v2.php` | Exportar DTEs emitidos a Excel |
| `factura/excel_dte_recep_v2.php` | Exportar DTEs recibidos a Excel |
| `factura/excel_dte_recep_v3.php` | Exportar DTEs recibidos a Excel |
| `laudus/gpuerto.php` | Importar DTEs desde Excel |
| `vgm/vgm.php` | Importar DTEs VGM desde Excel |
| `consorcio/generar.php` | Procesar boletas desde Excel |

### Pasos para Instalar en Servidor:

```bash
# 1. Ir al directorio del proyecto
cd /opt/opendte/httpdocs

# 2. Hacer git pull
git pull

# 3. Instalar dependencias con Composer
composer install --no-dev

# 4. Verificar que existe vendor/autoload.php
ls -la vendor/autoload.php
```

### Archivos Nuevos:
- `composer.json` - Configuración de Composer
- `include/excel_helper.php` - Helper opcional para PhpSpreadsheet

---

## 🔗 ARCHIVOS RELACIONADOS

- `Plan.md` - Plan completo de actualización
- `include/security_lib.php` - Documentación de funciones de seguridad
- `include/frontend_config.php` - Configuración del frontend
- `include/excel_helper.php` - Helper para operaciones Excel

