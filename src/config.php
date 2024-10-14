<?php
// MARIADB 설정
define("MY_MARIADB_HOST", "112.222.157.156");     // HOST
define("MY_MARIADB_PORT", "6532");          // PORT
define("MY_MARIADB_USER", "team3");          
define("MY_MARIADB_PASSWORD", "team3");
define("MY_MARIADB_NAME", "team3");
define("MY_MARIADB_CHARSET", "utf8mb4");          
define("MY_MARIADB_DSN", "mysql:host=".MY_MARIADB_HOST.";port=".MY_MARIADB_PORT.";dbname=".MY_MARIADB_NAME.";charset=".MY_MARIADB_CHARSET);

define("MY_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]."/");   // 웹서버 document root 
define("MY_PATH_DB_LIB", MY_PATH_ROOT."lib/db_lib.php"); // DB 라이브러리
define("MY_PATH_ERROR", MY_PATH_ROOT."error.php");        // 에러 페이지