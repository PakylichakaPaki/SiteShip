<?php

use yii\db\Migration;

/**
 * Class m240208_add_date_of_creation_to_job_opening
 */
class m240208_add_date_of_creation_to_job_opening extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('job_opening', 'date_of_creation', $this->dateTime()->notNull());
        
        // Заполняем поле date_of_creation данными недельной давности
        $this->execute("UPDATE job_opening SET date_of_creation = DATE_SUB(NOW(), INTERVAL 7 DAY)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('job_opening', 'date_of_creation');
    }
}
