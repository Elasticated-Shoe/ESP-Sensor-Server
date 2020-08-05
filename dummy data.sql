INSERT INTO users (userEmail, userPass, isAdmin, isLocked) VALUES ('email@email.com', 'blargh', 1, 0);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 1', 1, 1, 'Test One', '25', 'Temperature', 'Celsius', 'Home', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 2', 1, 1, 'Test Two', '27', 'Temperature', 'Celsius', 'Outside', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 3', 1, 1, 'Test Three', '28', 'Temperature', 'Celsius', 'Home', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 4', 1, 0, 'Test Four', '10', 'Temperature', 'Celsius', 'Home', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 5', 1, 1, 'Test Five', '22', 'Temperature', 'Celsius', 'Outside', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 6', 1, 1, 'Test Six', '25', 'Temperature', 'Celsius', 'Home', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 7', 1, 1, 'Test Seven', '34', 'Temperature', 'Celsius', 'Attic', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 8', 1, 1, 'Test Eight', '19', 'Temperature', 'Celsius', 'Attic', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 9', 1, 0, 'Test Nine', '16', 'Temperature', 'Celsius', 'Home', 1, NOW()
);

INSERT INTO sensorMetadata (
        sensorName, sensorOwner, sensorPublic, displayName, lastValue, sensorType, sensorUnits, sensorLocation, sensorVersion, lastSeen
    ) VALUES (
        'test 10', 1, 1, 'Test Ten', '15', 'Temperature', 'Celsius', 'Home', 1, NOW()
);