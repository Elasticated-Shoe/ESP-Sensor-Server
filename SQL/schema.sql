CREATE DATABASE ESP_Project;

CREATE TABLE users (
    username VARCHAR(21) NOT NULL,
    userPass VARBINARY(255) NOT NULL,
    userEmail VARCHAR(320) NOT NULL,
    isLocked INT(11),
    PRIMARY KEY (username)
);
CREATE TABLE userFailedLogins (
    origin VARBINARY(16) NOT NULL,
    attemptTimestamp INT(11) NOT NULL
);
CREATE TABLE userPermissions (
    username VARCHAR(21) NOT NULL,
    readArchive BIT NOT NULL,
    writeArchive BIT NOT NULL,
    writeMeta BIT NOT NULL,
    writeNotification BIT NOT NULL,    
    readNotification BIT NOT NULL,    
    addPermission BIT NOT NULL,
    createUser BIT NOT NULL,
    loginUser BIT NOT NULL,
    PRIMARY KEY (username)
);

CREATE TABLE eventLog (
    eventId INT(11) NOT NULL,
    eventName VARCHAR(15) NOT NULL,
    eventTimestamp INT(11) NOT NULL,
    eventDesc VARCHAR(255) NOT NULL,
    userInformed BIT NOT NULL,
    userAck BIT NOT NULL,
    PRIMARY KEY (eventId)
);
CREATE TABLE eventTypes (
    eventName VARCHAR(15) NOT NULL,
    eventSensor VARCHAR(255) NOT NULL,
    eventAction VARCHAR(15) NOT NULL,
    eventThreshold VARCHAR(15),
    PRIMARY KEY (eventName)
);
CREATE TABLE sensorMetadata (
    sensor VARCHAR(255) NOT NULL,
    reading VARCHAR(255),
    sensorType VARCHAR(255),
    sensorLocation VARCHAR(255),
    sensorVersion TINYINT,
    lastSeen INT(11),
    PRIMARY KEY (sensor)
);
CREATE TABLE sensorData (
    readingTimestamp INT(11) NOT NULL,
    PRIMARY KEY (readingTimestamp)
);
CREATE TABLE sensorUptime (
    dayTimestamp INT(11) NOT NULL,
    PRIMARY KEY (dayTimestamp)
)