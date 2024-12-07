<?php

use yii\db\Migration;

/**
 * Class m231124_201800_add_job_opening_id_to_candidate
 */
class m231124_201800_add_job_opening_id_to_candidate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('candidate', 'job_opening_id', $this->integer()->null());
        
        // Add foreign key
        $this->addForeignKey(
            'fk-candidate-job_opening_id',
            'candidate',
            'job_opening_id',
            'job_opening',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-candidate-job_opening_id', 'candidate');
        $this->dropColumn('candidate', 'job_opening_id');
    }
}
