CREATE DATABASE ESP_Project;

USE ESP_Project;

CREATE TABLE users (
    userId INT NOT NULL AUTO_INCREMENT,
    userEmail VARCHAR(255) NOT NULL,
    userPass VARCHAR(255) NOT NULL,
    isAdmin BIT NOT NULL,
    isLocked BIT NOT NULL,

    INDEX (userEmail),
    PRIMARY KEY (userId)
);
CREATE TABLE userFailedLogins (
    userId int NOT NULL,
    attemptCount TINYINT NOT NULL,
    attemptDatetime DATETIME NOT NULL,

    FOREIGN KEY (userId) REFERENCES users(userId),
    PRIMARY KEY (userId)
);

CREATE TABLE sensorMetadata (
    sensorId INT NOT NULL AUTO_INCREMENT,
    sensorName VARCHAR(255) NOT NULL,
    sensorOwner int NOT NULL,
    sensorPublic BIT NOT NULL,
    displayName VARCHAR(255) NOT NULL,
    lastValue VARCHAR(255),
    sensorType VARCHAR(255),
    sensorUnits Varchar(255),
    sensorLocation VARCHAR(255),
    sensorVersion TINYINT,
    lastSeen DATETIME,

    FOREIGN KEY (sensorOwner) REFERENCES users(userId),
    CONSTRAINT uniqueOwnerName UNIQUE (sensorName, sensorOwner),
    INDEX (sensorOwner, sensorId),
    PRIMARY KEY (sensorId)
);

CREATE TABLE sensorData (
    dataId INT NOT NULL AUTO_INCREMENT,
    sensorId INT NOT NULL,
    sensorDatetime DATETIME NOT NULL,
    sensorValue DECIMAL(5, 2) NOT NULL,

    FOREIGN KEY (sensorId) REFERENCES sensorMetadata(sensorId),
    INDEX (sensorId, sensorDatetime),
    PRIMARY KEY (dataId)
) ROW_FORMAT=COMPRESSED;