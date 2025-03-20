-- Create the database
CREATE DATABASE IF NOT EXISTS vegetable_quiz;
USE vegetable_quiz;

-- Create players table
CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    date_played DATETIME NOT NULL,
    time_started DATETIME NOT NULL,
    time_ended DATETIME NOT NULL,
    duration_seconds INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create fruits_vegetables table
CREATE TABLE IF NOT EXISTS fruits_vegetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    type ENUM('fruit', 'vegetable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample vegetables
INSERT INTO fruits_vegetables (name, image_url, type) VALUES
('Carrot', 'images/carrot.jpg', 'vegetable'),
('Broccoli', 'images/broccoli.jpg', 'vegetable'),
('Tomato', 'images/tomato.jpg', 'vegetable'),
('Potato', 'images/potato.jpg', 'vegetable'),
('Cucumber', 'images/cucumber.jpg', 'vegetable'),
('Spinach', 'images/spinach.jpg', 'vegetable'),
('Lettuce', 'images/lettuce.jpg', 'vegetable'),
('Cabbage', 'images/cabbage.jpg', 'vegetable'),
('Bell Pepper', 'images/bell_pepper.jpg', 'vegetable'),
('Onion', 'images/onion.jpg', 'vegetable'),
('Garlic', 'images/garlic.jpg', 'vegetable'),
('Eggplant', 'images/eggplant.jpg', 'vegetable'),
('Cauliflower', 'images/cauliflower.jpg', 'vegetable'),
('Celery', 'images/celery.jpg', 'vegetable'),
('Mushroom', 'images/mushroom.jpg', 'vegetable');

-- Insert sample high scores
INSERT INTO players (player_name, score, date_played, time_started, time_ended, duration_seconds) VALUES
('Chloe Salvador', 10, '2019-12-24 15:30:00', '2019-12-24 15:29:45', '2019-12-24 15:30:00', 15),
('Azi Acosta', 10, '2020-04-01 10:15:00', '2020-04-01 10:14:42', '2020-04-01 10:15:00', 18),
('Angeli Khang', 9, '2021-05-01 14:20:00', '2021-05-01 14:19:48', '2021-05-01 14:20:00', 12),
('Ayanna Misola', 8, '2018-06-12 09:45:00', '2018-06-12 09:44:46', '2018-06-12 09:45:00', 14);
