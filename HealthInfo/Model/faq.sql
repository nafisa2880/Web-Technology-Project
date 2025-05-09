use healthinfo;

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