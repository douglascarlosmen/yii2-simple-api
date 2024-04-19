    <?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m240418_184129_create_client_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'cep' => $this->string(10),
            'street' => $this->string(255),
            'number' => $this->string(10),
            'city' => $this->string(100),
            'state' => $this->string(2),
            'complement' => $this->string(255),
            'photo' => $this->string(255),
            'gender' => $this->string(1),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('client');
    }
}
