# 📋 PLAN DE ACTUALIZACIÓN - PROYECTO PORTALDTE

## 🔍 RESUMEN EJECUTIVO

**PortalDTE** es un sistema de Facturación Electrónica para el SII de Chile, desarrollado en PHP puro (sin framework moderno). El proyecto tiene aproximadamente **15+ años de desarrollo** y presenta una deuda técnica significativa que requiere una modernización gradual.

---

## 📊 ESTADO ACTUAL DEL PROYECTO

### 🏗️ Arquitectura

| Aspecto | Estado Actual |
|---------|---------------|
| **Patrón de diseño** | PHP procedural/script (sin MVC) |
| **Estructura** | Monolítica, archivos dispersos |
| **Base de datos** | PostgreSQL con ADOdb |
| **Frontend** | HTML4/Framesets + jQuery 3.5.1 |
| **Generación PDF** | FPDF 1.53 (2004) |
| **Excel** | PHPExcel 1.8 (obsoleto) |
| **SOAP** | NuSOAP 0.9.5 |
| **Email** | PHPMailer antiguo |

### 📦 Librerías y Dependencias Detectadas

| Librería | Versión Actual | Versión Recomendada | Urgencia |
|----------|---------------|---------------------|----------|
| **ADOdb** | Compatible PHP 7/8 | ✅ OK | Baja |
| **FPDF** | 1.53 (2004) | 1.86+ o mPDF | Alta |
| **PHPExcel** | 1.8 | PhpSpreadsheet 2.x | **Crítica** |
| **NuSOAP** | 0.9.5 | ext-soap nativo | Media |
| **Swift Mailer** | 4.1.3 | Symfony Mailer | Alta |
| **jQuery** | 3.5.1 | 3.7+ | Baja |
| **FullCalendar** | 1.6.4 | 6.x | Media |

---

## 🔐 PROBLEMAS DE SEGURIDAD CRÍTICOS

1. **Contraseñas en texto plano** - Las contraseñas se almacenan y comparan sin hash
2. **SQL Injection parcialmente mitigada** - Solo escape de comillas, no prepared statements
3. **Credenciales expuestas en código** - Usuario/contraseña BD en config.php
4. **Sin protección CSRF** - No hay tokens CSRF en formularios
5. **Sin validación XSS consistente** - Salidas sin `htmlspecialchars()` en muchos lugares

---

## 📋 PLAN DE ACTUALIZACIÓN (Fases)

### 🔴 FASE 1: SEGURIDAD CRÍTICA ✅ COMPLETADA (2025-11-29)

| Tarea | Descripción | Estado |
|-------|-------------|--------|
| 1.1 | **Hash de contraseñas** - `password_hash()` y `password_verify()` | ✅ |
| 1.2 | **Variables de entorno** - Archivo `.env` con funciones nativas | ✅ |
| 1.3 | **Prepared Statements** - Funciones `escapeSQL()` y `preparedQuery()` | ✅ |
| 1.4 | **Tokens CSRF** - Funciones `csrfField()` y `validateCSRFToken()` | ✅ |
| 1.5 | **Escape XSS** - Funciones `e()`, `escapeAttr()`, `escapeJS()` | ✅ |
| 1.6 | **Headers de seguridad** - Función `setSecurityHeaders()` | ✅ |

**Archivo principal:** `include/security_lib.php`

### � FASE 2A: SEGURIDAD CRÍTICA RESTANTE (1-2 semanas) ⏳ PENDIENTE

| Tarea | Descripción | Archivo(s) | Estado |
|-------|-------------|------------|--------|
| 2A.1 | **Hash contraseñas al crear usuarios** | `usuario/pro_usu.php` líneas 35-36 | ⏳ |
| 2A.2 | **Hash contraseñas al modificar usuarios** | `usuario/pro_usu.php` líneas 92-93 | ⏳ |
| 2A.3 | **Usar escapeSQL()** en vez de `str_replace("'","''")` | `usuario/pro_usu.php`, `mantencion/pro_clie.php`, `empresa/pro_emp.php` | ⏳ |
| 2A.4 | **Validar variables GET** con `intval()` | `dte/list_dte_v2.php`, `emitir/emitir.php` | ⏳ |
| 2A.5 | **Agregar CSRF a formularios legacy** | `usuario/form_user.php`, `mantencion/form_clie.php` | ⏳ |
| 2A.6 | **SQL injection en DELETE** | `usuario/pro_usu.php` líneas 127-130 | ⏳ |

#### 📁 ARCHIVOS CRÍTICOS A CORREGIR (Top 10)

