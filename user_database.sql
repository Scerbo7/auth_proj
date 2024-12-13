CREATE DATABASE user_database;

USE user_database;

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
<<<<<<< HEAD
);
s
=======
);
>>>>>>> 57e433c064ecc2f7c60daa5620acdd038b9389eb
