DROP TABLE IF EXISTS useraccount;
DROP TABLE IF EXISTS advertisements;
DROP TABLE IF EXISTS bid;
DROP TABLE IF EXISTS locations;

CREATE TABLE useraccount (
    name VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact_number VARCHAR(8) NOT NULL,
    email VARCHAR(40) PRIMARY KEY,
    password VARCHAR(20) NOT NULL,
    vehicle_plate VARCHAR(9) UNIQUE,
    capacity INTEGER,
    is_driver BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE locations(
  location VARCHAR(40) PRIMARY KEY
);

CREATE TABLE advertisements (
    advertisementID INTEGER PRIMARY KEY,
    email_of_driver VARCHAR(40) REFERENCES useraccount(email),
    start_location VARCHAR(40) NOT NULL REFERENCES locations(location),
    end_location VARCHAR(40) NOT NULL REFERENCES locations(location),
    creation_date_and_time TIMESTAMP NOT NULL,
    date_of_pickup DATE NOT NULL,
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

INSERT INTO locations VALUES
('Ang Mo Kio'),
('Bedok'),
('Bishan'),
('Boon Lay'),
('Bukit Batok'),
('Bukit Merah'),
('Bukit Panjang'),
('Bukit Timah'),
('Central Water Catchment'),
('Changi'),
('Changi Bay'),
('Choa Chu Kang'),
('Clementi'),
('Downtown Core'),
('Geylang'),
('Hougang'),
('Jurong East'),
('Jurong West'),
('Kallang'),
('Lim Chu Kang'),
('Mandai'),
('Mandai East'),
('Marina South'),
('Marine Parade'),
('Museum'),
('Newton'),
('Novena'),
('Orchard'),
('Outram'),
('Pasir Ris'),
('Paya Lebar'),
('Pioneer'),
('Punggol'),
('Queenstown'),
('River Valley'),
('Rochor'),
('Seletar'),
('Sembawang'),
('Sengkang'),
('Sentosa'),
('Serangoon'),
('Simpang'),
('Singapore River'),
('Straits View'),
('Sungei Kadut'),
('Tampines'),
('Tanglin'),
('Tengah'),
('Toa Payoh'),
('Tuas'),
('Western Islands'),
('Woodlands'),
('Yishun')