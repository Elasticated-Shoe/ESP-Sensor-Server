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
}