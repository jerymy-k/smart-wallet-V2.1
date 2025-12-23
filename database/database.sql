-- ==========================
-- SMART WALLET - database.sql
-- ==========================

DROP DATABASE IF EXISTS smart_wallet;
CREATE DATABASE smart_wallet
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE smart_wallet;

-- --------------------------
-- TABLE: users
-- --------------------------
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT uq_users_email UNIQUE (email)
) ENGINE=InnoDB;

-- --------------------------
-- TABLE: categories
-- type: income | expense | both
-- --------------------------
CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(80) NOT NULL,
  type ENUM('income','expense','both') NOT NULL DEFAULT 'both',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_categories_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT uq_category_name_per_user UNIQUE (user_id, name)
) ENGINE=InnoDB;

CREATE INDEX idx_categories_user ON categories(user_id);
CREATE INDEX idx_categories_type ON categories(type);

-- --------------------------
-- TABLE: incomes
-- --------------------------
CREATE TABLE incomes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description VARCHAR(255) NULL,
  income_date DATE NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_incomes_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_incomes_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT chk_incomes_amount CHECK (amount > 0)
) ENGINE=InnoDB;

CREATE INDEX idx_incomes_user ON incomes(user_id);
CREATE INDEX idx_incomes_category ON incomes(category_id);
CREATE INDEX idx_incomes_date ON incomes(income_date);

-- --------------------------
-- TABLE: expenses
-- --------------------------
CREATE TABLE expenses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description VARCHAR(255) NULL,
  expense_date DATE NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_expenses_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_expenses_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT chk_expenses_amount CHECK (amount > 0)
) ENGINE=InnoDB;

CREATE INDEX idx_expenses_user ON expenses(user_id);
CREATE INDEX idx_expenses_category ON expenses(category_id);
CREATE INDEX idx_expenses_date ON expenses(expense_date);

show DATABASES;
use smart_wallet;
show tables