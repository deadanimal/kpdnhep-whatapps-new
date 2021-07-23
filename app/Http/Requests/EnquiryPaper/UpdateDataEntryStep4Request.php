<?php

namespace App\Http\Requests\EnquiryPaper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDataEntryStep4Request extends FormRequest
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
        return [
            'detail_note' => 'required|max:500',
            'detail_result' => 'required|max:500',
            'detail_answer' => 'required|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'detail_note.required' => 'Ruangan Catatan diperlukan.',
            'detail_note.max' => 'Jumlah Catatan mesti tidak melebihi :max aksara.',
            'detail_result.required' => 'Ruangan Hasil Siasatan diperlukan.',
            'detail_result.max' => 'Jumlah Hasil Siasatan mesti tidak melebihi :max aksara.',
            'detail_answer.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
            'detail_answer.max' => 'Jumlah Jawapan Kepada Pengadu mesti tidak melebihi :max aksara.',
        ];
    }
}
