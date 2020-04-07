<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;

abstract class Request extends FormRequest
{
    /**
     * 表单请求类 错误响应信息格式 自定义
     *
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(response()->json([
            'code'    => 1,
            'message' => '参数错误',
            'data'    => $validator->errors()->all(),
        ], 200)));
    }
}
