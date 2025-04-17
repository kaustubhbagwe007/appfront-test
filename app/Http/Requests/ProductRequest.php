<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ProductRequest extends FormRequest
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
        $actionMethod = Route::getCurrentRoute()->getActionMethod();

        $imageRequired = 'required';

        if ($actionMethod === 'update') {
            $imageRequired = 'sometimes';
        }

        return [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'image' => $imageRequired . '|image|mimes:jpg,png,jpeg',
        ];
    }
}
