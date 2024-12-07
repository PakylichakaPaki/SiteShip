<?php

use yii\db\Migration;

/**
 * Class m231207_add_verification_fields
 */
class m231207_add_verification_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем поля для job_opening
        $this->addColumn('job_opening', 'created_at', $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('job_opening', 'last_verified_at', $this->dateTime()->null());
        $this->addColumn('job_opening', 'needs_verification', $this->boolean()->defaultValue(false));
        $this->addColumn('job_opening', 'is_archived', $this->boolean()->defaultValue(false));

        // Добавляем поля для candidate
        $this->addColumn('candidate', 'created_at', $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('candidate', 'last_verified_at', $this->dateTime()->null());
        $this->addColumn('candidate', 'needs_verification', $this->boolean()->defaultValue(false));
        $this->addColumn('candidate', 'is_archived', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем поля из job_opening
        $this->dropColumn('job_opening', 'created_at');
        $this->dropColumn('job_opening', 'last_verified_at');
        $this->dropColumn('job_opening', 'needs_verification');
        $this->dropColumn('job_opening', 'is_archived');

        // Удаляем поля из candidate
        $this->dropColumn('candidate', 'created_at');
        $this->dropColumn('candidate', 'last_verified_at');
        $this->dropColumn('candidate', 'needs_verification');
        $this->dropColumn('candidate', 'is_archived');
    }
}
