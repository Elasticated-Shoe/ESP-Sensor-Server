<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class MetadataControllerTest extends TestCase
{
    //use DatabaseTransactions;
    
    public function testEmptyMetadataGet() {
        $this->get("sensors/metadata", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([]);
    }
    public function testMetadataGet() {
        $user = factory('App\users')->create();

        $sensorMetadata = factory('App\sensorMetadata', 'test')->create(["sensorOwner" => $user["userId"]]);

        $this->get("sensors/metadata", []);
        $this->seeJsonEquals([$sensorMetadata]);
    }
    public function testMetadataCreate()
    {
        $user = factory('App\users')->create();

        $this->put("sensors/metadata/new", [
            "sensorName" => "id83ns",
            "sensorPublic" => 1,
            "sensorOwner" => $user["userId"],
            "displayName" => "Test Sensor 1"
        ]);
        $this->seeInDatabase('sensorMetadata', ['sensorName' => 'id83ns']);
    }
    public function testMetadataCreateFail()
    {
        $user = factory('App\users')->create();

        $this->put("sensors/metadata/new", [
            "sensorPublic" => 1,
            "sensorOwner" => $user["userId"],
            "displayName" => "Test Sensor 1"
        ]);
        
        $this->seeStatusCode(422);
    }
}
