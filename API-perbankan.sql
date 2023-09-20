--buat akun
CREATE TABLE account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_number VARCHAR(20) NOT NULL,
    account_holder VARCHAR(255) NOT NULL,
    balance DECIMAL(10, 2) NOT NULL
);

INSERT INTO account (account_number, account_holder, balance) VALUES
('ACC123456', 'Iffa', 5000.00),
('ACC789012', 'Ara', 8000.00),
('ACC345678', 'Rey', 3000.00),
('ACC901234', 'Basir', 6000.00),
('ACC567890', 'Hasma', 7500.00);


--buat transaksi
CREATE TABLE transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_number VARCHAR(255) NOT NULL,
    transaction_type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Memasukkan data tambahan ke dalam tabel transaksi
INSERT INTO `transaction` (`account_number`, `description`, `amount`, `transaction_date`)
VALUES
  ('ACC789012', 'deposit', 1500.00, '2023-09-20 20:55:10'),
  ('ACC345678', 'withdrawal', 500.00, '2023-09-20 20:55:10'),
  ('ACC901234', 'deposit', 2000.00, '2023-09-20 20:55:10'),
  ('ACC567890', 'withdrawal', 1000.00, '2023-09-20 20:55:10'),
  ('ACC123456', 'transfer', 750.00, '2023-09-20 20:55:10'),
  ('ACC789012', 'transfer', 300.00, '2023-09-20 20:55:10'),
  ('ACC345678', 'withdrawal', 200.00, '2023-09-20 20:55:10');
