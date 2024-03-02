<?php
/**
 * Ограничения количества отправленных форм
 * 
 * Запрещает массовую отправку форм с помощью добавления файла куки указывающего на успешную отправку формы
 * 
 * @version 1.1
 * 
 * @since BSCF7 1.1 Функция добавлена в плагин
 */

/**
 * Переменная с ID форм для ограничения отправки
 */
global $forms_id;
$forms_id = array( 132 );

function wpcf7_on_sent_checker() { 
    global $forms_id;?>
    <script type="text/javascript">
        document.addEventListener( 'wpcf7mailsent', function( event ) {
            <?php foreach ($forms_id as $form_id): ?>
                if ( '<?php echo $form_id; ?>' == event.detail.contactFormId ) {
                    jQuery('[id^="wpcf7-f<?php echo $form_id; ?>"] .wpcf7-submit').prop('disabled', true);
                }
            <?php endforeach; ?>
        }, true );
        document.onreadystatechange = function () {
            if (document.readyState == "complete") {
                <?php foreach ($forms_id as $form_id): ?>
                    if (document.cookie.split(';').filter((item) => item.trim().startsWith('form-<?php echo $form_id; ?>=')).length) {
                        jQuery('[id^="wpcf7-f<?php echo $form_id; ?>"] .wpcf7-submit').prop('disabled', true);
                    }
                <?php endforeach; ?>
            }
        }
    </script><?php
}
add_action( 'wp_footer', 'wpcf7_on_sent_checker', 98 );

/**
 * После отправки формы создаем файл Cookie, содержащий ID формы и статус отправки
 */
add_action('wpcf7_mail_sent', function ($cf7) {

    global $forms_id;
    /**
     * Получение данных текущей формы
     */
    $wpcf7         = WPCF7_ContactForm::get_current();
    /**
     * Получение данных текущей попытки отправки формы
     */
    $submission    = WPCF7_Submission::get_instance();
    /**
     * Время жизни файла Cookie
     */
    $expired_time = time() + 25000;
    /**
     * Цикл проверки всех форм, указанных в списке $forms_id
     */
    foreach ( $forms_id as $form_id ) :
        /**
         * Если ID текущей формы совпадает с любым из указанных в списке,
         * добавляет файл Cookie
         */
        if($wpcf7->id() === $form_id){
            $value = "sent";
            $path = '/';
            $form_name = 'form-' . $form_id;
            setcookie($form_name, $value, $expired_time, $path);  /* срок действия в секундах */
        }
    endforeach;

});