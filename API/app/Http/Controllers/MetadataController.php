<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sensorMetadata;

use App\Http\Requests\PostSensorRequest;
use App\Http\Requests\PutSensorRequest;

class MetadataController extends Controller {
    public function findByUser(Request $request, $user) {
        return sensorMetadata::where("sensorOwner", $user)->get();
    }

    public function createSensorMetadata(PutSensorRequest $request) {
        $validatedData = $request->validated();

        $newSensor = new sensorMetadata();
        $newSensorData = $request->only($newSensor->getFillable());
        $newSensor->fill($newSensorData);

        $newSensor->save();
        
        return array(
            "Message" => "Action Succesful"
        );
    }

    public function updateSensorMetadata(PostSensorRequest $request, $id) {
        $validatedData = $request->validated();

        $selectedSensor = sensorMetadata::find($validatedData["sensorId"]);
        if($selectedSensor === null) {
            return array(
                "Message" => "Sensor {$validatedData['sensorId']} Not Found"
            );
        }

        $selectedSensor->update($validatedData);
        
        return array(
            "Message" => "Action Succesful"
        );
    }

    public function deleteSensorMetadata(PostSensorRequest $request, $id) {
        $validatedData = $request->validated();
        $selectedSensor = sensorMetadata::find( $validatedData["sensorId"] );

        if($selectedSensor === null) {
            return array(
                "Message" => "Sensor {$id} Not Found"
            );
        }

        $selectedSensor->delete();

        return array(
            "Message" => "Action Succesful"
        );
    }
}