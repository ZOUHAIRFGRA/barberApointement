-- executer ce code dans sql a phpmyadmin http://localhost/phpmyadmin/index.php?route=/server/sql
CREATE DATABASE IF NOT EXISTS salonCoiffure_3;

USE salonCoiffure_3;

-- Create client table
CREATE TABLE IF NOT EXISTS client (
  id_client INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  prix DECIMAL(10, 2) NOT NULL,
  date DATE NOT NULL
);
 
-- Create typeSoin table
CREATE TABLE IF NOT EXISTS typeSoin (
  id_typeSoin INT AUTO_INCREMENT PRIMARY KEY,
  nomSoin VARCHAR(255) NOT NULL
);

-- Create appointments table
CREATE TABLE IF NOT EXISTS appointments (
  id_appointment INT AUTO_INCREMENT PRIMARY KEY,
  id_client INT NOT NULL,
  id_typeSoin INT NOT NULL,
  FOREIGN KEY (id_client) REFERENCES client(id_client),
  FOREIGN KEY (id_typeSoin) REFERENCES typeSoin(id_typeSoin)
);

-- Insert data into typeSoin table
INSERT IGNORE INTO typeSoin (nomSoin) VALUES
  ('soin visage'),
  ('vernis'),
  ('chauveau');

-- Create the user table
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `pass` varchar(30) NOT NULL
);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

INSERT INTO `user` (`id`, `login`, `pass`) VALUES
(1,'admin', 'admin'),
(2,'admin2', 'admin');
