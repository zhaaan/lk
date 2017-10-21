<?php

use yii\db\Migration;

class m170806_185220_add_user_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'role', $this->integer(2)->notNull());
        $this->addColumn('user', 'serial_number', $this->string());

    }

    public function safeDown()
    {
        $this->dropColumn('user', 'role');
        $this->dropColumn('user', 'serial_number');
        echo "m170806_185220_add_user_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170806_185220_add_user_columns cannot be reverted.\n";

        return false;
    }
    */
}
