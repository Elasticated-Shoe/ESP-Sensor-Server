<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sensorData;

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
}