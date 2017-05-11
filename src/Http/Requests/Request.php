<?php

namespace Social\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Request
 * @package Social\Http\Requests
 */
abstract class Request extends FormRequest
{
    /**
     * @return array
     */
    abstract function rules(): array;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}