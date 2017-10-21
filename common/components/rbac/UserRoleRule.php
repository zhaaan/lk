<?php
namespace common\components\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\User;


class UserRoleRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        $allowed = false;

        $user = User::findOne($user);
        //see roles /common/config/main.php authManager

        if ($user) {
            if ($item->name == 'Admin') {
                $allowed = $user->isAdmin();
            } elseif ($item->name === 'Client') {
                $allowed =$user->isClient();
            }
        }
        return $allowed;
    }
}