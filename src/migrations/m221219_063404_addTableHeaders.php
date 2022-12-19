<?php

use yii\db\Migration;

/**
 * Class m221219_063404_addTableHeaders
 */
class m221219_063404_addTableHeaders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('page_headers', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer(),
            'name' => $this->string(),
            'value' => $this->string()
        ]);
        $this->createIndex(
            'valueHeader',
            'page_headers',
            ['value']
        );

        $this->dropColumn('url_responses', 'headers');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('url_responses', 'headers', $this->text());
        $this->dropTable('page_headers');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221219_063404_addTableHeaders cannot be reverted.\n";

        return false;
    }
    */
}
