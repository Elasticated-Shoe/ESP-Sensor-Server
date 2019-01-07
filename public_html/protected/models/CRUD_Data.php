<?php
    function fetchRecent() {
        global $config;
        // connect to database
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["sensorsDatabase"]);
        $fetchRecentReadings = $conn->prepare("SELECT sensor, reading, lastSeen FROM sensors.mostrecentdata;");
        $fetchRecentReadings->execute();
        $fetchRecentReadings->bind_result($sensor, $reading, $lastSeen);
        // map data to array
        while ($fetchRecentReadings->fetch()) {
            $recentReadingResult[$sensor]["reading"] = $reading;
            $recentReadingResult[$sensor]["lastSeen"] = $lastSeen;
        }
        // close connections
        $fetchRecentReadings->close();
        $conn->close();
        return $recentReadingResult;
    }
    function insertData() {
        global $config;
        // get time
        $severTime = time();
        $severTime = (int)$severTime;
        // insert data supplied in post into the current sensor reading table
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["sensorsDatabase"]);
        // prepare statement (updates if present, inserts if not)
        $recentReadings = $conn->prepare("INSERT INTO sensors.mostrecentdata (sensor,reading,lastSeen) VALUES (?,?,?)
                                            ON DUPLICATE KEY UPDATE sensor=?,
                                                                    reading=?,
                                                                    lastSeen=?");
        $recentReadings->bind_param("siisii", $sensorName, $sensorValue, $lastSeen, $sensorName, $sensorValue, $lastSeen);
        // loop over data and execute changes
        foreach($_POST["data"] as $sensorName => $sensorData) {
            $sensorName = $conn->real_escape_string($sensorName);
            $sensorValue = $conn->real_escape_string($sensorData["value"]);
            $lastSeen = $sensorData["time"]; // doesnt need to be escaped as only used in prepared statement
            $recentReadings->execute();
            // couldnt find the right sql to use in prepared statement so had to use query instead
            // insert column, errors and continues if already exists
            // i hate the following code
            $columnSQL = "ALTER TABLE sensors.alldata ADD COLUMN IF NOT EXISTS %s int(11);";
            $columnSQL = sprintf($columnSQL, $sensorName);
            $conn->query($columnSQL);
            // insert time
            $archiveSQL = "INSERT INTO sensors.alldata (readingTimestamp) VALUES (%d) ON DUPLICATE KEY UPDATE readingTimestamp=%d;";
            $archiveSQL = sprintf($archiveSQL,
                $severTime,
                $severTime);
            $conn->query($archiveSQL);
            // insert data for this sensor
            $archiveSQL = "INSERT INTO sensors.alldata (readingTimestamp) VALUES (%d) ON DUPLICATE KEY UPDATE %s=%d;";
            $archiveSQL = sprintf($archiveSQL,
                $severTime,
                $sensorName,
                $sensorValue);
            $conn->query($archiveSQL);
        }
        echo "Values Inserted\n";
        // close connections
        $recentReadings->close();
        $conn->close();
    }
    function dropColumn() {

    }
    function deleteOld() {

    }
    function fetchMeta() {
        global $config;
        // connect to database
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["sensorsDatabase"]);
        
        $fetchDistinct = $conn->prepare("SELECT DISTINCT sensor, lastSeen FROM sensors.mostrecentdata;");
        $fetchDistinct->execute();
        $fetchDistinct->bind_result($sensor, $lastSeen);
        // map data to array
        while ($fetchDistinct->fetch()) {
            $fetchMetaResult[$sensor]["lastSeen"] = $lastSeen;
        }

        $fetchMeta = $conn->prepare("SELECT sensor, sensorType, sensorLocation FROM sensors.metadata;");
        $fetchMeta->execute();
        $fetchMeta->bind_result($sensor, $sensorLocation, $sensorLocation);
        // map data to array
        while ($fetchMeta->fetch()) {
            $fetchMetaResult[$sensor]["sensorType"] = $sensorLocation;
            $fetchMetaResult[$sensor]["sensorLocation"] = $sensorLocation;
        }
        // close connections
        $fetchMeta->close();
        $conn->close();
        return $fetchMetaResult;
    }
?>