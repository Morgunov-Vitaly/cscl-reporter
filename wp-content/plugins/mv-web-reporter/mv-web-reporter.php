<?php
	
	/*
		Plugin Name: MV-WEB-Reporter
		Plugin URI: http://cscl-reporter.com
		Description: Плагин для добавления отчетов с использованием библиотеки Select2 & AJAX-запросов в WordPress
		Author: Моргунов Виталий
		Author URI: https://vk.com/v.morgunov
		Version: 20170424
	*/
	
	// установим глобальную переменную с ID модального окна LogIn для удобства укажем ее здесь	
	$mv_login_popup = 6132; 
	
	
	/* Локализация плагина */
	add_action( 'plugins_loaded', 'mv_load_plugin_textdomain' );
	
	function mv_load_plugin_textdomain() {
		load_plugin_textdomain( 'mv-web-reporter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	/* /Локализация плагина */
	
	/* !!!!!!! Подключаем стили и скрипты для ВСЕХ ОТЧЕТОВ конструкторов  !!!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-style-and-js-switcher.php' );
	
	/* !!!!!! Подключаем Login обработчик с кнопкой  и передаем системные переменные !!!!!!!!!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-login-constructor.php' );
	
	/* !!!!!!! Подключаем шорткод конструктора формы ввода предварительных параметров отчетов  !!! */
	require_once( plugin_dir_path( __FILE__ ) . 'handlers/mv-form-param-constructor.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 102 - By Coffeeshops !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/102-by-coffeeshops/102-by-coffeeshops.php' );
	
	/* !!!!!!! Подключаем менеджер отчета 160 - Sales Mix !!!!!!!!! */
	require_once( plugin_dir_path( __FILE__ ) . 'reports/sales-mix/sales-mix.php' );
	
	
	/*
		!!!!!!!! 
		Обработчик шорткода отчетов 
		[mv_reports id="номер отчета"]
		!!!!!!!!!!
	*/
	function mv_reports($atts){
		// задаем значения параметров по умолчанию
		// ID по умолчанию, если его не указывать
		global $mv_report_params;
		$mv_report_params = shortcode_atts( array('id' => '102'), $atts);
		
		//global $mv_login_popup; 	
		
		
		ob_start(); // передадим значение mv_report_id в фронт-энд ! возможно это - лишнее
	?>
	<div id="mv_report_container"> <!-- контейнер отчета -->
	</div>
	<script type="text/javascript">
		mv_report_id = <?php echo $mv_report_params['id'] ?>;
		
		/* !!!!!!!!!!!!!!!!!!!! */
		/* Конструкторы отчетов */
		/* !!!!!!!!!!!!!!!!!!!! */
		jQuery(function ($) {
			$(document).ready(function(){
				$("#form_param").submit(function (event_pr) { /* отправка данных формы с параметрами для построения отчета */
					if (mv_document_ready > 0) {
						$("#mv_report_progress_circle").slideDown('normal'); // Отображаем колесо загрузчик ожидание slideUp('normal')
						<?php 
							
							//PC::debug($mv_report_params['id']);
							if ($mv_report_params['id'] == 102) { /* Отчет по кофейням 160 */
								echo mv_102_report(); 
							}
							if ($mv_report_params['id'] == 160) { /* Отчет по кофейням 160 */
								echo mv_160_sales_mix_report(); 
							}
						?>
						mv_document_ready = mv_document_ready + 1; // счетчик для предотвращения повторного срабатывания функций
						$("#mv_report_progress_circle").slideUp('normal'); // скрываем колесо загрузчик ожидание slideUp('normal');
						event_pr.preventDefault();/* Отменяем стандартное действие кнопки Submit в форме */
					}
				});
				/* !!!!!!!!! / AJAX  Обработчик отправки данных формы параметров отчетов  !!!!!!!!!!!!! */
			});
		});
	</script>
	<?php
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}
	// Также подключаем обработчики других отчетов
	
	add_shortcode('mv_reports', 'mv_reports');   
	/*/ Обработчик шорткода отчетов [mv_reports]*/
	
	
	/* добавляем шорткод для вставки колеса загрузки progress circle */
	
	add_shortcode('mv_progress_circle', 'mv_progress_circle_constructor'); 
	function mv_progress_circle_constructor($atts){
		$params = shortcode_atts( array( // Значенияпо умолчанию
		'id' => 'mv_login_loader', // ID блока по умолчанию mv_login_loader, если его не указывать
		), $atts );
		ob_start();
	?>
	<div id="<?php echo $params['id'] ?>" class="mv_loader" title="0">
		<svg version="1.1" class="mv_svg_loader" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50" enable-background="new 0 0 50 50" xml:space="preserve">
			<path class="mv_svg-path" opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169zM20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/><path class="mv_svg-path" fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0C22.32,8.481,24.301,9.057,26.013,10.047z">
				<animateTransform attributeType="xml"  attributeName="transform" type="rotate" from="0 20 20" to="360 20 20" dur="0.5s" repeatCount="indefinite"/>
			</path>
		</svg>
		</div><?php
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
	}	
	/* / добавляем шорткод для вставки колеса загрузки progress circle */
	
	
	/* 
		Добавление шорткода [mv-current-username] 
		отображающего LogIn/LogOut пользователя 
		в систему reporter  
	*/
	add_shortcode( 'mv-login' , 'mv_LogIn' );
	
	function mv_LogIn(){
		$UsName =  (isset($_COOKIE['mv_cuc_user'])) ? $_COOKIE['mv_cuc_user'] : "LogIn" ;
		If ($UsName == "LogIn"){
			$LogInLink = "<a class='w-text-value mv_login_modal_init' href='#'><i class='fa fa-lock'></i> ". $UsName ."</a>";
			} else {
			$LogInLink = "<a class='w-text-value mv_login_modal_init' href='#'><i class='fa fa-unlock-alt'></i> " . $UsName . "</a>"; /* #mv_login_modal_init - это триггер для модального окна LogIn */
		}
		echo $LogInLink;
	}
/* / Добавление шорткода [mv-current-username] отображающего LogIn/LogOut пользователя в систему reporter  */