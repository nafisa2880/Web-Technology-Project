CREATE DATABASE healthinfo;
use healthinfo;
CREATE TABLE HealthTips (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Auto-increment for unique IDs
    title VARCHAR(100) NOT NULL,        -- Title of the health tip
    description TEXT NOT NULL,          -- Detailed description of the tip
    category VARCHAR(50)                -- Category of the health tip
);
INSERT INTO HealthTips (title, description, category)
VALUES
    ('Stay Hydrated', 'Drink at least 8 glasses of water daily.', 'General'),
    ('Eat Balanced Meals', 'Ensure your meals include protein, carbohydrates, and vitamins.', 'Diet'),
    ('Get Regular Exercise', 'Aim for at least 30 minutes of physical activity daily.', 'Fitness'),
    ('Adequate Sleep', 'Adults should aim for 7-9 hours of quality sleep every night.', 'General'),
    ('Mental Wellness', 'Practice mindfulness or meditation to reduce stress.', 'Mental Health');
    
    SHOW databases;
    
USE healthinfo;
SHOW TABLES;
SELECT * FROM HealthTips;
