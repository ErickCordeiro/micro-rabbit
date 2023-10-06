<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $appToken = $this->company;

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => "required|unique:companies,name,{$appToken},app_token",
            'whatsapp' => "required|unique:companies,whatsapp,{$appToken},app_token",
            'email' => "required|email|unique:companies,email,{$appToken},app_token",
            'phone' => "nullable|unique:companies,phone,{$appToken},app_token",
            'facebook' => "nullable|unique:companies,facebook,{$appToken},app_token",
            'instagram' => "nullable|unique:companies,instagram,{$appToken},app_token",
            'youtube' => "nullable|unique:companies,youtube,{$appToken},app_token",
        ];
    }
}
