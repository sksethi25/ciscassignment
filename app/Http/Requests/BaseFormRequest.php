<?php
/**
 * Base Request model containing Request Model Validation related Method.
 */

namespace App\Http\Requests;

use \Auth;
use \Carbon\Carbon;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Waavi\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BaseFormRequest
 */
class BaseFormRequest extends FormRequest
{
    use SanitizesInput;


    /**
     * For more sanitizer rule check https://github.com/Waavi/Sanitizer
     *
     * @return void
     */
    public function validateResolved()
    {
        \Log::Error("in validat resolved");
        $this->sanitize();

        $input_params_after_sanitize = $this->input();

        foreach ($input_params_after_sanitize as $key => $value) {
            if (($value) === null) {
                unset($input_params_after_sanitize[$key]);
            }
        }
       \Log::Error("after validat resolved");
        $this->replace($input_params_after_sanitize);
        parent::validateResolved();

    }//end validateResolved()


    /**
     * Format the errors from the given Validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator Validator.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function formatErrors(Validator $validator)
    { 
        \Log::Error("inf format erors");

        $error_messages = $validator->getMessageBag()->toArray();
        foreach ($error_messages as $key => $value) {
                $error_messages[$key] = $value[0];
        }
        \Log::Error("inf format erors"+$error_messages);
        // return ApiResponse::validationFailed($error_messages);

    }//end formatErrors()


    /**
     *  Custom Filters to be applied to the input.
     *
     * @return string|array|mixed
     */
    public function customFilters()
    {
        return [
            // Default is use to set default value. default:empty set '' otherwise set given value Eg. defalut:INR set 'INR'.
            'default'       => function ($value, $options=[]) {
                if (($value) === null && empty($options) === false) {
                    switch ($options[0]) {
                        case 'empty':
                        return '';

                        default:
                            // If default value is integer then tycast to int.
                            if (is_numeric($options[0]) === true) {
                                return (int) $options[0];
                            }
                        return $options[0];
                    }
                }

                return $value;
            },
            // If incoming value is not under default set then we are setting first value of default
            // set i.e is option[0].
            'default_in'    => function ($value, $options=[]) {
                if (in_array($value, $options) === false) {
                    return $options[0];
                }

                return $value;
            },
            // Use to decode base64 value in password field.
            'base64_decode' => function ($value, $options=[]) {
                if (empty($value) === false) {
                    return base64_decode($value, false);
                } else {
                    return $value;
                }
            },
            // Use to typecast in integer.
            'integer'       => function ($value, $options=[]) {
                return (is_numeric($value) === true) ? (int) $value : $value;
            },
            'date'          => function ($value, $option=[]) {
                if ((is_string($value) === false && is_numeric($value) === false) || strtotime($value) === false) {
                    return $value;
                }

                // Need to Convert Request Date in Default Format(Y-m-d).
                return Carbon::parse($value)->toDateString();
            },
              // Use to return file type in $request->input().
            'file'          => function ($value, $options=[]) {
                return $this->file($options[0], '');
            },
        ];

    }//end customFilters()


    /**
     * Get all headers
     *
     * @return array Header.
     */
    public function getAllHeaders()
    {
        return $this->headers->all();

    }//end getAllHeaders()


    /**
     * Get login User
     *
     * @return integer
     */
    public function getLoginUserId()
    {
        $user = Auth::user();

        if (empty($user) === true) {
            return 0;
        }

        return $user->id;

    }//end getLoginUserId()


    /**
     * Check User has Verified email and contact
     *
     * @return integer
     */
    public function checkUserHasVerifiedContactAndEmail()
    {
        $user = Auth::user();

        if (empty($user) === false && $user->email_verify === 1 && $user->mobile_verify === 1) {
            return 1;
        }

        return 0;

    }//end checkUserHasVerifiedContactAndEmail()


    /**
     * Get logged in User
     *
     * @return object
     */
    public function getLoggedInUser()
    {
        return Auth::user();

    }//end getLoggedInUser()


    /**
     * Get validated admin id
     *
     * @return object
     */
    public function getValidatedAdminIdOrFail()
    {
        $admin_hash_id = $this->headers->get('admin', '');

        if (empty($admin_hash_id) === true) {
            return 0;
        }

        $admin_id = Helper::decodeAdminId($admin_hash_id);

        if (empty($admin_id) === true) {
            $validator = \Validator::make(['admin' => $admin_id], ['admin' => 'required|numeric'], ['admin.required' => 'Invalid Admin id in Header.']);
             $this->failedValidation($validator);
        }

        return $admin_id;

    }//end getValidatedAdminIdOrFail()


     /**
      * Get Property Id
      *
      * @param string $property_hash_id Property Hash Id.
      *
      * @return integer
      */
    public function decodePropertyIdOrFail(string $property_hash_id=null)
    {
        $property_id = Helper::decodePropertyHashId($property_hash_id);
        if (empty($property_id) === true) {
            $validator = \Validator::make(['property_id' => $property_id], ['property_hash_id' => 'required|numeric'], ['property_hash_id.required' => 'The property hash id field is invalid.']);
             $this->failedValidation($validator);
        }

        return $property_id;

    }//end decodePropertyIdOrFail()


