<?php
/**
 * Created by PhpStorm.
 * User: v.morgunov
 * Date: 12.07.2017
 * Time: 12:38
 */

$mv_json_string = <<<'TAG'
{
	"employeeSummary": []
}
TAG;

$mv_json_string2 =<<<'TAG'
{"employeeSummary": [
	{
		"author": "Бородин Дмитрий Юрьевич", 
		"fullSumm": 370,
		"divisionName":	"77/05 Домодедово", 
		"purchaseCategoryInfo": [{"category": "Coffee",
          "qty": 1,
          "categorySumm": 370,
          "percent": 100
        }
      ]
    },
    {
	    "author": "Гакчаев Артур Саидович",
      "fullSumm": 4075,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 7,
          "categorySumm": 2385,
          "percent": 58.527607
        },
        {
	        "category": "Drink",
          "qty": 5,
          "categorySumm": 1360,
          "percent": 33.374233
        },
        {
	        "category": "Food",
          "qty": 1,
          "categorySumm": 330,
          "percent": 8.098159
        }
      ]
    },
    {
	    "author": "Грушина Наталья Викторовна",
      "fullSumm": 23245,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 26,
          "categorySumm": 9540,
          "percent": 41.041084
        },
        {
	        "category": "Drink",
          "qty": 12,
          "categorySumm": 3580,
          "percent": 15.401161
        },
        {
	        "category": "Food",
          "qty": 15,
          "categorySumm": 4775,
          "percent": 20.542052
        },
        {
	        "category": "Others",
          "qty": 19,
          "categorySumm": 5350,
          "percent": 23.015702
        }
      ]
    },
    {
	    "author": "Долгушева Елена Анатольевна",
      "fullSumm": 14970,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 15,
          "categorySumm": 5730,
          "percent": 38.276553
        },
        {
	        "category": "Drink",
          "qty": 7,
          "categorySumm": 2625,
          "percent": 17.53507
        },
        {
	        "category": "Food",
          "qty": 13,
          "categorySumm": 4430,
          "percent": 29.592518
        },
        {
	        "category": "Others",
          "qty": 9,
          "categorySumm": 2185,
          "percent": 14.595858
        }
      ]
    },
    {
	    "author": "Павленко Станислав Викторович",
      "fullSumm": 1050,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 3,
          "categorySumm": 990,
          "percent": 94.285714
        },
        {
	        "category": "Others",
          "qty": 2,
          "categorySumm": 60,
          "percent": 5.714285
        }
      ]
    },
    {
	    "author": "Хромова Ирина Сергеевна",
      "fullSumm": 28470,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 29,
          "categorySumm": 10900,
          "percent": 38.285914
        },
        {
	        "category": "Drink",
          "qty": 13,
          "categorySumm": 5965,
          "percent": 20.951879
        },
        {
	        "category": "Food",
          "qty": 18,
          "categorySumm": 7605,
          "percent": 26.712328
        },
        {
	        "category": "Others",
          "qty": 16,
          "categorySumm": 4000,
          "percent": 14.049877
        }
      ]
    },
    {
	    "author": "Чернов Валерий Игоревич",
      "fullSumm": 2365,
      "divisionName": "77/05 Домодедово",
      "purchaseCategoryInfo": [
        {
	        "category": "Coffee",
          "qty": 3,
          "categorySumm": 1090,
          "percent": 46.088794
        },
        {
	        "category": "Food",
          "qty": 1,
          "categorySumm": 345,
          "percent": 14.587737
        },
        {
	        "category": "Others",
          "qty": 3,
          "categorySumm": 930,
          "percent": 39.323467
        }
      ]
    }
  ]
}
TAG;

/** @var TYPE_NAME $mv_report_result */
$mv_report_result = json_decode($mv_json_string);
$mv_report_result2 = json_decode($mv_json_string2);

/**
 * @param $mv_att
 * @param $mv_key
 * @param $mv_vol
 */
function mv_take_val_with_key ($mv_att, $mv_key, $mv_vol ){
	foreach ($mv_att as $key => $value ) {
		var_dump($key); // 0,1,2,3 и т.д.
		var_dump($value);
		if ($value->$mv_key == $mv_vol) {
			echo "Yeeesss!";
			echo "<br>";
			echo $value->qty;
			echo "<br>";
			echo $value->categorySumm;
			echo "<br>";
			echo $value->percent;
			echo "<br>";

		}
	}
}
$mv_att = $mv_report_result->employeeSummary[1]->purchaseCategoryInfo;
var_dump($mv_report_result);
var_dump($mv_report_result2);

if (! empty( $mv_report_result->employeeSummary ) ) {
	echo '$mv_report_result not empty';} else{
	echo '$mv_report_result empty';
}
if ( ! empty( $mv_report_result2->employeeSummary ) ) {
	echo '$mv_report_result2 Not empty';
} else {echo '$mv_report_result2 empty';}
//echo $mv_report_result2->employeeSummary'
//echo count($mv_report_result->employeeSummary);
//mv_take_val_with_key($mv_att, 'category', 'Coffee');
//var_dump($mv_report_result);
//echo count($mv_json_string2->employeeSummary);
//echo isset($mv_report_result->employeeSummary);
//echo isset($mv_report_result2.employeeSummary);
//if (null !== $mv_report_result->employeeSummary) {echo '$mv_report_result Not null'; }
//if (null !== $mv_report_result2->employeeSummary) {echo '$mv_report_result2 Not null';}
// echo is_object($mv_report_result);
//echo empty($mv_report_result);
//echo is_object($mv_report_result2);

?>

