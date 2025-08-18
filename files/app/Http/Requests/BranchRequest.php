<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'map_url' => 'nullable',
            'district_id' => 'required',
            'thana_id' => 'required',
            'local_id' => 'sometimes',
            'image' => 'sometimes',
            'opening_hours' => 'required|array|min:1',
            'opening_hours.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'opening_hours.*.open' => 'required|date_format:H:i',
            'opening_hours.*.close' => 'required|date_format:H:i',
            'opening_hours.*.breakfast.start' => 'nullable|date_format:H:i',
            'opening_hours.*.breakfast.end' => 'nullable|date_format:H:i',
            'opening_hours.*.lunch.start' => 'nullable|date_format:H:i',
            'opening_hours.*.lunch.end' => 'nullable|date_format:H:i',
            'opening_hours.*.dinner.start' => 'nullable|date_format:H:i',
            'opening_hours.*.dinner.end' => 'nullable|date_format:H:i',
            'delivery_radius' => 'required|integer|min:1|max:50',
            'is_active' => 'required|boolean',
        ];
    }
}
