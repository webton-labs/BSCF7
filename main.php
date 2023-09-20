<?php
/**
 * Plugin Name: Bootstrap + CF7
 * Plugin URI: https://webton.ru
 * Description: Добавляет в CF7 поддержку компонентов Bootstrap (алерты, радио, чекбоксы) и дополнительные функции. Требует установленного плагина Contact Form 7.
 * Author: Вебтон.ру
 * Author URI: https://webton.ru
 * Version: 1.0.8
 */

/**
 * Проверка наличия обновлений плагина
 */
require_once __DIR__ . '/update/update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
$BCF7_UpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Webton-Network/BSCF7',
    __FILE__,
    'BSCF7'
);
$BCF7_UpdateChecker->setBranch('main');
$BCF7_UpdateChecker->setAuthentication('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');

/**
 * Проверка на наличие плагина "Contact Form 7"
 */
if(in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins')))){

	/**
	 * Подключение страницы настроек плагина
	 */
	require_once __DIR__ . '/inc/setting-page.php';

	/**
	 * Подключение функций плагина только во фронтенде
	 */
	if ( ! is_admin() ) {

		/**
		 * Отключение "Auto P"
		 */
		if (get_option( 'autop_disable' ) == 1) {
			add_action( 'wpcf7_autop_or_not', '__return_false' );
		}

		/**
		 * Отключение стилей и скриптов Contact Form 7
		 * @return void
		 */
		function wpcf7_remove_assets() {
//			add_filter( 'wpcf7_load_js', '__return_false' );
			add_filter( 'wpcf7_load_css', '__return_false' );
		}
		add_action( 'wpcf7_init', 'wpcf7_remove_assets' );

		/**
		 * Функция асинхронной загрузки стилей и скриптов плагина
		 *
		 * @param $tag
		 * @param $handle
		 *
		 * @return array|mixed|string|string[]
		 */
		function bscf7_asyncdefer_attribute($tag, $handle) {
			$scripts_to_defer = array('index.js', 'contactform.min.js' );
			foreach($scripts_to_defer as $defer_script){
				if( strpos( $tag, $defer_script ) )
					return str_replace( ' src', ' defer="defer" src', $tag );
			}
			return $tag;
		}
		add_filter('script_loader_tag', 'bscf7_asyncdefer_attribute', 10, 2);

		/**
		 * Регистрация стилей и скриптов плагина
		 * @return void
		 */
		function bscf7_scripts() {
			wp_register_script( 'cf-script', plugins_url( '/js/contactform.min.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
			wp_register_style( 'cf-style', plugins_url('css/contactform.min.css', __FILE__) );
		}
		add_action('wp_enqueue_scripts','bscf7_scripts');

		/**
		 * Подключение стилей и скриптов плагина только на страницах
		 * с размещенным шоркодом формы
		 *
		 * @param $atts
		 *
		 * @return mixed
		 */
		function bscf7_add_assets( $atts ){
			wp_enqueue_style( 'cf-style' );
			wp_enqueue_script( 'cf-script' );
			wpcf7_enqueue_scripts();
			return $atts;
		}
		add_filter( 'shortcode_atts_wpcf7', 'bscf7_add_assets' );

		/**
		 * Удаление wrapper (обертки) вокруг элементов форм
		 */
		if (get_option( 'wrapper_disable' ) == 1) {
			add_filter('wpcf7_form_elements', function($content) {
				return preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^>]*>(.*)<\/\1>/i', '\2', $content);
			});
		}

		/**
		 * Изменение структуры чекбоксов и радио для соответствия
		 * структуре bootstrap
		 */
		add_filter('wpcf7_form_elements', function ($content) {
			return preg_replace('/<label><input type="(checkbox|radio)" name="(.*?)" value="(.*?)" \/><span class="wpcf7-list-item-label">/i', '<label class="form-check form-check-inline form-check-\1"><input type="\1" name="\2" value="\3" class="form-check-input"><span class="wpcf7-list-item-label form-check-label">', $content);
		});

		/**
		 * Отмена регистрации стилей Contact Form 7
		 * @return void
		 */
		function bscf7_deregister_styles() {
			wp_deregister_style( 'contact-form-7' );
		}
		add_action( 'wp_print_styles', 'bscf7_deregister_styles', 100 );

	}

} else {
	add_action( 'admin_notices', function(){
		echo '<div id="message" class="error notice is-dismissible"><p>Плагин <b><a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a></b> не активирован! Пожалуйста, <a href="/wp-admin/plugin-install.php?s=Contact%2520Form%25207&tab=search&type=term">установите и активируйте плагин</a>.</p></div>';
	} );
}