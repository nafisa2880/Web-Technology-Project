-- Create database
CREATE DATABASE healthinfo;


USE healthinfo;

-- Create medication_reminders table
CREATE TABLE medication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medication_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(255) NOT NULL,
    reminder_time TIME NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);

select * from medication;

