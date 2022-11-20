<?php
/**
 * Plugin Name: Bootstrap + CF7
 * Plugin URI: https://webton.ru
 * Description: Добавляет в CF7 поддержку компонентов Bootstrap (алерты, радио, чекбоксы) и дополнительные функции. Требует установленного плагина Contact Form 7.
 * Author: Вебтон.ру
 * Author URI: https://webton.ru
 * Version: 0.16
 */

// Проверка обновлений плагина
require 'update/plugin-update-checker.php';
$BCF7_UpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Webton-Network/bootstrap-cf7-main/update/plugin.json',
	__FILE__,
	'bootstrap-cf7-main'
);

// //Set the branch that contains the stable release.
// $BCF7_UpdateChecker->setBranch('main');

// //Optional: If you're using a private repository, specify the access token like this:
// $BCF7_UpdateChecker->setAuthentication('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');
?>