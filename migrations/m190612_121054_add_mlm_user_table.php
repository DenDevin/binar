<?php

use yii\db\Migration;

/**
 * Class m190612_121054_add_mlm_user_table
 */
class m190612_121054_add_mlm_user_table extends Migration
{


    public function up()
    {
        $this->createTable('mlm_user', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'position' => $this->integer(),
            'path' => $this->string(12288),
            'level' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('mlm_user');
    }

}
