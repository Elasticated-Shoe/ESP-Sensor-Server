<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sensorData extends Model
{
    protected $table = 'sensorData';
    protected $primaryKey = null;
    public $timestamps = false;

    protected $fillable = ["sensorId", "sensorDatetime", "sensorValue"];
}
