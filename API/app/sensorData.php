<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sensorData extends Model
{
    protected $table = 'sensorData';
    protected $primaryKey = "dataId";
    public $timestamps = false;

    protected $fillable = ["sensorId", "sensorDatetime", "sensorValue"];
}
