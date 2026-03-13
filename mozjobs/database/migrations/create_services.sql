CREATE TABLE IF NOT EXISTS services (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  professional_id BIGINT NOT NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL,
  approved TINYINT(1) DEFAULT 0,
  FOREIGN KEY (professional_id) REFERENCES users(id)
);
