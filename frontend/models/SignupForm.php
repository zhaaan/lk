<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Query;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $serial_number;
    public $personal_account;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Логин уже занят'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таки email уже существует'],
            ['personal_account', 'trim'],
            ['personal_account', 'required'],
            ['personal_account', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким номером лецивого счета уже существует'],
            ['personal_account', function(){
                $checkSnPaFlag = Yii::$app->db_ext2->createCommand('SELECT * FROM abon WHERE lchet = :personal_account AND num = :serial_number')
                    ->bindValue('serial_number', $this->serial_number)->
                    bindValue('personal_account', $this->personal_account)->execute();
                if(!$checkSnPaFlag){
                    $this->addError('personal_account', 'Не найдено соответствие лицевого счета и номера счетчика');
                }
            }],
            ['serial_number', 'trim'],
            ['serial_number', 'required'],
            ['serial_number', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким серийным номером уже существует'],
            ['serial_number', function(){
                $serial_number = Yii::$app->db_ext->createCommand('SELECT * FROM meters WHERE SerialNumber = :serial_number')->bindValue('serial_number', $this->serial_number)->execute();
                if(!$serial_number){
                    $this->addError('serial_number', 'Данный серийный номер не найден в системе');
                }
            }],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->serial_number = $this->serial_number;
        $user->personal_account = $this->personal_account;
        $user->role = User::ROLE_CLIENT;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'serial_number' => 'Серийный номер',
            'personal_account' => 'Лицевой счет',
        ];
    }
}
