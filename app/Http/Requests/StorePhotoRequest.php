<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotoRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_id' => "required|exists:products,id",
            'photos' => "required",
            'photos.*' => "file|mimes:png,jpg,jpeg|max:5000",
        ];
    }

    public function attributes()
    {
        return [
            'photos.*' => 'photos',
        ];
    }
}
