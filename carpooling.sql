DROP TABLE IS EXISTS useraccount;
DROP TABLE IF EXISTS advertisements;
DROP TABLE IF EXISTS bid;

CREATE TABLE useraccount (
    name VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact_number VARCHAR(8) NOT NULL,
    email VARCHAR(40) PRIMARY KEY,
    password VARCHAR(20) NOT NULL,
    vehicle_plate VARCHAR(9) UNIQUE NOT NULL,
    capacity INTEGER NOT NULL,
    is_driver BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE advertisements (
    advertisementID INTEGER PRIMARY KEY,
    email_of_driver VARCHAR(40) REFERENCES useraccount(email),
    start_location VARCHAR(40) NOT NULL,
    end_location VARCHAR(40) NOT NULL,
    creation_date_and_time TIMESTAMP NOT NULL,
    time_of_pickup TIME NOT NULL,
    closed BOOLEAN DEFAULT FALSE,
    self_select BOOLEAN DEFAULT TRUE
);

CREATE TABLE bid (
    email VARCHAR(40) NOT NULL,
    advertisementID INTEGER NOT NULL,
    status VARCHAR(8) NOT NULL DEFAULT 'pending',
    price NUMERIC(5, 2) NOT NULL,
    creation_date_and_time TIMESTAMP NOT NULL,
    PRIMARY KEY (email, advertisementID),
    FOREIGN KEY (email) REFERENCES useraccount(email),
    FOREIGN KEY (advertisementID) REFERENCES advertisements(advertisementID)
);
