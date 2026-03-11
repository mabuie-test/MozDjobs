CREATE TABLE IF NOT EXISTS profiles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  location VARCHAR(120),
  skills TEXT,
  portfolio_url VARCHAR(255),
  reputation DECIMAL(3,2) DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
