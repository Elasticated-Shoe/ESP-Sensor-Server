<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PostDataBulkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ownedSensorIdArray = array_column($this->all(), 'sensorId');

        return $this->AuthorizeIfOwned($ownedSensorIdArray);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*' => 'required|array',
            '*.sensorId' => 'required|integer',
            '*.sensorDatetime' => 'required|date_format:Y-m-d-H:i:s',
            '*.sensorValue' => 'required|numeric|between:0.00,999.99'
        ];
    }
}
