<?php

use yii\db\Migration;

class m240124_000001_add_is_read_to_message extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%message}}', 'is_read', $this->tinyInteger(1)->notNull()->defaultValue(0));
        $this->createIndex('idx-message-is_read', '{{%message}}', 'is_read');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-message-is_read', '{{%message}}');
        $this->dropColumn('{{%message}}', 'is_read');
    }
}
