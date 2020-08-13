<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sensorData;
use App\sensorMetaData;
use Validator;

class DataController extends Controller {
    public function getReadings(Request $request) {
        $idArray = $request->get("id");

        $startFrom = date_create_from_format( 'Y-m-d-H:i:s', $request->get("start") );
        $endAt = date_create_from_format( 'Y-m-d-H:i:s', $request->get("end") );

        if($idArray === null || $startFrom === null || $endAt === null) {
            return array(
                "Message" => "Please Provide At Least One Sensor ID To Fetch Data For, A Start Date and A End Date"
            );
        }
        
        return sensorData::whereIn("sensorId", $idArray)
                        ->where('sensorDatetime', '>', $startFrom)
                        ->where('sensorDatetime', '<', $endAt)
                        ->get();
    }
    public function createReading(Request $request, $id) {
        $validatedData = $this->validate($request, [
            'sensorDatetime' => 'required|date',
            'sensorValue' => 'required|numeric|between:0.00,999.99',
        ]);

        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $newReading = new sensorData();
        $newReadingData = $request->only($newReading->getFillable());

        $newReadingData["sensorId"] = $id;

        $newReading->fill($newReadingData);

        $selectedSensor = sensorMetadata::find($id);
        $selectedSensor["lastSeen"] = $validatedData["sensorDatetime"];
        $selectedSensor["lastValue"] = $validatedData["sensorValue"];
        $selectedSensor->save();

        $newReading->save();
        
        return array(
            "Message" => "Action Succesful"
        );
    }
    public function batchCreateReadings(Request $request) {
        $validatedData = $this->validate($request, [
            '*' => 'required|array',
            '*.sensorId' => 'required|integer',
            '*.sensorDatetime' => 'required|date',
            '*.sensorValue' => 'required|numeric|between:0.00,999.99'
        ])["*"];

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