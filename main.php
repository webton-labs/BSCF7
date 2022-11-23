<?php
/**
 * Plugin Name: Bootstrap + CF7
 * Plugin URI: https://webton.ru
 * Description: Добавляет в CF7 поддержку компонентов Bootstrap (алерты, радио, чекбоксы) и дополнительные функции. Требует установленного плагина Contact Form 7.
 * Author: Вебтон.ру
 * Author URI: https://webton.ru
 * Version: 1.0.1
 */

// Проверка обновлений плагина 
require_once __DIR__ . '/update/update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$BCF7_UpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Webton-Network/bootstrap-cf7-main',
    __FILE__,
    'bootstrap-cf7-main'
);

$BCF7_UpdateChecker->setBranch('main');
$BCF7_UpdateChecker->setAuthentication('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');
// КОНЕЦ

// Подключение страницы настроек
require_once __DIR__ . '/inc/setting-page.php';

// Отключение AutoP
if (get_option( 'autop_disable' ) == 1) {
    add_action( 'wpcf7_autop_or_not', '__return_false' );
}
// Конец

// Подключение стилей и сриптов CF7 только на страницах, где выведен [шорткод]
function wpcf7_remove_assets() {
    add_filter( 'wpcf7_load_js', '__return_false' );
    add_filter( 'wpcf7_load_css', '__return_false' );
    add_filter( 'wpcf7cf_load_js', '__return_false' );
    add_filter( 'wpcf7cf_load_css', '__return_false' );
}
add_action( 'wpcf7_init', 'wpcf7_remove_assets' );

function wpcf7_add_assets( $atts ) {
    wpcf7_enqueue_styles();
    wpcf7_enqueue_scripts();
    if ( function_exists('wpcf7cf_enqueue_scripts') || !is_front_page() && !is_home() ) {
        wpcf7cf_enqueue_scripts();
        wpcf7cf_enqueue_styles();
    }
    return $atts;
}
add_filter( 'shortcode_atts_wpcf7', 'wpcf7_add_assets' );
// Конец

// Регистрация стилей и сриптов
function bscf7_scripts() {
    wp_register_script( 'cf-script', plugins_url( '/js/contactform.min.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_register_style( 'cf-style', plugins_url('css/contactform.min.css', __FILE__) );
}
add_action('wp_enqueue_scripts','bscf7_scripts');

function bscf7_add_assets( $atts ) {
    wp_enqueue_style( 'cf-style' );
	wp_enqueue_script( 'cf-script' );
	return $atts;
}
add_filter( 'shortcode_atts_wpcf7', 'bscf7_add_assets' );
// Конец

// Удаление враппера вокруг элементов форм
if (get_option( 'wrapper_disable' ) == 1) {
    add_filter('wpcf7_form_elements', function($content) {
        $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
        return $content;
    });
}
// Конец

// Изменение структуры чекбоксов и радио для соответствия структуре bootstrap
add_filter('wpcf7_form_elements', function ($content) {
    $content = preg_replace('/<label><input type="(checkbox|radio)" name="(.*?)" value="(.*?)" \/><span class="wpcf7-list-item-label">/i', '<label class="form-check form-check-inline form-check-\1"><input type="\1" name="\2" value="\3" class="form-check-input"><span class="wpcf7-list-item-label form-check-label">', $content);

    return $content;
});
// Конец

// Отключение стилей CF7
add_action( 'wp_print_styles', 'bscf7_deregister_styles', 100 );
function bscf7_deregister_styles() {
    wp_deregister_style( 'contact-form-7' );
}
// Конец

?>