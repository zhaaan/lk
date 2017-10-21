<?php
/**
 * Created by PhpStorm.
 * User: ursus
 * Date: 07.08.17
 * Time: 0:59
 */

namespace common\models;


use Yii;
use yii\base\Model;


/**
 * Модкль статистики (данных из второй базы)
 * Class CommunicationStat
 * @package common\models
 */
class CommunicationStat extends Model
{
    /**
     * Получаем показание за день начиная с определенной даты
     * @param $serial_number
     * @param null $date_start
     * @param string $details
     * @return array
     */
    public static function  statistic($serial_number, $date_start = null, $details = 'month'){
        if(is_null($date_start)){
            $date_start = date('Y-m-d H:i:s', strtotime('-1 year'));
        }
        $data =  Yii::$app->db_ext->createCommand('select `values`.val as exp1,b.dat as expr1,b.serialnumber as b from
        (select meters.serialnumber,d.dat,meters.meterid from
         (select f.dat as dat,f.meterid from (select `values`.meterid as meterid, `values`.val as num, `values`.dt as dat from `values` where `values`.tariffid=1) as f) as d 
         inner join `meters` on d.meterid=meters.meterid) as b inner join `values` on b.meterid=`values`.meterid and b.dat=`values`.dt 
         where `values`.tariffid=1 and b.serialnumber= :serial_number and `values`.dt>= :date_start order by exp1 asc', ['serial_number' => $serial_number, 'date_start' => $date_start])->queryAll();
        return self::details($data, $details);
    }

    /**
     * Получаем показание на конец каждого месяца
     * @param $serial_number
     * @return mixed
     */
    public static function recentTestimonies($serial_number){
        return Yii::$app->db_ext->createCommand('select sum(exp1) as exp_sum, exp1, expr1, serialnumber from (select `values`.val as exp1,b.mx as expr1,serialnumber from
  (select meters.serialnumber,mx,meters.meterid from (select max(f.dat) as mx,f.meterid  from
    (select `values`.meterid as meterid, `values`.val as num, `values`.dt as dat from  `values`) as f group by f.meterid) as d inner join `meters`
      on d.meterid=meters.meterid) as b inner join `values` on b.meterid=`values`.meterid and b.mx=`values`.dt where serialnumber=:serial_number) as yy', ['serial_number' => $serial_number])->queryOne();
    }

    /**
     * Подготавливаем (детелезируем) данные для отрисовки в графике - реализовано только для месяца
     * @param $data
     * @param $type
     * @return array
     */
    private static function details($data, $type){
        $result = [];

        switch ($type){
            case "month":
            default:
                $mounts = [];
                foreach($data as $item) {
                    $time = strtotime($item['expr1']);
                    $year_month = Yii::$app->formatter->asDate($time, 'MMMM y');
                    if(!isset($mounts[$year_month])){
                        $mounts[$year_month] = [
                            'start' => (float)$item['exp1'],
                            'end' => (float)$item['exp1'],
                        ];
                    }else {
                        $mounts[$year_month]['end'] = (float)$item['exp1'];
                    }
                }
                foreach ($mounts as  $name => $mount){
                    $result[$name] = round($mount['end'] - $mount['start'], 2);
                }
        }
        return $result;
    }


}

