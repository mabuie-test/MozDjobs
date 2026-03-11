CREATE TABLE IF NOT EXISTS orders (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  client_id BIGINT NOT NULL,
  professional_id BIGINT NOT NULL,
  service_id BIGINT,
  status ENUM('open','in_progress','completed','cancelled') DEFAULT 'open',
  amount DECIMAL(12,2) NOT NULL,
  escrow_status ENUM('held','released','refunded') DEFAULT 'held',
  FOREIGN KEY (client_id) REFERENCES users(id),
  FOREIGN KEY (professional_id) REFERENCES users(id)
);
