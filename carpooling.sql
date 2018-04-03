DROP TABLE IF EXISTS bid;
DROP TABLE IF EXISTS bidHistory;
DROP TABLE IF EXISTS advertisements;
DROP TABLE IF EXISTS advertisementsHistory;
DROP TABLE IF EXISTS useraccount;
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
    is_admin BOOLEAN DEFAULT FALSE,
    CHECK((is_driver = FALSE AND vehicle_plate IS NULL AND capacity IS NULL) OR (is_driver = TRUE AND vehicle_plate IS NOT NULL AND capacity IS NOT NULL)),
    CHECK(capacity > 0 AND capacity < 8),
    CHECK(gender = 'Male' OR gender = 'Female')
);

CREATE TABLE locations(
  location VARCHAR(40) PRIMARY KEY
);

CREATE TABLE advertisements (
    advertisementID BIGSERIAL PRIMARY KEY,
    email_of_driver VARCHAR(40) REFERENCES useraccount(email) ON UPDATE CASCADE ON DELETE CASCADE,
    start_location VARCHAR(40) NOT NULL REFERENCES locations(location),
    end_location VARCHAR(40) NOT NULL REFERENCES locations(location),
    creation_date_and_time TIMESTAMP NOT NULL,
    date_of_pickup DATE NOT NULL,
    time_of_pickup TIME NOT NULL,
    closed BOOLEAN DEFAULT FALSE,
    self_select BOOLEAN DEFAULT TRUE,
    CONSTRAINT same_start_end_location CHECK(start_location != end_location),
    CONSTRAINT pickup_date_before_current_date CHECK(date_of_pickup >= current_date),
    CONSTRAINT pickup_time_before_current_time CHECK((date_of_pickup = current_date AND time_of_pickup > current_time) OR (date_of_pickup > current_date))
);

CREATE TABLE advertisementsHistory (
    email_of_driver VARCHAR(40) REFERENCES useraccount(email) ON UPDATE CASCADE ON DELETE CASCADE,
    start_location VARCHAR(40) NOT NULL REFERENCES locations(location) ON UPDATE CASCADE ON DELETE SET NULL,
    end_location VARCHAR(40) NOT NULL REFERENCES locations(location) ON UPDATE CASCADE ON DELETE SET NULL,
    creation_date_and_time TIMESTAMP NOT NULL,
    date_of_pickup DATE NOT NULL,
    time_of_pickup TIME NOT NULL,
    self_select BOOLEAN NOT NULL
);

CREATE TABLE bid (
    email VARCHAR(40) NOT NULL,
    advertisementID BIGINT NOT NULL,
    status VARCHAR(9) NOT NULL DEFAULT 'Pending',
    price NUMERIC(5, 2) NOT NULL,
    creation_date_and_time TIMESTAMP NOT NULL,
    PRIMARY KEY (email, advertisementID),
    FOREIGN KEY (email) REFERENCES useraccount(email) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (advertisementID) REFERENCES advertisements(advertisementID) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK(price > 0),
    CHECK(status = 'Pending' OR status = 'Rejected' OR status = 'Accepted' OR status = 'Offer retracted' OR status = 'Offer expired')
);

CREATE TABLE bidHistory (
    email VARCHAR(40) NOT NULL,
    status VARCHAR(9) NOT NULL,
    price NUMERIC(5, 2) NOT NULL,
    creation_date_and_time TIMESTAMP NOT NULL,
    start_location VARCHAR(40) NOT NULL REFERENCES locations(location) ON UPDATE CASCADE ON DELETE SET NULL,
    end_location VARCHAR(40) NOT NULL REFERENCES locations(location) ON UPDATE CASCADE ON DELETE SET NULL,
    date_of_pickup DATE NOT NULL,
    time_of_pickup TIME NOT NULL,
    FOREIGN KEY (email) REFERENCES useraccount(email) ON UPDATE CASCADE ON DELETE CASCADE
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
