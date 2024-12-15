<?php

use yii\db\Migration;

/**
 * Class m240208_add_date_of_creation_to_job_openings
 */
class m240208_add_date_of_creation_to_job_openings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Сначала добавляем столбец, который может быть NULL
        $this->addColumn('job_openings', 'date_of_creation', $this->dateTime()->null());
        
        // Заполняем поле date_of_creation данными недельной давности
        $this->execute("UPDATE job_openings SET date_of_creation = DATE_SUB(NOW(), INTERVAL 7 DAY)");

        // После заполнения данных делаем столбец NOT NULL
        $this->alterColumn('job_openings', 'date_of_creation', $this->dateTime()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('job_openings', 'date_of_creation');
    }
}
