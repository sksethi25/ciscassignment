<?php
/**
 * PostLeadRequest model containing Request Model Validation related Method.
 */

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Factory;

/**
 * Class PostLeadRequest
 */
class CreateRouterRequest extends BaseFormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Log::Error("in rules");
        return [
            'sapid'             => 'required|min:3|max:18',
            'hostname'          =>'required|min:3|max:14',
            'macaddress'        => 'required|min:3|max:14',
            'loopback'          => 'required|ipv4',
        ];

    }//end rules()


    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
         \Log::Error("in filte");
        // Data Sanitization Parameters and Its Default Value.
        return [
            'sapid'          => 'trim',
            'hostname'       => 'trim',
            'macaddress'     => 'trim',
            'loopback'       => 'trim'
        ];

    }//end filters()


}//end class
