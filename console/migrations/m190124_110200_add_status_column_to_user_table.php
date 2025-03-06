<?php

use \yii\db\Migration;

class m190124_110200_add_status_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'status', $this->smallInteger()->notNull()->defaultValue(10)); // добавляем статус
        $this->addColumn('{{%user}}', 'password_reset_token', $this->string()->unique()->defaultValue(null)); // добавляем token для сброса пароля
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'status'); // удаляем статус
        $this->dropColumn('{{%user}}', 'password_reset_token'); // удаляем token для сброса пароля
    }
}
