<?php
	/*
		
		Конструктор 160 отчета 
		
	*/
	
	function mv_160_report_constructor($mv_report_result){
		
/**
 * @param $mv_att - исходный объект $tr->purchaseCategoryInfo или $mv_report_result->employeeSummary[1]->purchaseCategoryInfo;
 * @param $mv_key - название ключа, например, 'category' 
 * @param $mv_vol - значение ключа, например, 'Coffee', 'Drink', 'Food', 'Others' 
 * @$mv_summ - передаем значение суммы
 * @$mv_percent - передаем значение %
 * @$mv_qty - передаем значение кол-ва
 */		
	
		function mv_take_val_with_key ($mv_att, $mv_key, $mv_vol, &$mv_summ, &$mv_qty){
			$mv_rez_str="";
			foreach ($mv_att as $key => $value ) {
				if ($value->$mv_key == $mv_vol) {
					$mv_rez_str = number_format(($value->categorySumm), 1, ',', ' ') . '<br><span class="mv_rep_data_percent">% ' . number_format(($value->percent), 1, ',', ' ') . '</span> <span class="mv_rep_data_qty">('. number_format(($value->qty), 0, ',', ' '). ')</span></div>';
					$mv_summ = $mv_summ + $value->categorySumm;
					$mv_qty = $mv_qty + $value->qty;
					return $mv_rez_str;
					break;
				}
			}
			unset($value);
			unset($key);
		}
		ob_start();
		
		// start Выводим заголовок отчета: дата от до, название организации 
		echo '<p style="display: inline;">'.__('Организация: ', 'mv-web-reporter').'</p><p id="displayorgname" style="display: inline;"></p>';
		echo '<p style="display: inline;"><br>'.__('Дата от: ', 'mv-web-reporter').'</p><p id="displaydatefrom" style="display: inline;"></p>';
		echo '<p style="display: inline;"> / '.__('Дата по: ', 'mv-web-reporter').'</p><p id="displaydateto" style="display: inline;"></p>';
		//echo '<p><br></p>';
		// end Выводим заголовок отчета: дата от до, название организации 
		
		echo '<!-- Шапка таблицы -->';
		$mv_report160_head = '<div class="g-cols  vc_row wpb_row vc_row-fluid"><div class="report_header_orgname vc_col-sm-4  vc_col-xs-12 wpb_column vc_column_container" ><p class ="mv_orgname">' . $mv_report_result->employeeSummary[0]->divisionName . '</p></div><div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/coffee.svg"><br>' .__('Кофе', 'mv-web-reporter') . '</p></div><div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/drinks.svg"><br>' .__('Напитки', 'mv-web-reporter') . '</p></div><div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/food.svg"><br> ' .__('Еда', 'mv-web-reporter') . '</p></div><div class="report_header  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" ><p class="mv_header_img"><img class="mv_75_img" src="' . plugin_dir_url( __FILE__ ) . '../img/other.svg"><br>' .__('Прочее', 'mv-web-reporter') . '</p></div></div>';
		
		echo $mv_report160_head;
		echo '<!-- Данные по сотрудникам -->';
		$mv_index = 0;
		$mv_multiplicator = 5; // мультипликатор - через сколько записей повторять заголовок
		
		$mv_total_coffee=0;
		$mv_total_coffee_percent=0;
		$mv_total_coffee_qty=0;
		
		$mv_total_drink=0;
		$mv_total_drink_percent=0;
		$mv_total_drink_qty=0;
		
		$mv_total_food=0;
		$mv_total_food_percent=0;
		$mv_total_food_qty=0;
		
		$mv_total_oters=0;
		$mv_total_oters_percent=0;
		$mv_total_oters_qty=0;
		
		
		foreach ($mv_report_result->employeeSummary as $tr):
		
		if (($mv_index == $mv_multiplicator)||($mv_index == ($mv_multiplicator * 2))||($mv_index == ($mv_multiplicator * 3))||($mv_index == ($mv_multiplicator * 4))||($mv_index == ($mv_multiplicator * 5))||($mv_index == ($mv_multiplicator * 6))||($mv_index == ($mv_multiplicator * 7))||($mv_index == ($mv_multiplicator * 8))||($mv_index == ($mv_multiplicator * 9))||($mv_index == ($mv_multiplicator * 10))){ $mv_index = $mv_index + 1; echo $mv_report160_head; } else {$mv_index = $mv_index + 1;}
		$mv_str="";
		
		
		echo '<div class="g-cols  vc_row wpb_row vc_row-fluid">';
		
		echo '<div class="report_data_emp_name  vc_col-sm-4  vc_col-xs-12 wpb_column vc_column_container" >';
		echo '<div class="mv_employee_name">' . $tr->author . '</div>';
		echo '</div>';
		
		$mv_str = mv_take_val_with_key($tr->purchaseCategoryInfo, 'category', 'Coffee', $mv_total_coffee, $mv_total_coffee_qty );
		if ($mv_str != ""){
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_coffee">' . $mv_str;
			echo '</div>';
			} else {
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_others">0<br><span class="mv_rep_data_percent">%0</span> <span class="mv_rep_data_qty">(0)</span></div>';
			echo '</div>';
		}
		
		$mv_str =  mv_take_val_with_key($tr->purchaseCategoryInfo, 'category', 'Drink', $mv_total_drink, $mv_total_drink_qty );
		if ($mv_str != ""){
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_drinks">' . $mv_str;
			echo '</div>';
			} else {
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_others">0<br><span class="mv_rep_data_percent">%0</span> <span class="mv_rep_data_qty">(0)</span></div>';
			echo '</div>';
		}
		
		$mv_str = mv_take_val_with_key($tr->purchaseCategoryInfo, 'category', 'Food', $mv_total_food, $mv_total_food_qty);
		if ($mv_str != ""){
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_food">' . $mv_str;
			echo '</div>';
			} else {
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_others">0<br><span class="mv_rep_data_percent">%0</span> <span class="mv_rep_data_qty">(0)</span></div>';
			echo '</div>';
		}
		$mv_str = mv_take_val_with_key($tr->purchaseCategoryInfo, 'category', 'Others', $mv_total_oters, $mv_total_oters_qty);
		if ($mv_str != ""){
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_others">' . $mv_str;
			echo '</div>';
			} else {
			echo '<div class="report_data vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >';
			echo '<div class="mv_others">0<br><span class="mv_rep_data_percent">%0</span> <span class="mv_rep_data_qty">(0)</span></div>';
			echo '</div>';
		}  
		
		echo '</div>';
		endforeach;
		echo '<!-- Подвал с итогами -->';
		
		/* Итоговое значение */
		$mv_total_total = $mv_total_coffee + $mv_total_drink + $mv_total_food + $mv_total_oters;
		/* Формула для процентов */
		$mv_total_coffee_percent = 100 * $mv_total_coffee / $mv_total_total;
		$mv_total_drink_percent = 100 * $mv_total_drink / $mv_total_total;
		$mv_total_food_percent = 100 * $mv_total_food / $mv_total_total;
		$mv_total_oters_percent = 100 * $mv_total_oters / $mv_total_total; 
		echo'<div class="g-cols  vc_row wpb_row vc_row-fluid"><div class="report_footer_total vc_col-sm-4  vc_col-xs-12 wpb_column vc_column_container" >' . __('Итого', 'mv-web-reporter') . '</div><div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >'. number_format(($mv_total_coffee), 1, ',', ' '). '<br><span class="mv_rep_data_percent">% ' . number_format(($mv_total_coffee_percent), 1, ',', ' ') . '</span> <span class="mv_rep_data_qty">('. number_format(($mv_total_coffee_qty), 0, ',', ' '). ')</span>' . '</div><div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >'. number_format(($mv_total_drink), 1, ',', ' ') . '<br><span class="mv_rep_data_percent">% ' . number_format(($mv_total_drink_percent), 1, ',', ' ') . '</span> <span class="mv_rep_data_qty">('. number_format(($mv_total_drink_qty), 0, ',', ' '). ')</span>' .  '</div><div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >'. number_format(($mv_total_food), 1, ',', ' ') . '<br><span class="mv_rep_data_percent">% ' . number_format(($mv_total_food_percent), 1, ',', ' ') . '</span> <span class="mv_rep_data_qty">('. number_format(($mv_total_food_qty), 0, ',', ' '). ')</span>' .  '</div><div class="report_footer  vc_col-sm-2 vc_col-xs-3 wpb_column vc_column_container" >'. number_format(($mv_total_oters), 1, ',', ' ') . '<br><span class="mv_rep_data_percent">% ' . number_format(($mv_total_oters_percent), 1, ',', ' ') . '</span> <span class="mv_rep_data_qty">('. number_format(($mv_total_oters_qty), 0, ',', ' '). ')</span>' .  '</div></div>';
		
		$html = ob_get_contents();
		ob_get_clean();
		return $html;
		//PC::debug( $html );
	} 
	
?>