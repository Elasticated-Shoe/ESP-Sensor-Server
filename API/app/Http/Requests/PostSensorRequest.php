<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PostSensorRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'sensorId' => 'required|integer',
			'displayName' => 'max:255',
            'sensorType' => 'max:255'
        ];
	}
}