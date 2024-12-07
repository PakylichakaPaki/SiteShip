ALTER TABLE candidate
ADD COLUMN job_opening_id INT NULL,
ADD CONSTRAINT fk-candidate-job_opening_id
FOREIGN KEY (job_opening_id) REFERENCES job_opening(id)
ON DELETE SET NULL;
