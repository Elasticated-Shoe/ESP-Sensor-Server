<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PutSensorRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'sensorName' => 'required|max:255',
            'sensorOwner' => 'required|integer',
            'sensorPublic' => 'required|boolean',
            'displayName' => 'required|max:255',
        ];
	}
}