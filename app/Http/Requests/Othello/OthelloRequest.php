<?php
namespace App\Http\Requests\Othello;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Othello\Othello as OthelloModel;

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
            'key' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            if (is_null(OthelloModel::where('key', $this->key)->first()->category)) {
                return;
            };
            $validator->errors()->add('message', '既に入力されているマスには保存できません');
            return;
        });
    }
}
