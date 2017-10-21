<?php
/**
 * Created by PhpStorm.
 * User: ursus
 * Date: 07.08.17
 * Time: 11:50
 */

namespace backend\models;
use common\models\User;
use \yii\base\Model;
use yii\db\Query;

/**
 * Модель списка пользователей
 * Class UserList
 * @package backend\models
 */
class UserList extends Model
{
    public $username;
    public $user_id;

    public function query(){
        return (new Query())->select('*')->from('user')->filterWhere([
             'username' => $this->username,
             'id' => $this->user_id,
            'role' => User::ROLE_CLIENT
        ])->orderBy('id');
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'Email',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'personal_account' => 'Номер счета',
            'serial_number' => 'Серийный номер',
        ];
    }
}