CREATE DATABASE login;

CREATE TABLE login.users (
    username varchar(21) NOT NULL,
    passwordHash varbinary(255) NOT NULL,
    passwordSalt varchar(255) NOT NULL,
    lastFailedLogin int(11),
    failedLoginsInWindow int(2),
    PRIMARY KEY (username)
);

CREATE DATABASE sensors;

CREATE TABLE sensors.mostRecentData (
    sensor varchar(255) NOT NULL,
    reading varchar(255),
    sensorType varchar(255),
    sensorLocation varchar(255),
    sensorVersion varchar(255),
    lastSeen int(11),
    PRIMARY KEY (sensor)
);
CREATE TABLE sensors.allData (
    readingTimestamp int(11) NOT NULL,
    PRIMARY KEY (readingTimestamp)
);