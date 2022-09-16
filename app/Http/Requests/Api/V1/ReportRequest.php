<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string', 'in:customer,bug,feedback'],
            'message' => ['required', 'string'],
            'customer_id' => ['nullable', 'required_if:type,customer', 'numeric'],
        ];
    }
}
