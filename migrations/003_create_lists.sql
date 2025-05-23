CREATE TABLE IF NOT EXISTS todo_lists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  hash VARCHAR(255) UNIQUE NOT NULL,
  title VARCHAR(255) DEFAULT 'Untitled',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);