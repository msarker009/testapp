<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectRequestValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
        ];
        if($this->method() == "POST"){
            $rules += [
                'title' => 'min:3|required',
                'status' => 'in:started,pending,completed,delivered,suspended',
                'project_files.*' => 'mimes:jpg,bmp,png,docx,pdf'
            ];
        } else {
            $rules += [
                'end_date' => 'required',
            ];
        }
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $jsonResponse = response()->json(['errors' => $validator->errors()], 422);
        throw new HttpResponseException($jsonResponse);
    }
}
