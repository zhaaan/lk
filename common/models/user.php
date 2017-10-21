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
                <b>Лицевой счет:</b><span><?= $user->personal_account ?></span>
            </div>
            <div class="col-md-4">
                <b>Номер счетчика:</b><span><?= $user->serial_number ?></span>
            </div>
            <div class="col-md-4">
                <b>Адрес:</b><span></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Последние показания
            </div>
            <div class="panel-body text-center">

                <div>
                    <b>Общая сумма: </b><span><?=round($recentTestimonies['exp1'], 2)?></span>
                </div>
                <div>
                    <b>На дату: </b><span><?=date("d.m.Y", strtotime($recentTestimonies['expr1']))?></span>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Потребение за текущий месяц
                <?php
                Yii::$app->formatter->locale = 'ru-RU';
                $month_key = Yii::$app->formatter->asDate(time(), 'MMMM y');;
                $sum = 0;
                if(isset($data[$month_key])){
                    $sum = round((float)$data[$month_key], 2);
                }
                echo $month_key;
                ?>
            </div>
            <div class="panel-body text-center">
                <div>
                    <b><?=$sum?></b>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-8">

        <div class="panel panel-default">
            <div class="panel-body">
                <?php

                echo ChartJs::widget([
                    'type' => 'bar',

                    'data' => [
                        'labels' => array_keys($data),
                        'datasets' => [
                            [
                                'label' => "График потребления",
                                'backgroundColor' => "rgba(246,226,169,0.7)",
                                'borderColor' => "rgba(246,226,169,1)",
                                'pointBackgroundColor' => "rgba(246,226,169,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(246,226,169,1)",
                                'data' => array_values($data)
                            ]
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>