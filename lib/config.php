<?php
define( 'REGEX_USERNAME', '/^[A-Za-z0-9_-]{3,16}$/');
define( 'REGEX_PASSWORD', '/^[A-Za-z0-9_-]{6,18}$/');
define( 'REGEX_EMAIL',    '/^([A-Za-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/');
define( 'REGEX_FIRST', 	  '/^[A-Za-z-]{2,20}$/');
define( 'REGEX_NOT_NUM',  '/[^0-9]/');

define( 'DB_HOST', "localhost");
define( 'DB_NAME', "bootstrap");
define( 'DB_USER', "root");
define( 'DB_PASS', "");

define( 'IMAGE_MAX_WIDTH', 640 );
define( 'IMAGE_BASE_PATH', "images/large/");

define( 'PASSWORD_SALT', 'P14VtlebkRFSsNY78cU5kMgm6n4EsA==');
