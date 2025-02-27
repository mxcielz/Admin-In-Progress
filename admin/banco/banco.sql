CREATE DATABASE WB;

USE WB;

CREATE TABLE WBLogin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    CNPJ VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(255) NOT NULL,
    date_order DATE NOT NULL,
    status VARCHAR(50) NOT NULL,
    image VARCHAR(255) NOT NULL
);

INSERT INTO WBLogin (id, CNPJ, username, password, email)
VALUES 
(1, '00000000001', 'WB Admin', PASSWORD('123'), 'wbmanutencao@gmail.com');

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO contact_messages (company_name, contact_name, email, phone, message) VALUES
('Empresa A', 'Contato A', 'contatoa@empresa.com', '123456789', 'Mensagem de teste A'),
('Empresa B', 'Contato B', 'contatob@empresa.com', '987654321', 'Mensagem de teste B');