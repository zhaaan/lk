<?php
/**
 * Created by PhpStorm.
 * User: ursus
 * Date: 08.08.17
 * Time: 16:52
 */

namespace backend\models;


/**
 * Форма логина  - к основной форме добавляется проверка что пользователь админ
 * Class LoginForm
 * @package backend\models
 */
class LoginForm extends \common\models\LoginForm
{

    public function rules()
    {
        $rules = parent::rules();
        $rules[] =['password', function(){
            $user = $this->getUser();
            if(!$user || ($user && !$user->isAdmin())){
                $this->addError('password', 'У ват нет прав доступа');
            }
        }];
        return $rules;
    }
}