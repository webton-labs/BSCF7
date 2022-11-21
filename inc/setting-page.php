<?php
/**
 * Страница настроек плагина
 * 
 * @package bootstrap-cf7-main
 * @version 1.0.1
 */

// регистрация пункта меню
add_action( 'admin_menu', 'bscf7_settings_menu_page', 25 );
function bscf7_settings_menu_page() { 
	add_submenu_page(
		'wpcf7',
		__('Настройки BSCF7', 'bootstrap_cf7'),
		__('Настройки BSCF7', 'bootstrap_cf7'),
		'manage_options',
		'bscf7_settings',
		'bscf7_setting_page_callback'
	);
}
 
// Каллбек функция
function bscf7_setting_page_callback(){
	echo '<div class="wrap">
	<h1>' . get_admin_page_title() . '</h1>
	<p>' . __('Страница настроек плагина BSCF7', 'bootstrap_cf7') . '</p>
	<form method="post" action="options.php">';
 
		settings_fields( 'bscf7_theme_settings' );
		do_settings_sections( 'bscf7_settings' );
		submit_button();
 
	echo '</form></div>';
}
add_action( 'admin_init',  'bscf7_settings_fields' );

// Поля настроек
function bscf7_settings_fields(){
    // Auto <P>
	add_settings_section(
		'bscf7_settings_section_rest', 
		__(''),
		'',
		'bscf7_settings'
	);

	// Начало
    register_setting('bscf7_theme_settings', 'autop_disable');
    add_settings_field(
		'autop_disable',
		__('Отключение AutoP', 'bootstrap_cf7'),
		'autop_disable_field',
		'bscf7_settings',
		'bscf7_settings_section_rest',
		array( 
			'label_for' => 'autop_disable',
			'class' => '',
			'name' => 'autop_disable',
		)
	);
	function autop_disable_field( $args ){
		// получаем значение из базы данных
		$value = get_option( $args[ 'name' ] );
		
		echo '<div class="form-check form-switch"><input id="autop_disable" name="autop_disable" type="checkbox" 
		' . checked( 1, get_option( 'autop_disable' ), false ) . '
		value="1" class="form-check-input"
		><label class="form-check-label" for="autop_disable">' . __('Отключение автоматической обертки всех полей в тег "p".', 'bootstrap_cf7') .'</label></div>';
	 
	}
    // Конец

	// Начало
	register_setting('bscf7_theme_settings', 'wrapper_disable');
	add_settings_field(
		'wrapper_disable',
		__('Отключение враппера элементов', 'bootstrap_cf7'),
		'wrapper_disable_field',
		'bscf7_settings',
		'bscf7_settings_section_rest',
		array( 
			'label_for' => 'wrapper_disable',
			'class' => '',
			'name' => 'wrapper_disable',
		)
	);
	function wrapper_disable_field( $args ){
		// получаем значение из базы данных
		$value = get_option( $args[ 'name' ] );
		
		echo '<div class="form-check form-switch"><input id="wrapper_disable" name="wrapper_disable" type="checkbox" 
		' . checked( 1, get_option( 'wrapper_disable' ), false ) . '
		value="1" class="form-check-input"
		><label class="form-check-label" for="wrapper_disable">' . __('Отключение враппера вокруг элементов CF7', 'bootstrap_cf7') .'
		<br><b>' . __('Внимание! ', 'bootstrap_cf7') .'</b>' . __('Удаление враппера, нарушает работу функции валидации', 'bootstrap_cf7') . '</label></div>';
		
	}
	// Конец
}

?>