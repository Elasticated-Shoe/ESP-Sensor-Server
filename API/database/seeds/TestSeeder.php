<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x = 1; $x <= 10; $x++) {
            DB::table('users')->insert([
                'userEmail' => "test " . strval($x),
                'userPass' => "pasword",
                'isAdmin' => 0,
                'isLocked' => 0,
            ]);
        }
        for ($userId = 1; $userId <= 10; $userId++) {
            for ($userSensorCount = 1; $userSensorCount <= 10; $userSensorCount++) {
                DB::table('sensorMetadata')->insert([
                    "sensorName" => "sensor " . strval($userId) . " " . strval($userSensorCount),
                    "sensorOwner" => $userId,
                    "sensorPublic" => 1,
                    "displayName" => "sensor " . strval($userId) . " " . strval($userSensorCount)
                ]);
            }
        }
        $unixTimestamp = 1597152488;
        for ($userId = 1; $userId <= 10; $userId++) {
            for ($userSensorCount = 1; $userSensorCount <= 10; $userSensorCount++) {
                $sensorId = $userId * 10 - 10 + $userSensorCount;
                for ($sensorReading = 1; $sensorReading <= 10000; $sensorReading++) {
                    DB::table('sensorData')->insert([
                        "sensorId" => $sensorId,
                        "sensorDatetime" => new DateTime('@' . strval($unixTimestamp - $sensorId + $sensorReading)),
                        "sensorValue" => $sensorReading >= 1000 ? $sensorReading / 1000 : $sensorReading
                    ]);
                }
            }
        }
    }
}