| # | Archivo | Problemas | Prioridad |
|---|---------|-----------|-----------|
| 1 | `usuario/pro_usu.php` | Contraseñas sin hash, SQL injection | 🔴 Crítica |
| 2 | `mantencion/pro_clie.php` | SQL injection con str_replace | 🔴 Crítica |
| 3 | `empresa/pro_emp.php` | SQL injection | 🟠 Alta |
| 4 | `dte/list_dte_v2.php` | Variables GET sin validar | 🟠 Alta |
| 5 | `emitir/emitir.php` | Variables sin sanitizar | 🟠 Alta |
| 6 | `factura/list_dte_recep_v2.php` | SQL injection potencial | 🟠 Alta |
| 7 | `libros/pro_libro.php` | Sin validación de entrada | 🟡 Media |
| 8 | `caf/pro_caf.php` | Upload sin validación segura | 🟡 Media |
| 9 | `empresa/certificado.php` | Upload de certificados | 🟡 Media |
| 10 | `reenvio/reenvio.php` | Sin autenticación robusta | 🟡 Media |

### �🟠 FASE 2B: ACTUALIZACIÓN DE LIBRERÍAS (4-6 semanas)

| Tarea | Descripción | Complejidad |
|-------|-------------|-------------|
| 2B.1 | **PHPExcel → PhpSpreadsheet** - Migración completa | Alta |
| 2B.2 | **FPDF → mPDF/TCPDF** - Actualizar generación de PDFs | Media |
| 2B.3 | **PHPMailer moderno** - Actualizar a PHPMailer 6.x | Baja |
| 2B.4 | **Composer** - Centralizar dependencias con autoload | Media |
| 2B.5 | **PHP 8.x** - Asegurar compatibilidad con PHP 8.1+ | Alta |

### 🟡 FASE 3: MODERNIZACIÓN FRONTEND ✅ COMPLETADA (2025-11-29)

| Tarea | Descripción | Estado |
|-------|-------------|--------|
| 3.1 | **Eliminar framesets** - Layout con sidebar CSS | ✅ |
| 3.2 | **Bootstrap 5.3.2** - Framework CSS moderno | ✅ |
| 3.3 | **jQuery 3.7.1** - Última versión estable | ✅ |
| 3.4 | **FullCalendar 6.x** - Actualizar calendario | ⏳ Pendiente |
| 3.5 | ~~UTF-8~~ - **MANTENER ISO-8859-1** por compatibilidad SII | ✅ |

> ⚠️ **IMPORTANTE**: El proyecto DEBE mantener codificación **ISO-8859-1** por compatibilidad con el SII (Servicio de Impuestos Internos de Chile). Los documentos DTE requieren esta codificación.

**Archivos nuevos:** `index_new.php`, `login_new.php`, `sel_emp_new.php`, `templates/layout.php`

### 🟢 FASE 4: ARQUITECTURA (6-12 semanas)

| Tarea | Descripción | Complejidad |
|-------|-------------|-------------|
| 4.1 | **Estructura de carpetas** - Separar lógica/presentación | Alta |
| 4.2 | **Patrón Repository** - Abstraer acceso a BD | Alta |
| 4.3 | **API REST** - Documentar y estandarizar endpoints | Media |
| 4.4 | **Testing** - Implementar PHPUnit para tests | Media |
| 4.5 | **Logging** - Implementar Monolog | Baja |
| 4.6 | **Cache** - Implementar Redis/Memcached | Media |

### 🔵 FASE 5: OPCIONAL - FRAMEWORK (Largo plazo)

| Tarea | Descripción | Complejidad |
|-------|-------------|-------------|
| 5.1 | Evaluar migración gradual a Laravel/Symfony | Muy Alta |
| 5.2 | Implementar API RESTful completa | Alta |
| 5.3 | SPA con Vue.js/React | Muy Alta |

---

## 📁 ESTRUCTURA DE ARCHIVOS RECOMENDADA

```
portaldte/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   └── Helpers/
├── config/
│   ├── database.php
│   └── app.php
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── assets/
├── resources/
│   └── views/
├── storage/
│   ├── logs/
│   └── cache/
├── vendor/
├── .env
├── .env.example
└── composer.json
```

---

## 🔧 ACCIONES INMEDIATAS RECOMENDADAS

### 1. Crear `composer.json` centralizado
```json
{
    "name": "opendte/portaldte",
    "require": {
        "php": "^8.1",
        "adodb/adodb-php": "^5.22",
        "phpoffice/phpspreadsheet": "^2.0",
        "phpmailer/phpmailer": "^6.9",
        "mpdf/mpdf": "^8.2",
        "vlucas/phpdotenv": "^5.6",
        "monolog/monolog": "^3.0"
    }
}
```

### 2. Crear archivo `.env`
```env
DB_HOST=10.30.1.194
DB_PORT=5432
DB_NAME=opendte
DB_USER=opendte
DB_PASS=****
APP_ENV=production
```

### 3. Implementar hash de contraseñas
```php
// Al guardar contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Al verificar
if (password_verify($input_password, $stored_hash)) {
    // Login exitoso
}
```

