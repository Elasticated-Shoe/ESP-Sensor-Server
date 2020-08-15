<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class sensorGetParameterRequest extends FormRequest
{
	public function all($keys = NULL) {
		// override all to contain url parameters
		return array_merge(parent::all(), $this->route()[2]);
	}
	public function authorize() {
		return true;
	}

	public function rules() {
		return [
            'id' => 'required|integer'
		];
	}
}