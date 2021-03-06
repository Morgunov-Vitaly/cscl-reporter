<?php
	/*
		
		PHP обработчик запроса на удаленный сервер данных 160 отчета 
		
	*/
	
	
	add_action('wp_ajax_mv_take_report_data_160' , 'mv_take_report_data_160'); /* Вешаем обработчик mv_take_report_data на ajax  хук */
	add_action('wp_ajax_nopriv_mv_take_report_data_160', 'mv_take_report_data_160'); /* то же для незарегистрированных пользователей */
	
	function mv_take_report_data_160() {
		
		$nonce = $_GET['mv_nonce']; // Вытаскиваем из AJAX запроса переданное значение mv_nonce и заносим в переменную $nonce
		// проверяем nonce код, если проверка не пройдена прерываем обработку
		if( ! wp_verify_nonce( $nonce, 'mv_take_report_data_160' ) ) wp_die('Stop! Nonce code of mv_take_report_data_160 incorrect!');
		
		if (isset($_GET['cafe_ref']) && $_GET['cafe_ref']!=="0"){ 
			$refObject = $_GET['cafe_ref'];
			$objectType='Coffeeshop';
			} else {wp_die('Возникли проблемы с выбором кофейни для построения отчета!');
		}
		$dateFrom = $_GET['dateFrom'];
		$dateTo = $_GET['dateTo'];
		$token = ( $_COOKIE['mv_cuc_token'] != '' ? $_COOKIE['mv_cuc_token'] : ''); //Забираем токен из кукиса
		/* https://cscl.coffeeset.ru/ws-test/web/report?token=...&id=160&dateFrom=...&dateTo=...&refDivision=193A9F3B-15AE-4030-B3BA-6DE2DA537383
		*/
		$mv_url = 'https://cscl.coffeeset.ru/ws-test/web/report?token=' . $token . '&id=160&dateFrom=' . $dateFrom . '&dateTo='  . $dateTo . '&refDivision=' . $refObject; // Формируем строку запроса
		//$mv_url = "https://cscl.coffeeset.ru/ws-test/web/report?token=YTY0OTYxY2UtYTgwNS00N2M3LTg1YzctZjMyNTU3YTUyMTFj&id=160&dateFrom=2017-01-01T00:00:01&dateTo=2017-01-31T23:59:59&refDivision=193A9F3B-15AE-4030-B3BA-6DE2DA537383";
		PC::debug($mv_url );	
		$mv_remote_get = wp_remote_get( $mv_url, array(
		'timeout'     => 11)); //увеличиваем время ожидания ответа от удаленного сервера с 5? по умолчанию до 11 сек
		
		$mv_report_result = json_decode( wp_remote_retrieve_body( $mv_remote_get ) ); /* PHP функция Принимает закодированную в JSON строку и преобразует ее в объект PHP */
		// Ну и если ответ сервера 200 OK, то можно вывести что-нибудь
		if ( ! is_wp_error( $mv_remote_get )  &&  wp_remote_retrieve_response_code( $mv_remote_get ) == 200 )  {
			
			
			PC::debug( $mv_report_result );
			//PC::debug( $token );
			$mv_user = ( $_COOKIE['mv_cuc_user'] != '' ? $_COOKIE['mv_cuc_user'] : '');
			
			/*
				!!!!!!!!!!!! 
				вызваем конструктор отчета
				!!!!!!!!!!!! 
			*/
			 if(! empty( $mv_report_result->employeeSummary ) ){
				$mv_html = mv_160_report_constructor($mv_report_result); 
				} else {
				$mv_html ='<p style="text-align: center;">' . __( 'Данные отсутствуют', 'mv-web-reporter' ) . '</p>';
			};
			/* / вызваем конструктор отчета */
			
			//PC::debug( $mv_html );
			$mv_data = array('mv_error_code' => '200', 'message' => 'Well done!'); 
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);
			
			echo json_encode($mv_response); // Это передается во фронтэнед
			
			}else {
			
			/* 
				произошел сбой:
				- 401 отказано в доступе 401 Unauthorized («не авторизован»)
				- 404 "message": "User not found"
				- 403 - какая-то таинственная ошибка которая переодически выскакивает
				- 500 "message": "Произошла ошибка.",  "Exceptionmessage": "Timeout expired.  The timeout period elapsed prior to completion of the operation or the server is not responding." 
				
			*/			
			PC::debug(wp_remote_retrieve_response_code( $mv_remote_get ) );	
			//PC::debug($mv_report_result );
			if ( is_wp_error( $mv_remote_get )) { //timeout? отказ в доступе и пр.
				PC::debug( $mv_remote_get );
			}
			//$mv_error_code_result = ((null !== $mv_remote_get->get_error_code())  ? $mv_remote_get->get_error_code() : "" );
			$mv_html = '"' . $mv_url . '"'; //запишем в пустующий раздел адресс ссылки-запроса к удаленному серверу
			$mv_data = array('mv_error_code' => '"' . wp_remote_retrieve_response_code( $mv_remote_get ) .'"', 'message' => '"'. ((isset($mv_report_result->message)) ? $mv_report_result->message :"" ) . '"');
			$mv_response = array('mv_data'=>$mv_data, 'mv_html'=>$mv_html);			
			//echo '{"mv_error_code" : "' . wp_remote_retrieve_response_code( $mv_remote_get ) . '", ' . '"message" :  "' . $mv_report_result->message . '"}';
			echo json_encode($mv_response); // Это передается во фронтэнед
		};
		
		// Не забываем завершать PHP
		wp_die();
		
	};		
	/* !!!!!!!!!!! / PHP обработчик AJAX запроса данных 160 отчета !!!!!!!!!!! */
?>