---

## ⏱️ CRONOGRAMA ESTIMADO

| Fase | Duración | Recursos |
|------|----------|----------|
| Fase 1 - Seguridad | 2-4 semanas | 1-2 desarrolladores |
| Fase 2 - Librerías | 4-6 semanas | 1-2 desarrolladores |
| Fase 3 - Frontend | 4-6 semanas | 1 desarrollador + 1 frontend |
| Fase 4 - Arquitectura | 6-12 semanas | 2-3 desarrolladores |
| **Total estimado** | **16-28 semanas** | - |

---

## ⚠️ RIESGOS Y CONSIDERACIONES

1. **Base de código grande**: Muchos archivos PHP interconectados
2. **Sin tests automatizados**: Cambios pueden romper funcionalidad
3. **Dependencias SII**: Cambios en API del SII pueden afectar
4. **Producción activa**: Requiere despliegue gradual con rollback
5. **PHPExcel duplicado**: Hay 4+ copias en diferentes carpetas (dte/, laudus/, reenvio/, vgm/)
6. **⚠️ CODIFICACIÓN ISO-8859-1**: NO migrar a UTF-8. El SII requiere ISO-8859-1 para documentos DTE

---

## 📌 PRÓXIMOS PASOS SUGERIDOS

1. **Crear branch de desarrollo** para modernización
2. **Configurar ambiente de testing** separado
3. **Implementar Fase 1** (seguridad) de inmediato
4. **Documentar APIs actuales** antes de cambios
5. **Establecer CI/CD** para pruebas automatizadas

---

## 📝 HISTORIAL DE CAMBIOS

| Fecha | Versión | Descripción |
|-------|---------|-------------|
| 2025-11-29 | 1.0 | Creación inicial del plan de actualización |
| 2025-11-29 | 1.1 | **FASE 3 COMPLETADA** - Modernización Frontend |
| 2025-11-29 | 1.2 | **FASE 1 COMPLETADA** - Seguridad Crítica |
| 2026-02-26 | 1.3 | **Análisis completo** - Identificación de archivos críticos y Fase 2A |

---

## ✅ FASES IMPLEMENTADAS - RESUMEN

### FASE 1: Seguridad Crítica

| Archivo | Descripción |
|---------|-------------|
| `include/security_lib.php` | Librería completa de seguridad (450+ líneas) |
| `.env` | Variables de entorno para credenciales |
| `.env.example` | Plantilla de configuración |

**Funciones implementadas:**
- `hashPassword()`, `verifyPassword()`, `verifyAndMigratePassword()` - Hash bcrypt
- `loadEnvFile()`, `env()` - Variables de entorno
- `escapeSQL()`, `preparedQuery()`, `buildWhereClause()` - SQL seguro
- `generateCSRFToken()`, `csrfField()`, `validateCSRFToken()` - CSRF
- `e()`, `escapeAttr()`, `escapeJS()`, `escapeURL()` - XSS
- `setSecurityHeaders()`, `secureSessionStart()` - Headers y sesiones
- `validateRUT()`, `validateEmail()` - Validaciones

### FASE 3: Modernización Frontend

| Archivo | Descripción |
|---------|-------------|
| `assets/css/app.css` | Estilos CSS modernos con variables CSS |
| `templates/layout.php` | Layout con sidebar y header Bootstrap 5 |
| `index_new.php` | Nueva página principal sin framesets |
| `login_new.php` | Login moderno con Bootstrap 5 y CSRF |
| `sel_emp_new.php` | Nueva selección de empresa |
| `include/frontend_config.php` | Switch frontend viejo/nuevo |

### Archivos Modificados

| Archivo | Cambios |
|---------|---------|
| `include/config.php` | Carga security_lib.php y usa variables de entorno |
| `val_user.php` | Validación segura con hash y CSRF |
| `asig_emp.php` | Usa frontend_config para redirecciones |

### Cómo Probar

1. **Acceder directamente al nuevo login**: `https://tu-servidor/login_new.php`
2. **Para activar/desactivar**: Editar `include/frontend_config.php` y cambiar `USE_NEW_FRONTEND`

### Características del Nuevo Frontend

- ✅ Bootstrap 5.3.2
- ✅ Bootstrap Icons
- ✅ jQuery 3.7.1
- ✅ Diseño responsive (mobile-friendly)
- ✅ Sidebar colapsable
- ✅ Menú dinámico según rol de usuario
- ✅ Sin framesets (usa iframe para contenido legacy)
- ✅ **ISO-8859-1** - Mantiene compatibilidad con SII

> ⚠️ **NOTA IMPORTANTE**: Se mantiene codificación ISO-8859-1 en todo el proyecto por compatibilidad con los documentos DTE del Servicio de Impuestos Internos (SII) de Chile.

---

*Documento generado automáticamente mediante análisis del código fuente.*

