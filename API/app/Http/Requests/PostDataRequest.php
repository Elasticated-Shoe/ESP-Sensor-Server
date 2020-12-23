<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PostDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->AuthorizeIfOwned(array($this->all()["sensorId"]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sensorId' => 'required|integer',
            'sensorDatetime' => 'required|date_format:Y-m-d-H:i:s',
            'sensorValue' => 'required|numeric|between:0.00,999.99'
        ];
    }
}
