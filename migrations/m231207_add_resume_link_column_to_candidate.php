<?php

use yii\db\Migration;

/**
 * Class m231207_add_resume_link_column_to_candidate
 */
class m231207_add_resume_link_column_to_candidate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('candidate', 'resume_link', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('candidate', 'resume_link');
    }
}
