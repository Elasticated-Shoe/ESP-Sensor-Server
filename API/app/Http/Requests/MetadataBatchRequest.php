<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MetadataBatchRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            '*' => 'required|array',
            '*.sensorId' => 'required|integer',
            '*.sensorType' => 'max:255',
        ];
	}
}