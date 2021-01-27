<?php
namespace App\Http\Requests;

use Upgate\LaravelJsonRpc\Server\FormRequest;

class GetByDateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'before-or-equal:yesterday'
            ]
            // 'temp' => ['required', 'numeric', 'between:-60,60', 'regex:/^\-?\d{1,2}(\.\d{1})?$/'],
        ];
    }
}
