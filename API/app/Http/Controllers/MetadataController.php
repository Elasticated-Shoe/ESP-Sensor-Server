<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sensorMetadata;

use App\Http\Requests\MetadataBatchRequest;
use App\Http\Requests\sensorGetParameterRequest;
use App\Http\Requests\MetadataRequest;
use App\Http\Requests\MetadataCreateRequest;

class MetadataController extends Controller {
    public function findById(Request $request, $id) {
        return sensorMetadata::find($id);
    }
    public function findByUser(Request $request, $user) {
        return sensorMetadata::where("sensorOwner", $user)->get();
    }

    public function filterById(Request $request) {
        $idArray = $request->get("id");

        if($idArray === null) {
            return sensorMetadata::all();
        }

        return sensorMetadata::whereIn("sensorId", $idArray)->get();
    }

    public function createSensorMetadata(MetadataCreateRequest $request) {
        $validatedData = $request->validated();

        $newSensor = new sensorMetadata();
        $newSensorData = $request->only($newSensor->getFillable());
        $newSensor->fill($newSensorData);

        $newSensor->save();
        
        return array(
            "Message" => "Action Succesful"
        );
    }

    public function updateSensorMetadata(MetadataRequest $request, $id) {
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

    public function deleteSensorMetadata(sensorGetParameterRequest $request, $id) {
        $validatedData = $request->validated();
        $selectedSensor = sensorMetadata::find( $validatedData["id"] );

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

    public function batchUpdateSensorMetadata(MetadataBatchRequest $request) {
        $validatedData = $request->validated();

        foreach($validatedData as $newSensorData) {
            $selectedSensor = sensorMetadata::find($newSensorData["sensorId"]);
            $selectedSensor->update($newSensorData);
        }
        
        return array(
            "Message" => "Action Succesful"
        );
    }
}