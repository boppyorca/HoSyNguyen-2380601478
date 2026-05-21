<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_store');
define('DB_USER', 'root');
define('DB_PASS', '');

define('UPLOAD_DIR', 'uploads/products/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', array('jpg', 'jpeg', 'png', 'gif'));

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/webbanhang/');
define('ITEMS_PER_PAGE', 10);
