CREATE DATABASE ESP_Project;

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

CREATE TABLE eventTypes (
    eventId INT NOT NULL AUTO_INCREMENT,
    eventOwner int NOT NULL,
    eventName VARCHAR (30) NOT NULL,
    eventSensor INT NOT NULL,
    eventAction VARCHAR(15) NOT NULL,
    eventData VARCHAR(255),

    FOREIGN KEY (eventSensor) REFERENCES sensorMetadata(sensorId),
    FOREIGN KEY (eventOwner) REFERENCES users(userId),
    INDEX (eventOwner),
    PRIMARY KEY (eventId)
);
CREATE TABLE eventLog (
    logId INT NOT NULL AUTO_INCREMENT,
    eventId INT NOT NULL,
    eventName VARCHAR(15) NOT NULL,
    eventSensor INT NOT NULL,
    eventTime DATETIME NOT NULL,
    eventOngoing BIT NOT NULL,
    eventDesc VARCHAR(255) NOT NULL,
    userInformed BIT NOT NULL,
    userAck BIT NOT NULL,

    FOREIGN KEY (eventSensor) REFERENCES sensorMetadata(sensorId),
    FOREIGN KEY (eventId) REFERENCES eventTypes(eventId),
    INDEX (eventId, eventOngoing, eventTime),
    PRIMARY KEY (logId)
);

CREATE TABLE sensorData (
    sensorId INT NOT NULL,
    sensorDatetime DATETIME NOT NULL,
    sensorValue VARCHAR(255) NOT NULL,

    FOREIGN KEY (sensorId) REFERENCES sensorMetadata(sensorId),
    INDEX (sensorId, sensorDatetime)
) ROW_FORMAT=COMPRESSED;