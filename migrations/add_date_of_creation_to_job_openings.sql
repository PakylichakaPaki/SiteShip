ALTER TABLE job_openings ADD COLUMN date_of_creation DATETIME DEFAULT NULL;
UPDATE job_openings SET date_of_creation = DATE_SUB(NOW(), INTERVAL 7 DAY);
ALTER TABLE job_openings MODIFY COLUMN date_of_creation DATETIME NOT NULL;
