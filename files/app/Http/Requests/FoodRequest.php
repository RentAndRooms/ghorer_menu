<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
        return [
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|unique:food,name|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'preparation_time' => 'required|integer|min:1',
            // 'image' => $this->isMethod('POST') ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'half_price'=> 'sometimes',
            'allergens' => 'nullable|array',
            'allergens.*' => 'string',
            'is_available' => 'boolean',
            'sort_order' => 'integer|min:0',
            'extra_options' => 'array',
            'extra_options.*.id' => 'nullable|exists:extra_options,id',
            'extra_options.*.name' => 'required|string|max:255',
            'extra_options.*.price' => 'required|numeric|min:0',
            'extra_options.*.is_available' => 'boolean',
            'extra_options.*.sort_order' => 'integer|min:0'
        ];
    }
}
