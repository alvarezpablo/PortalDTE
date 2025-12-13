<?php
// mysql example
//define('DB_HOSTNAME','localhost'); // database host name
//define('DB_USERNAME', 'root');     // database user name
//define('DB_PASSWORD', ''); // database password
//define('DB_NAME', 'sampledb'); // database name     
//define('DB_TYPE', 'mysql');  // database type
//define('DB_CHARSET','utf8'); // ex: utf8(for mysql),AL32UTF8 (for oracle), leave blank to use the default charset

// postgres example
define('DB_HOSTNAME','10.30.1.194'); // database host name
define('DB_USERNAME', 'opendte');     // database user name
define('DB_PASSWORD', 'root8831'); // database password
define('DB_NAME', 'opendte'); // database name     
define('DB_TYPE', 'postgres');  // database type
define('DB_CHARSET','');

// mssql server example
//define('DB_HOSTNAME','localhost'); // database host name
//define('DB_USERNAME', 'root');     // database user name
//define('DB_PASSWORD', ''); // database password
//define('DB_NAME', 'sampledb'); // database name     
//define('DB_TYPE', 'mssql');  // database type
//define('DB_CHARSET','');

// oracle server example
//$tnsname = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = hosts)(PORT = 3306)))(CONNECT_DATA =(SERVICE_NAME = database)))";
//define('DB_HOSTNAME',$tnsname); // database host name (TNS)
//define('DB_USERNAME', 'system');     // database user name
//define('DB_PASSWORD', '1234'); // database password
//define('DB_NAME', 'SYSTEM'); // database name     
//define('DB_TYPE', 'oci8');  // database type
//define('DB_CHARSET','AL32UTF8');

// sqlite server example
//define('DB_HOSTNAME','c:\path\to\sqlite.db'); // database host name
//define('DB_USERNAME', '');     // database user name
//define('DB_PASSWORD', ''); // database password
//define('DB_NAME', ''); // database name     
//define('DB_TYPE', 'sqlite');  // database type
//define('DB_CHARSET','');


define('SERVER_ROOT', '/grid/phpGrid');

/******** DO NOT MODIFY ***********/
require_once('phpGrid.php');     
/**********************************/
?>
