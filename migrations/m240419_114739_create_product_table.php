<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m240419_114739_create_product_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'client_id' => $this->integer()->notNull(),
            'photo' => $this->string(),
        ]);

        $this->createIndex(
            'idx-product-client_id',
            'product',
            'client_id'
        );

        $this->addForeignKey(
            'fk-product-client_id',
            'product',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-product-client_id',
            'product'
        );
        $this->dropIndex(
            'idx-product-client_id',
            'product'
        );
        $this->dropTable('product');
    }
}
