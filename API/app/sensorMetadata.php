<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sensorMetadata extends Model
{
    protected $table = 'sensorMetadata';
    protected $primaryKey = "sensorId";
    public $timestamps = false;

    protected $fillable = ["sensorName", "sensorOwner", "sensorPublic", "displayName", "sensorType"];
}
