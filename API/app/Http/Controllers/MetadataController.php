<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sensorMetadata;

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

    public function createSensorMetadata(Request $request) {
        $validatedData = $this->validate($request, [
            'sensorName' => 'required|max:255',
            'sensorOwner' => 'required|integer',
            'sensorPublic' => 'required|boolean',
            'displayName' => 'required|max:255',
        ]);

        $newSensor = new sensorMetadata();
        $newSensorData = $request->only($newSensor->getFillable());
        $newSensor->fill($newSensorData);

        $newSensor->save();
        
        return array(
            "Message" => "Action Succesful"
        );
    }

    public function updateSensorMetadata(Request $request) {
        $validatedData = $this->validate($request, [
            'sensorId' => 'required|integer',
            'sensorType' => 'max:255'
        ]);

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

    public function deleteSensorMetadata(Request $request, $id) {
        $selectedSensor = sensorMetadata::find( $id );

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