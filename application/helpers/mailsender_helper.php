<?php
	/*! 
	 * @file
	 * @brief Функция для отправки сообщения по электронной почте.
	 * Использует классы фрейсворка codeigniter из репозитория composer.
	 * В качестве аргумента $adresat можно передать массив с адресами
	 * для рассылки нескольким или строку с одним адресом.
	 * 
	 * Для mailsender требуется расширение openssl.
	 * 
	 * Для работы функции следует проинициализировать переменную $conf
	 * параметрами smtp сервера для отправки сообщения.
	 * 
	 * В файле system/core/CodeIgniter.php добавить условие для вызова функции после следующего комментария. В версии codeigniter 3.1.0 строка 516.
	 * 
	 * ------------------------------------------------------
	 *  Call the requested method
	 * ------------------------------------------------------
	 * if(!defined('PHP_MODE')) {
	 *	call_user_func_array(array(&$CI, $method), $params);
	 * }
	 * 
	 * @version 0.03 2017-03-16
	 * 
	 * @author Ващенко Александр <Vash-Alexandr@yandex.ru>
	 */

	//mark_ae();
	//const PHP_MODE = true;
	//require('codeigniter/index.php');
	
	const COUNT_SEND_ATTEMPT = 3;
	//mark_ae();
	
	function mailsender($adresat, $message, $subj) {
		$CI_superobject = &get_instance();
		//mark_ae();
		$CI_superobject->load->library('email');
	
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.yandex.ru';
		$config['smtp_user'] = ''; //Логин для ящика
		$config['smtp_pass'] = ''; //Пароль для ящика
		$config['smtp_port'] = 465; // Порт на яндексе
		$config['smtp_crypto'] = 'ssl';
		$config['smtp_timeout'] = 20;
		$config['mailtype'] = 'html';

		$CI_superobject->email->initialize($config);
		
		$CI_superobject->email->from('test1@yandex.ru'); //Указать свой адрес для ответа
		$CI_superobject->email->to($adresat); 
		$CI_superobject->email->subject($subj);
		$CI_superobject->email->message($message);
		
		for($i = 0; $i < COUNT_SEND_ATTEMPT; ++$i) {
			if($CI_superobject->email->send()) {
				return array(0 => 'Сообщение успешно отправлено');
			}
			sleep(3);
		}
		return array(1 => 'Не удалось отправить сообщение');
	}
	
	function mark_ae() {
		static $tm = 0;
		//echo microtime().'	';
		if($tm === 0) {
			$tm = microtime(true);
		} else {
			$stamp = microtime(true);
			$period = $stamp - $tm;
			$tm = $stamp;
			echo $period.PHP_EOL;
		}
		//echo PHP_EOL;
	}
	
?>