    /**
     * Get collection Id
     *
     * @param string $collection_hash_id Collection Hash Id.
     *
     * @return integer
     */
    public function decodeCollectionIdOrFail(string $collection_hash_id)
    {
        $collection_id = Helper::decodeCollectionHashId($collection_hash_id);
        if (empty($collection_id) === true) {
            $validator = \Validator::make(['collection_id' => $collection_id], ['collection_hash_id' => 'required|numeric'], ['collection_hash_id.required' => 'The collection hash id field is invalid.']);
            $this->failedValidation($validator);
        }

        return $collection_id;

    }//end decodeCollectionIdOrFail()


     /**
      * Get booking Id
      *
      * @param string $booking_hash_id Booking Hash Id.
      *
      * @return integer
      */
    public function decodeBookingRequestIdOrFail(string $booking_hash_id=null)
    {
        $booking_id = Helper::decodeBookingRequestId($booking_hash_id);
        if (empty($booking_id) === true) {
            $validator = \Validator::make(['booking_id' => $booking_id], ['booking_hash_id' => 'required|numeric'], ['booking_hash_id.required' => 'The booking id is invalid.']);
            $this->failedValidation($validator);
        }

        return $booking_id;

    }//end decodeBookingRequestIdOrFail()


    /**
     * Get User Id
     *
     * @param string $user_hash_id User Hash Id.
     *
     * @return integer
     */
    public function decodeUserIdOrFail(string $user_hash_id=null)
    {
        $user_id = Helper::decodeUserId($user_hash_id);
        if (empty($user_id) === true) {
            $validator = \Validator::make(['user_id' => $user_id], ['user_hash_id' => 'required|numeric'], ['user_hash_id.required' => 'The user hash id field is invalid.']);
             $this->failedValidation($validator);
        }

        return $user_id;

    }//end decodeUserIdOrFail()


    /**
     * Get Property Ids
     *
     * @param string $property_hash_ids Property Hash Id.
     *
     * @return integer
     */
    public function decodeAllPropertyIdOrFail(string $property_hash_ids=null)
    {
        $property_ids = [];
        if (empty($property_hash_ids) === false) {
            $property_hash_ids_array = explode(',', $property_hash_ids);

            foreach ($property_hash_ids_array as $hash_id) {
                $property_ids[] = $this->decodePropertyIdOrFail(trim($hash_id));
            }
        }

        return $property_ids;

    }//end decodeAllPropertyIdOrFail()


    /**
     * Get Task Id
     *
     * @param string $task_hash_id Task Hash Id.
     *
     * @return integer
     */
    public function decodeTaskIdOrFail(string $task_hash_id=null)
    {
        $task_id = Helper::decodeTaskHashId($task_hash_id);
        if (empty($task_id) === true) {
            $validator = \Validator::make(['task_id' => $task_id], ['task_hash_id' => 'required|numeric'], ['task_hash_id.required' => 'The Task hash id field is invalid.']);
             $this->failedValidation($validator);
        }

        return $task_id;

    }//end decodeTaskIdOrFail()


    /**
     * Custom Validation
     *
     * @param array $data_to_validate   Data Array to Validate.
     * @param array $validation_rule    Validation Rules.
     * @param array $validation_message Validation Message.
     *
     * @return void
     */
    public function customValidation(array $data_to_validate, array $validation_rule, array $validation_message)
    {
        $validator = \Validator::make($data_to_validate, $validation_rule, $validation_message);

        if ($validator->fails() !== false) {
            $this->failedValidation($validator);
        }

    }//end customValidation()


    /**
     * Get User Ids
     *
     * @param string $user_hash_ids User Hash Id.
     *
     * @return integer
     */
    public function decodeAllUserIdOrFail(string $user_hash_ids=null)
    {
        $property_ids = [];
        if (empty($user_hash_ids) === false) {
            $user_hash_ids_array = explode(',', $user_hash_ids);

            foreach ($user_hash_ids_array as $hash_id) {
                $user_ids[] = $this->decodeUserIdOrFail(trim($hash_id));
            }
        }

        return $user_ids;

    }//end decodeAllUserIdOrFail()


    /**
     * Get Properly Expense Id.
     *
     * @param string|null $properly_expense_hash_id Properly Expense Hash Id.
     *
     * @return integer|string
     * @throws \Illuminate\Validation\ValidationException Throws an exception for failed validation.
     */
    public function decodeProperlyExpenseIdOrFail(string $properly_expense_hash_id=null)
    {
        $properly_expense_id = Helper::decodeProperlyExpenseHashId($properly_expense_hash_id);

        if (empty($properly_expense_id) === true) {
            $validator = \Validator::make(['property_expense_id' => $properly_expense_id], ['properly_expense_hash_id' => 'required|numeric'], ['properly_expense_hash_id.required' => 'The properly expense hash id is invalid.']);

            $this->failedValidation($validator);
        }

        return $properly_expense_id;

    }//end decodeProperlyExpenseIdOrFail()


}//end class
