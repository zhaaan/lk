<?php

use common\models\User;
use yii\db\Migration;

class m170807_201110_user_admin extends Migration
{
    public function safeUp()
    {
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@cenergo.ru';
        $user->role = User::ROLE_ADMIN;
        $user->setPassword('123456');
        $user->generateAuthKey();
        $user->save();

    }

    public function safeDown()
    {

        $user = User::findByUsername('admin');
        $user->delete();
        echo "m170807_201110_user_admin cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170807_201110_user_admin cannot be reverted.\n";

        return false;
    }
    */
}
