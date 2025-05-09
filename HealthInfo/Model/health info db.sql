CREATE DATABASE healthinfo;
use healthinfo;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE, -- Add email column
    password VARCHAR(255) NOT NULL,
    role ENUM('patient', 'doctor', 'admin') NOT NULL
);

CREATE TABLE HealthTips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL
);

INSERT INTO HealthTips (title, category, description) VALUES
    ('Exercise Regularly', 'Fitness', 'Engage in at least 30 minutes of moderate exercise daily to maintain physical fitness.'),
    ('Eat More Vegetables', 'Nutrition', 'Include a variety of colorful vegetables in your diet to ensure balanced nutrition.'),
    ('Practice Meditation', 'Mental Health', 'Spend 10-15 minutes daily meditating to reduce stress and improve mental clarity.'),
    ('Stay Hydrated', 'Nutrition', 'Drink at least 8 glasses of water daily to keep your body well-hydrated.'),
    ('Stretch Daily', 'Fitness', 'Incorporate stretching exercises to improve flexibility and reduce muscle tension.'),
    ('Limit Screen Time', 'Mental Health', 'Reduce screen time, especially before bed, to improve sleep quality.'),
    ('Avoid Processed Foods', 'Nutrition', 'Choose whole and fresh foods over processed ones for better health.'),
    ('Strength Training', 'Fitness', 'Add strength training to your routine twice a week to build muscle and support bone health.');


CREATE TABLE faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL
);

INSERT INTO faq (question, answer) VALUES
("What is the recommended amount of water to drink daily?", "It's recommended to drink at least 8-10 glasses (2-2.5 liters) of water per day."),
("How much sleep is necessary for a healthy adult?", "Adults should aim for 7-9 hours of quality sleep each night."),
("What are the benefits of regular exercise?", "Regular exercise helps maintain a healthy weight, improves mood, boosts energy, and prevents chronic diseases."),
("How can I reduce stress in my daily life?", "Practice relaxation techniques like deep breathing, meditation, and yoga. Stay organized and make time for hobbies."),
("What are the early signs of dehydration?", "Common signs include dry mouth, fatigue, dizziness, and dark-colored urine."),
("What should a balanced diet include?", "A balanced diet includes fruits, vegetables, whole grains, lean protein, and healthy fats."),
("How often should I visit the doctor for a check-up?", "It's advisable to have an annual health check-up, even if you're feeling well."),
("What vaccines should adults take?", "Adults should take vaccines like the flu shot annually, and others like tetanus and pneumonia as recommended by their doctor."),
("What is the normal range for blood pressure?", "A normal blood pressure reading is around 120/80 mmHg."),
("How can I boost my immune system?", "Eat a healthy diet, stay physically active, get enough sleep, and reduce stress.");

select * from faq;
show databases;
SELECT * FROM HealthTips;
