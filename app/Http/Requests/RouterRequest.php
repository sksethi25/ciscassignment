<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouterRequest extends FormRequest
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
            'sapid'             => 'required|min:3|max:18',
            'hostname'          =>'required|min:3|max:14',
            'macaddress'        => 'required|min:3|max:14',
            'loopback'          => 'required|ipv4',
        ];
    }

    public function messages()
    {
        return [
            'sapid.required' => 'Email is required!',
            'hostname.required' => 'Name is required!',
            'macaddress.required' => 'Password is required!'
        ];
    }


    /**
 * Configure the validator instance.
 *
 * @param  \Illuminate\Validation\Validator  $validator
 * @return void
 */
public function withValidator($validator)
{
    //die("in with validator");
    // $validator->after(function ($validator) {
    //     if ($this->somethingElseIsInvalid()) {
    //         $validator->errors()->add('field', 'Something is wrong with this field!');
    //     }

    // });

     $error_messages = $validator->getMessageBag()->toArray();
        foreach ($error_messages as $key => $value) {
                $error_messages[$key] = $value[0];
        }

        //\Log::Error("inf format erors"+$error_messages);
         $content = [
            'status' => false,
            'data'   => new \stdClass,
            'error'  => $error_messages,
        ];

         return response()->json($content, 400);
}
}
