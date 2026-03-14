CREATE TABLE IF NOT EXISTS disputes (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  opened_by BIGINT NOT NULL,
  reason TEXT NOT NULL,
  status ENUM('open','resolved') DEFAULT 'open',
  resolution TEXT,
  resolved_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (opened_by) REFERENCES users(id)
);
