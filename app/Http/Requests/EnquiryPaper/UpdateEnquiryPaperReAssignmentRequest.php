<?php

namespace App\Http\Requests\EnquiryPaper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnquiryPaperReAssignmentRequest extends FormRequest
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
            'io_user_id' => 'required|max:190',
            'pegawai_penyiasat_io' => 'required|max:190',
            // 'aio_user_id' => 'required|max:190',
            'pegawai_penyiasat_aio' => 'required|max:190',
            // 'description' => 'required',
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
            'io_user_id.required' => 'Ruangan Pegawai Penyiasat IO diperlukan.',
            'io_user_id.max' => 'Jumlah Pegawai Penyiasat IO mesti tidak melebihi :max aksara.',
            'pegawai_penyiasat_io.required'  => 'Ruangan Pegawai Penyiasat IO diperlukan.',
            'pegawai_penyiasat_io.max' => 'Jumlah Pegawai Penyiasat IO mesti tidak melebihi :max aksara.',
            'aio_user_id.required' => 'Ruangan Pegawai Penyiasat AIO diperlukan.',
            'aio_user_id.max' => 'Jumlah Pegawai Penyiasat AIO mesti tidak melebihi :max aksara.',
            'pegawai_penyiasat_aio.required'  => 'Ruangan Pegawai Penyiasat AIO diperlukan.',
            'pegawai_penyiasat_aio.max' => 'Jumlah Pegawai Penyiasat AIO mesti tidak melebihi :max aksara.',
            'description.required'  => 'Ruangan Saranan diperlukan.',
        ];
    }
}
