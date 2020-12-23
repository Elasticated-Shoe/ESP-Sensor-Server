<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class GetDataRequest extends FormRequest
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
			'id.*' => 'required|integer',
			'start' => 'required|date_format:Y-m-d-H:i:s',
			'end' => 'required|date_format:Y-m-d-H:i:s'
		];
	}
}