<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Http\Requests\PostDataRequest;
use App\Http\Requests\PostDataBulkRequest;
use App\sensorData;
use App\sensorMetaData;

class DataController extends Controller {
    public function getReadings(GetDataRequest $request) {
        $validatedData = $request->validated();
        
        return sensorData::whereIn("sensorId", $validatedData["id"])
                        ->where('sensorDatetime', '>', $validatedData["start"])
                        ->where('sensorDatetime', '<', $validatedData["end"])
                        ->get();
    }
    public function createReading(PostDataRequest $request) {
        $validatedData = $request->validated();

        $newReading = new sensorData();
        $newReadingData = $request->only($newReading->getFillable());

        $newReading->fill($newReadingData);

        $selectedSensor = sensorMetadata::find($validatedData["sensorId"]);
        $selectedSensor["lastSeen"] = $validatedData["sensorDatetime"];
        $selectedSensor["lastValue"] = $validatedData["sensorValue"];
        $selectedSensor->save();

        $newReading->save();
        
        return array(
            "Message" => "Action Succesful"
        );
    }
    public function batchCreateReadings(PostDataBulkRequest $request) {
        $validatedData = $request->validated();

        sensorData::insert($validatedData);

        $mostRecentLookup = array();
        foreach($validatedData as $readingIndex=>$readingValue) {
            $currentLatest = null;
            $readingDatetime = date_create_from_format("Y-m-d H:i:s", $readingValue["sensorDatetime"] );

            if(!array_key_exists( $readingValue["sensorId"], $mostRecentLookup) ) {
                $mostRecentLookup[ $readingValue["sensorId"] ] = array();
                $currentLatest = $mostRecentLookup[ $readingValue["sensorId"] ]["datetime"] = $readingDatetime;
                $mostRecentLookup[ $readingValue["sensorId"] ]["index"] = $readingIndex;
            }
            else {
                $currentLatest = $mostRecentLookup[ $readingValue["sensorId"] ]["datetime"];
            }

            if($readingDatetime > $currentLatest) {
                $mostRecentLookup[ $readingValue["sensorId"] ]["datetime"] = $readingDatetime;
                $mostRecentLookup[ $readingValue["sensorId"] ]["index"] = $readingIndex;
            }
        }
        foreach( array_keys($mostRecentLookup) as $sensorId) {
            $sensorValue = $validatedData[ $mostRecentLookup[$sensorId]["index"] ];

            $selectedSensor = sensorMetadata::find($sensorId);
            $selectedSensor["lastSeen"] = $sensorValue["sensorDatetime"];
            $selectedSensor["lastValue"] = $sensorValue["sensorValue"];
            $selectedSensor->save();
        }

        return array(
            "Message" => "Action Succesful"
        );
    }
}