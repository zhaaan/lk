<?php

use yii\db\Migration;

class m170806_221839_user_personal_account extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'personal_account', $this->string()->notNull());
    }

    public function safeDown()
    {
        echo "m170806_221839_user_personal_account cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170806_221839_user_personal_account cannot be reverted.\n";

        return false;
    }
    */
}
