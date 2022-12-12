<?php

use yii\db\Migration;

/**
 * Class m221212_132029_startProject
 */
class m221212_132029_startProject extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('url_responses', [
            'id' => $this->primaryKey(),
            'statusCode'=>$this->integer(),
            'headers'=>$this->string(),
            'content'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('url_responses');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221212_132029_startProject cannot be reverted.\n";

        return false;
    }
    */
}
