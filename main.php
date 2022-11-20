<?php
/**
 * Plugin Name: Bootstrap + CF7
 * Plugin URI: https://webton.ru
 * Description: Добавляет в CF7 поддержку компонентов Bootstrap (алерты, радио, чекбоксы) и дополнительные функции. Требует установленного плагина Contact Form 7.
 * Author: Вебтон.ру
 * Author URI: https://webton.ru
 * Version: 0.16
 */

// ================== Проверка обновлений плагина ================== //
    require_once __DIR__ . '/update/update-checker.php';
    use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

    $BCF7_UpdateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/Webton-Network/bootstrap-cf7-main',
        __FILE__,
        'bootstrap-cf7-main'
    );

    $BCF7_UpdateChecker->setBranch('main');
    $BCF7_UpdateChecker->setAuthentication('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');
// ============================= КОНЕЦ ============================= //
?>