<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class TestRequest extends FormRequest
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