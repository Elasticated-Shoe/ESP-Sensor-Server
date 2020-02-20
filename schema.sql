CREATE DATABASE ESP_Project;

CREATE TABLE users (
    userEmail VARCHAR(255) NOT NULL,
    userPass VARBINARY(255) NOT NULL,
    isLocked BIT NOT NULL,

    PRIMARY KEY (userEmail)
);
CREATE TABLE userFailedLogins (
    userEmail VARCHAR(255) NOT NULL,
    attemptCount TINYINT NOT NULL,
    attemptDatetime DATETIME NOT NULL,

    FOREIGN KEY (userEmail) REFERENCES users(userEmail),
    PRIMARY KEY (userEmail)
);

CREATE TABLE sensorMetadata (
    sensorId INT NOT NULL AUTO_INCREMENT,
    sensorName VARCHAR(255) NOT NULL,
    sensorOwner VARCHAR(21) NOT NULL,
    displayName VARCHAR(255) NOT NULL,
    lastValue VARCHAR(255),
    sensorType VARCHAR(255),
    sensorUnits Varchar(255),
    sensorLocation VARCHAR(255),
    sensorVersion TINYINT,
    lastSeen DATETIME,

    FOREIGN KEY (sensorOwner) REFERENCES users(userEmail),
    CONSTRAINT uniqueOwnerName UNIQUE (sensorName, sensorOwner),
    INDEX (sensorOwner, sensorId),
    INDEX (sensorOwner),
    PRIMARY KEY (sensorId)
);

CREATE TABLE eventTypes (
    eventId INT NOT NULL AUTO_INCREMENT,
    eventOwner VARCHAR(255) NOT NULL,
    eventName VARCHAR (30) NOT NULL,
    eventSensor INT NOT NULL,
    eventAction VARCHAR(15) NOT NULL,
    eventData VARCHAR(255),

    FOREIGN KEY (eventSensor) REFERENCES sensorMetadata(sensorId),
    FOREIGN KEY (eventOwner) REFERENCES users(userEmail),
    INDEX (eventOwner),
    PRIMARY KEY (eventId)
);
CREATE TABLE eventLog (
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
    INDEX (eventId, eventOngoing, eventTime)
);

CREATE TABLE sensorData (
    sensorId INT NOT NULL,
    sensorDatetime DATETIME NOT NULL,
    sensorValue VARCHAR(255) NOT NULL,

    FOREIGN KEY (sensorId) REFERENCES sensorMetadata(sensorId),
    INDEX (sensorId, sensorDatetime)
) ROW_FORMAT=COMPRESSED;