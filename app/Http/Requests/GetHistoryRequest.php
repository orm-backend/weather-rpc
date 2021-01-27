<?php
namespace App\Http\Requests;

use Upgate\LaravelJsonRpc\Server\FormRequest;

class GetHistoryRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'lastDays' => ['required', 'integer', 'min:1']
        ];
    }
}
