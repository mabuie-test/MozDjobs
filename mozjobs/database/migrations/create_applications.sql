CREATE TABLE IF NOT EXISTS applications (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  job_id BIGINT NOT NULL,
  professional_id BIGINT NOT NULL,
  cover_letter TEXT,
  status ENUM('submitted','shortlisted','rejected','accepted') DEFAULT 'submitted',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES jobs(id),
  FOREIGN KEY (professional_id) REFERENCES users(id)
);
