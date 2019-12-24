INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Alpha", 25, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Beta", 14, "Unassigned", "Unassigned", 3, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Gamma", 25, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Delta", 17, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Zeta", 29, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Eta", 22, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Theta", 24, "Unassigned", "Unassigned", 3, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Iota", 26, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Kappa", 16, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Omicron", 15, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Epsilon", 15, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());
INSERT INTO `sensorMetadata` (`sensor`, `reading`, `sensorType`, `sensorLocation`, `sensorVersion`, `lastSeen`) 
VALUES ("Lambda", 17, "Unassigned", "Unassigned", 2, UNIX_TIMESTAMP());

ALTER TABLE `sensorData` ADD (
    Alpha INT(11),
    Beta INT(11),
    Gamma INT(11),
    Delta INT(11),
    Zeta INT(11),
    Eta INT(11),
    Theta INT(11),
    Iota INT(11),
    Kappa INT(11),
    Omicron INT(11),
    Epsilon INT(11),
    Lambda INT(11)
);

SET @startTime = UNIX_TIMESTAMP();
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);
SET @startTime = @startTime + 1;
INSERT INTO `sensorData` (
    `readingTimestamp`, `Alpha`, `Beta`, `Gamma`, `Delta`, `Zeta`, `Eta`, `Theta`, `Iota`, `Kappa`, 
    `Omicron`, `Epsilon`, `Lambda`
) VALUES (
    @startTime, FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5), 
    FLOOR(RAND()*(10-5+1)+5), FLOOR(RAND()*(10-5+1)+5)
);