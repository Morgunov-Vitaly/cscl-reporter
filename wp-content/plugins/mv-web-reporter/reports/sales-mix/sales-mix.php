<?php	
/*
	
	Менеджер отчета Sales Mix
	ID 160 
	
	
*/

/* !!!!!!!!!!   Подключаем стили  !!!!!!!!!! */	

add_action( 'wp_footer', 'enqueue_mv_stylecss_160' );
/* Подвешиваем к хуку функцию подключения стилей */	

function enqueue_mv_stylecss_160() {
	/* Проверяем наличие шорткода  в посте */
	global $mv_report_params;	
	//PC::debug($mv_report_params['id']);
	if ($mv_report_params['id'] == 160) {
		wp_register_style( 'mv_stylecss_160', plugins_url('css/report-160.css', __FILE__));
		wp_enqueue_style( 'mv_stylecss_160' );
	}
}
/* / Подключаем стили !!!!!!!!!! */	

/* !!!!!!! Подключаем AJAX обработчик отчета 160 JS !!!!!!!! */
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-constructor-160.php' );
require_once( plugin_dir_path( __FILE__ ) . 'handlers/report-handler-160.php' );


function mv_160_sales_mix_report (){
	global $post;
	$content = $post->post_content; /* Считываем контент страницы поста и смотрим есть ли шорткод [mv_closed] или [mv_reports] */
	ob_start();
	?>
	
	$.ajax({
		type: 'GET',
		url: '<?php echo admin_url( "admin-ajax.php" ); ?>', /* URL к которму подключаемся как альтернатива */
		data: {
			action: 'mv_take_report_data_160', /* Вызывам обработчик делающий запрос данных отчета*/
			mv_nonce: '<?php echo wp_create_nonce( "mv_take_report_data_160" ); ?>',
			ref_organization: document.getElementById('form_param_ref_organization').value, //по ID поля $('#form_param_ref_organization').val() window.form_param_ref_organization.value
			cafe_ref: document.getElementById('form_param_cafe').value, //по ID поля $('#form_param_cafe').val() window.form_param_cafe.value
			dateFrom: document.getElementById('dateFrom').value + 'T00:00:00', //по ID поля window.dateFrom.value.toISOString().replace(/\..*$/, '') window.dateFrom.value + 'T00:00:00',
			dateTo: document.getElementById('dateTo').value + 'T23:59:59' //по ID поля document.getElementById('form_param_ref_organization').value   window.dateTo.value + 'T23:59:59'
		},
		success: function (result, status) {
			console.log("<?php _e( 'Статус запроса списка по токену: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
			if (result != ""){
				mv_report_result = JSON.parse(result);
				console.log('mv_report_result: ' + mv_report_result);
				if (mv_report_result.mv_data.mv_error_code == "200") { //Все получилось!
					$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
					
					$("#mv_report_container").html(mv_report_result.mv_html);// обновляем форму отчета
					
					//Добавить условие, если этот блок с выводом параметров отчета вообще есть
					if ( document.getElementById("displayorgname") != undefined) {
						document.getElementById("displayorgname").innerHTML = document.getElementById("form_param_ref_organization").options[document.getElementById("form_param_ref_organization").options.selectedIndex].text;
						document.getElementById("displaydatefrom").innerHTML = document.getElementById("dateFrom").value;
						document.getElementById("displaydateto").innerHTML = document.getElementById("dateTo").value;
					}
					
					console.log("<?php _e( 'Статус запроса конструктора отчета: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
					
					} else {
					
					/* Здесь надо вывести окно с сообщением об ошибке или сделать редирект на соответсвующую страницу 401, 403 и т.д. */
					//alert("<?php _e( 'Ошибка конструктора отчета!: ', 'mv-web-reporter' ); ?>" + mv_report_result.mv_data.mv_error_code);
					$("#mv_report_container").html('<H3 style="text-align: center;">Ошибка конструктора: ' + mv_report_result.mv_data.mv_error_code + '</h3><p style="text-align: center;">message: ' + mv_report_result.mv_data.message + '</p>'); // Выводим сообщение об ошибке
					$(".mv_reports_container").slideDown('normal');// показать .mv_reports_container - контейнер для вывода отчетов
					console.log('mv_error_code: ' + mv_report_result.mv_data.mv_error_code);
					console.log('message: ' + mv_report_result.mv_data.message);
					console.log('report URL: ' + mv_report_result.mv_html);
				}
				}else{
				console.log("<?php _e( 'Удаленный сервер вернул пустую строку: ', 'mv-web-reporter' ); ?>" + result);
			}
			//$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
			
		},
		error: function (result, status, jqxhr) { // срабатывает только в случае если не сработает AJAX запрос на WP
			
			//$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal')
			//alert("<?php _e( 'Упс! Возникла ошибка при обращении №2 к серверу WP! Ответ сервера: ', 'mv-web-reporter' ); ?>" + result);
			console.log("<?php _e( 'Статус: ', 'mv-web-reporter' ); ?>" + status); // Выводим сообщение об ошибках
			console.log("<?php _e( 'jqXHR статус: ', 'mv-web-reporter' ); ?>" + jqxhr.status + " " + jqxhr.statusText);
			console.log(jqxhr.getAllResponseHeaders());
		}
	});
	
	<?php
	$html = ob_get_contents();
	ob_get_clean();
	
	return $html;
}
?>