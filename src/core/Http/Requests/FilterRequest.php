<?php

namespace Core\Http\Requests;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
     * Preapare request for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        try {
            $startDate = Carbon::parse($this->input('dates.0'))->startOfDay();
            $endDate = Carbon::parse($this->input('dates.1'))->endOfDay();
        } catch (InvalidFormatException $e) {
            $startDate = $startDate ?? null;
            $endDate = $startDate ?? null;
        }

        $this->merge([
            'dates' => [$startDate, $endDate],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dates' => 'required|array',
            'dates.0' => 'nullable|date',
            'dates.1' => 'nullable|date',
        ];
    }
}
