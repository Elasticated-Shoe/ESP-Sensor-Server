<?php
    function fetchRecent() {
        global $config;
        // connect to database
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $fetchRecentReadings = $conn->prepare("SELECT sensor, reading, lastSeen, sensorType, sensorLocation FROM mostrecentdata;");
        $fetchRecentReadings->execute();
        $fetchRecentReadings->bind_result($sensor, $reading, $lastSeen, $sensorType, $sensorLocation);
        // map data to array
        while ($fetchRecentReadings->fetch()) {
            $recentReadingResult[$sensor]["reading"] = strip_tags($reading);
            $recentReadingResult[$sensor]["lastSeen"] = strip_tags($lastSeen);
            $recentReadingResult[$sensor]["sensorType"] = strip_tags($sensorType);
            $recentReadingResult[$sensor]["sensorLocation"] = strip_tags($sensorLocation);
        }
        // close connections
        $fetchRecentReadings->close();
        $conn->close();
        return $recentReadingResult;
    }
    function fetchRange($sensorsArray, $start = null, $end = null) {
        global $config;
        // connect to database
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        // escape sensors
        $columnNames = $conn->real_escape_string(implode(",", $sensorsArray));
        // escape daterange
        $whereClause = "WHERE readingTimestamp < " . time() . " AND readingTimestamp > " . strtotime("midnight", time());
        if($start !== null && $end !== null) {
            $whereClause = "WHERE readingTimestamp < " . $end . " AND readingTimestamp > " . $start;
            $whereClause = $conn->real_escape_string($whereClause);
        }
        // build query
        $query = "SELECT readingTimestamp, %s FROM alldata %s;";
        $query = sprintf($query, $columnNames, $whereClause);
        // run query, map results
        $result = $conn->query($query);
        while($row = $result->fetch_assoc()) {
            foreach($row as $key => $value) {
                if($key !== "readingTimestamp") {
                    // strip tags as you map values, is this how you do it?
                    $ArchiveReadings[strip_tags($row["readingTimestamp"])][strip_tags($key)]["Reading"] = strip_tags($value);
                }
            }
        }
        // close connections
        $conn->close();
        return $ArchiveReadings;
    }
    function insertData() {
        global $config;
        // get time
        $severTime = time();
        $severTime = (int)$severTime;
        // insert data supplied in post into the current sensor reading table
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        // prepare statement (updates if present, inserts if not)
        $recentReadings = $conn->prepare("INSERT INTO mostrecentdata (sensor,reading,lastSeen) VALUES (?,?,?)
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
            $columnSQL = "ALTER TABLE alldata ADD COLUMN IF NOT EXISTS %s int(11);";
            $columnSQL = sprintf($columnSQL, $sensorName);
            $conn->query($columnSQL);
            // insert time // WARNING poor query ahead
            $archiveSQL = "INSERT INTO alldata (readingTimestamp) VALUES (%d) ON DUPLICATE KEY UPDATE readingTimestamp=%d;";
            $archiveSQL = sprintf($archiveSQL,
                $severTime,
                $severTime);
            $conn->query($archiveSQL);
            // insert data for this sensor
            $archiveSQL = "INSERT INTO alldata (readingTimestamp) VALUES (%d) ON DUPLICATE KEY UPDATE %s=%d;";
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
    function dropColumn($sensorName) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $sensorName = $conn->real_escape_string($sensorName);
        $query = "ALTER TABLE alldata DROP COLUMN %s;";
        $query = sprintf($query, $sensorName);
        $conn->query($query);
        $conn->close();
    }
    function removeRow($sensorName) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("DELETE FROM mostrecentdata WHERE sensor=?;");
        $query->bind_param("s", $sensorName);
        $query->execute();
        $query->close();
        $conn->close();
    }
    function deleteOld($deleteBefore) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("DELETE FROM alldata WHERE readingTimestamp < ?;");
        $query->bind_param("i", $deleteBefore);
        $query->execute();
        $query->close();
        $conn->close();
    }
    function editMeta($column, $value, $sensorName) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("UPDATE mostrecentdata SET " . $column . "=? WHERE sensor=?;");
        $query->bind_param("ss", $value, $sensorName);
        $query->execute();
        $query->close();
        $conn->close();
    }
?>