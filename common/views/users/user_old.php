<?php

/**
 * @var $this yii\web\View
 * @var $user User
 */


use common\models\User;
use dosamigos\chartjs\ChartJs;

$this->title = 'Личный кабинет';
?>

<div class="row">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-4">
                <b>Лицевой счет: </b><span><?= $user->personal_account ?></span>
            </div>
            <div class="col-md-4">
                <b>Номер счетчика: </b><span><?= $user->serial_number ?></span>
            </div>
            <div class="col-md-4">
                <b>Адрес: </b><span><?= $user->adress ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Последние показания (на конец суток)
            </div>
            <div class="panel-body text-center">
                <div>
                <?php 
//                    print_r($recentTestimonies1);
		    $tariff=array();
		    $i=0;
                        foreach ($recentTestimonies1 as $item) {
                	    $tariff[$i]=round($item['exp1'],2);
			    echo "\n";
			    $i++;
    		    }
                      echo "<b>Общая сумма:</b><br><b> T0=".$tariff[0]." кВт (сумма тарифов)</b><br> ";
//print_r($tariff);
                      echo "T1=".$tariff[1]." кВт (дневной тариф)<br> ";
                      echo "T2=".$tariff[2]." кВт (ночной тариф)<br> ";
?>                    
                </div>
                <div>
                    <b>На дату: </b><span><?=date("d.m.Y", strtotime($recentTestimonies['expr1']))?></span>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Потребение за текущий месяц
            </div>
            <div class="panel-body text-center">

	              <?php
//                print_r($val_tariff);
                Yii::$app->formatter->locale = 'ru-RU';
                $month_key = Yii::$app->formatter->asDate(time(), 'MMMM y');;
                $sum = 0;
//                print_r($data);
                if(isset($data[$month_key])){
                    $sum = round((float)$data[$month_key], 2);
                }
//                echo $month_key;
                ?>

                <div>
                    <b><?=$sum?></b>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
            Показания на конец дня  за текущий месяц
            </div>
            <div class="panel-body text-center">
                <div>
                <?php
                echo "<table class='table table-striped'>";
                echo "<thead><tr>";
                $raz=0;
                $one=0;
//		print_r($val_tariff);
                foreach ($val_tariff as $item) {
                        echo "<th>".date("d.m.Y", strtotime($item['DT']))."</th>";
                        $raz=round($item['Val_1'],2)-$one;
                        $one=round($item['Val_1'],2);
                        echo "<th>".round($item['Val_1'],2)."</th>";
                        echo "<th>".round($item['Val_2'],2)."</th>";
                        echo "<th>".round($item['Val_3'],2)."</th>";
                        echo "<th>".round($raz,2)."</th>";
	                echo "</tr>";
    		    }
                echo "</thead></table>";

                 $mounts = [];
                foreach($val_tariff as $item) {
                    $time = strtotime($item['DT']);
                    $year_month = Yii::$app->formatter->asDate($time, 'MMMM y');
                    if(!isset($mounts[$year_month])){
                        $mounts[$year_month] = [
                            'start' => (float)$item['Val_1'],
                            'tarif1' => (float)$item['Val_2'],
                            'tarif2' => (float)$item['Val_3'],
                            
                        ];
//                        echo "добавлен в массив 1";
                    }else {
                        $mounts[$year_month]['end'] = (float)$item['Val_1'];
                        $mounts[$year_month]['tarif1_e'] = (float)$item['Val_2'];
                        $mounts[$year_month]['tarif2_e'] = (float)$item['Val_3'];
//                	echo "добавлен в массив 2";
                    }
                }
                foreach ($mounts as  $name => $mount){
//                print_r($mounts);
		echo $name."\n";
		
		$delta=round($mounts[$name]['end']-$mounts[$name]['start'],2);
		$delta_T1=round($mounts[$name]['tarif1_e']-$mounts[$name]['tarif1'],2);
		$delta_T2=round($mounts[$name]['tarif2_e']-$mounts[$name]['tarif2'],2);
		echo "T0=".$delta."\n ";
		echo "T1=".$delta_T1."\n ";
		echo "T2=".$delta_T2."\n<br>";
			foreach($mount as $key => $value)
			{
//			echo "key ".$key." value ".$value."\n";
//			echo $mounts[$name]['start'];
			}
		}
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                foreach ($mounts as  $name => $mount){
			$delta=round($mounts[$name]['end']-$mounts[$name]['start'],2);
			$delta_T1=round($mounts[$name]['tarif1_e']-$mounts[$name]['tarif1'],2);
			$delta_T2=round($mounts[$name]['tarif2_e']-$mounts[$name]['tarif2'],2);
			$Tar1[] = $delta_T1;
			$Tar2[] = $delta_T2;
		}

                echo ChartJs::widget([
                    'type' => 'bar',

                    'data' => [
                        'labels' => array_keys($data),
                        'datasets' => [
                            [
                                'label' => "Сумма тарифов",
                                'backgroundColor' => "rgba(0,99,132,0.6)",
                                'borderColor' => "rgba(0,99,132,1)",
                                'pointBackgroundColor' => "rgba(246,226,169,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(246,226,169,1)",
                                'data' => array_values($data)
                            ],
                            [
                                'label' => "Дневной тариф",
                                'backgroundColor' => "rgba(217,210,11,0.6)",
                                'borderColor' => "rgba(217,210,11,1)",
                                'pointBackgroundColor' => "rgba(246,226,169,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(246,226,169,1)",
                                'data' => array_values($Tar1)
                            ],
                            [
                                'label' => "Ночной тариф",
                                'backgroundColor' => "rgba(8,250,0,0.6)",
                                'borderColor' => "rgba(8,250,0,1)",
                                'pointBackgroundColor' => "rgba(246,226,169,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(246,226,169,1)",
                                'data' => array_values($Tar2)
                            ]
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Потребление по месяцам
                <?php
                Yii::$app->formatter->locale = 'ru-RU';
                $month_key = Yii::$app->formatter->asDate(time(), 'MMMM');;
                $sum = 0;
                if(isset($data[$month_key])){
                    $sum = round((float)$data[$month_key], 2);
                }
                ?>
            </div>
            <div class="panel-body text-center">
                <?php
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Период</th><th>Общая сумма</th><th>Дневной тариф</th><th>Ночной тариф</th></tr>";
                echo "<thead><tr>";
//                foreach ($data as $item => $value) {
//                        echo "<th>".$item."</th>";
//	                echo "<th>".$value."</th></tr>";
//    		    }
                
                foreach ($mounts as  $name => $mount){
			echo "<th>".$name."</th>";
			$delta=round($mounts[$name]['end']-$mounts[$name]['start'],2);
			$delta_T1=round($mounts[$name]['tarif1_e']-$mounts[$name]['tarif1'],2);
			$delta_T2=round($mounts[$name]['tarif2_e']-$mounts[$name]['tarif2'],2);
			echo "<th>".$delta."</th>";
			echo "<th>".$delta_T1."</th>";
			echo "<th>".$delta_T2."</th></tr>";
			}
		echo "</thead></table>";
                ?>
            </div>
        </div>
    </div>
</div>
