
CREATE DATABASE cs234project;
USE cs234project;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer'
);

CREATE TABLE Bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    number_of_people INT ,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    class_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE PotteryItems (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL,
    image_url VARCHAR(400) DEFAULT 'https://imgs.search.brave.com/ceZWcrNvZd70s6H3TdelSjRuBgQDyS7WS3DBtM0eX0E/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvMTgy/ODg1ODMyL3Bob3Rv/L3BvdHRlcnktYXJ0/LmpwZz9zPTYxMng2/MTImdz0wJms9MjAm/Yz0wSm9PMk9kZVhi/VktzdTRZenVwQUZ1/T0ZKcUFiSTRRaHI2/N1ZWWGZDQVlvPQ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Testimonials (
    username VARCHAR(50) DEFAULT 'Anonymous',
    review VARCHAR(150),
    rating INT CHECK (rating BETWEEN 1 AND 5),
);