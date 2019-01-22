<?php
    function fetchRecent() {
        global $config;
        // connect to database
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $fetchRecentReadings = $conn->prepare("SELECT sensor, reading, lastSeen, sensorType, sensorLocation FROM mostRecentData;");
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
        $query = "SELECT readingTimestamp, %s FROM allData %s;";
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
        $recentReadings = $conn->prepare("INSERT INTO mostRecentData (sensor,reading,lastSeen) VALUES (?,?,?)
                                            ON DUPLICATE KEY UPDATE sensor=?,
                                                                    reading=?,
                                                                    lastSeen=?");
        $recentReadings->bind_param("siisii", $sensorName, $sensorValue, $lastSeen, $sensorName, $sensorValue, $lastSeen);
        // get list of column names in allData table
        $allColumns = $conn->prepare("SHOW COLUMNS FROM allData;");
        $allColumns->execute();
        $allColumnsList = array();
        $result = $allColumns->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            if($row[0] !== "readingTimestamp") {
                array_push($allColumnsList, $row[0]);
            }
        }
        $allColumns->close();
        // loop over data and execute changes
        foreach($_POST["data"] as $sensorName => $sensorData) {
            // escaped as i stopped using parameterised queries because i am an idiot
            $sensorName = $conn->real_escape_string($sensorName);
            $sensorValue = $conn->real_escape_string($sensorData["value"]);
            $lastSeen = $sensorData["time"]; // doesnt need to be escaped as only used in prepared statement
            // add or update sensor entry in mostRecentData
            $recentReadings->execute();
            // if this column is not in the database, add it
            if(!in_array($sensorName, $allColumns))
            {
                $AddColumnSQL = "ALTER TABLE allData ADD %s int(11);";
                $AddColumnSQL = sprintf($AddColumnSQL, $sensorName);
                $conn->query($AddColumnSQL);
            }
            // insert data for this sensor // if past time could be added as parameter could use to ammend old results
            $archiveSQL = "INSERT INTO allData (readingTimestamp, %s) VALUES (%d, %d) ON DUPLICATE KEY UPDATE %s=%d;";
            $archiveSQL = sprintf($archiveSQL,
                $sensorName,    
                $severTime,
                $sensorValue,
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
        $query = "ALTER TABLE allData DROP COLUMN %s;";
        $query = sprintf($query, $sensorName);
        $conn->query($query);
        $conn->close();
    }
    function removeRow($sensorName) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("DELETE FROM mostRecentData WHERE sensor=?;");
        $query->bind_param("s", $sensorName);
        $query->execute();
        $query->close();
        $conn->close();
    }
    function deleteOld($deleteBefore) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("DELETE FROM allData WHERE readingTimestamp < ?;");
        $query->bind_param("i", $deleteBefore);
        $query->execute();
        $query->close();
        $conn->close();
    }
    function editMeta($column, $value, $sensorName) {
        global $config;
        $conn = new mysqli($config["servername"], $config["username"], $config["password"], $config["database"]);
        $query = $conn->prepare("UPDATE mostRecentData SET " . $column . "=? WHERE sensor=?;");
        $query->bind_param("ss", $value, $sensorName);
        $query->execute();
        $query->close();
        $conn->close();
    }
?>