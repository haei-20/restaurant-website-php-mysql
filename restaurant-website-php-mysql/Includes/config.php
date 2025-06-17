<?php
if (!isset($_SESSION)) {
    session_start();
}

// Application paths
define('APP_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', '/restaurant-website-php-mysql');

// Site info
define('SITE_NAME', 'Nhà Hàng Vincent');
define('SITE_DESC', 'Ẩm Thực & Đồ Uống Ngon');

// Email settings (for contact form)
define('CONTACT_EMAIL', 'contact@vincentrestaurant.com');
define('EMAIL_SUBJECT_PREFIX', '[Nhà Hàng Vincent] ');

// Default pagination settings
define('DEFAULT_ITEMS_PER_PAGE', 12);

// Include essential files
require_once APP_ROOT . '/connect.php';
require_once APP_ROOT . '/Includes/functions/functions.php';
require_once APP_ROOT . '/Includes/php_replacements.php';
require_once APP_ROOT . '/Includes/tooltip_helper.php';

// Thông báo chuyển sang PHP thuần
define('PHP_ONLY_VERSION', true);
?> 