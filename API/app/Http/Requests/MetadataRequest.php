<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MetadataRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'sensorId' => 'required|integer',
            'sensorType' => 'max:255'
        ];
	}
}