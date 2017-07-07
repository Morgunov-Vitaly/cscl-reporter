<?php
/* Защита от вредоносных URL-запросов */

if (strpos($_SERVER['REQUEST_URI'], "eval(") ||	strpos($_SERVER['REQUEST_URI'], "CONCAT") || strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||	strpos($_SERVER['REQUEST_URI'], "base64")) {
	@header("HTTP/1.1 400 Bad Request");
	@header("Status: 400 Bad Request");
	@header("Connection: Close");
	@exit;
}
/* /Защита от вредоносных URL-запросов */

/* Запрет пингбэков и трэкбэков на самого себя */
function true_disable_self_ping( &$links ) {
	foreach ( $links as $l => $link )
		if ( 0 === strpos( $link, get_option( 'home' ) ) )
			unset($links[$l]);
}

add_action( 'pre_ping', 'true_disable_self_ping' );
/* /Запрет пингбэков и трэкбэков на самого себя */

/* меняю стандартное лого на странице регистрации */
function loginLogo() {
	echo '<style type="text/css">
        h1 a { background-image:url('.get_stylesheet_directory_uri().'/img/logoCSCw.png) !important; }
		body {background: #f1f1f1 url(http://cscl-reporter.com/wp-content/uploads/2015/04/treugolnikus.jpg);}
		.wp-core-ui .button-primary {
		background: #29b28f;
		border: none;
		-webkit-box-shadow: none;
		box-shadow: none;
		color: #fff;
		text-decoration: none; 
		text-shadow: none;    
}
.login form {
    margin-top: 20px;
    margin-left: 0;
    padding: 26px 24px 46px;
    background: rgba(255, 255, 255, 0.27);
    -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
    box-shadow: 0 1px 3px rgba(0,0,0,.13);
    border-radius: 5px;
}
.login label {
    color: #c5c5c5;    
}
    </style>';
}

add_action('login_head', 'loginLogo');
/* /меняю стандартное лого на странице регистрации */

/* Ставим ссылку на себя в футере в админке */
function true_change_admin_footer () {
	$footer_text = array(
		'Спасибо вам за творчество с <a href="http://wordpress.org">WordPress</a>',
		'Разработал <a href="https://vk.com/v.morgunov" target="_blank">Виталий Моргунов</a>'
	);
	return implode( ' &bull; ', $footer_text);
}

add_filter('admin_footer_text', 'true_change_admin_footer');
/* Ставим ссылку на себя в футере в админке */

/*  Расширяю список доступных для загрузки типов файлов  */

function mv_myme_types($mime_types){
	$mime_types['svg'] = 'image/svg+xml'; // поддержка SVG
	$mime_types['xls'] = 'application/vnd.ms-excel'; // поддержка Microsoft Excel .xls
	$mime_types['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; // поддержка Microsoft Office - OOXML - Spreadsheet .xlsx
	//$mime_types['psd'] = 'image/vnd.adobe.photoshop'; // поддержка PSD
	return $mime_types;
}
add_filter('upload_mimes', 'mv_myme_types', 1, 1);


/*  /Расширяю список доступных для загрузки типов файлов  */


/* Добавление шорткода [mv-current-username] отображающего LogIn/LogOut пользователя в систему reporter  */
add_shortcode( 'mv-login' , 'mv_LogIn' );

function mv_LogIn(){
	$UsName =  (isset($_COOKIE['mv_cuc_user'])) ? $_COOKIE['mv_cuc_user'] : "LogIn" ;
	If ($UsName == "LogIn"){
		$LogInLink = "<a class='w-text-value popmake-login mv-login' href='#'><i class='fa fa-lock'></i> ". $UsName ."</a>";
	} else {
		$LogInLink = "<a class='w-text-value popmake-login mv-login' href='#'><i class='fa fa-unlock-alt'></i> " . $UsName . "</a>";
	}
	echo $LogInLink;
}
/* / Добавление шорткода [mv-current-username] отображающего LogIn/LogOut пользователя в систему reporter  */