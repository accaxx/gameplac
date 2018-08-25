<?php
namespace App\Http\Requests\Othello;

use Illuminate\Foundation\Http\FormRequest;

class OthelloRequest extends FormRequest
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

    public function rules()
    {
        return [
            'key' => 'required|unique:plays',
        ];
    }
}
