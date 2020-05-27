<?php
/**
 * Contain all response functions
 */

namespace App;

use Illuminate\Http\Response;
use Log;

define('OK_HTTP_STATUS_CODE', 200);
define('CREATED_HTTP_STATUS_CODE', 201);

// Error 4xx.
define('BAD_REQUEST_HTTP_STATUS_CODE', 400);
define('UNAUTHORIZED_HTTP_STATUS_CODE', 401);
define('PAYMENT_REQUIRED_HTTP_STATUS_CODE', 402);
define('FORBIDDEN_HTTP_STATUS_CODE', 403);
define('NOT_FOUND_HTTP_STATUS_CODE', 404);

// Error 5xx.
define('INTERNAL_SERVER_ERROR_HTTP_STATUS_CODE', 500);
define('BAD_GATEWAY_HTTP_STATUS_CODE', 502);
define('SERVICE_UNAVAILABLE_HTTP_STATUS_CODE', 503);

// Error code.
define('ERROR_CODE_EMAIL_ALREADY_REGISTERED', 'We already have an account associated with this email.');
define('ERROR_CODE_USER_NOT_CREATED', 'There was some error while signing you up. Please try again after some time.');
define('ERROR_CODE_INVALID_EMAIL', 'Invalid email.');
define('ERROR_CODE_INVALID_MOBILE', 'Invalid contact number.');
define('ERROR_CODE_OTP_LIMIT', 'You have reached maximum otp limit. Please reset through link send in email!');
define('ERROR_CODE_MOBILE_NOT_VERIFIED', 'Mobile number not verified. Please login using email!');
define('ERROR_CODE_INVALID_MOBILE_LENGTH', 'Invalid contact number length.');
define('ERROR_CODE_INVALID_PASSWORD_LENGTH', 'New password should be of minimum 6 characters.');
define('ERROR_CODE_INVALID_OTP', 'Invalid verification code.');
define('ERROR_CODE_EMAIL_NOT_REGISTERED', 'Email id not registered.');
define('ERROR_CODE_INVALID_RESET_PASSWORD_TOKEN', 'Invalid reset token!');
define('ERROR_CODE_FILE_UPLOAD_FAILED', 'There was some error while file upload. Please try again after some time.');
define('ERROR_CODE_EMAIL_CONTACT_ALREADY_REGISTERED', 'We already have an account associated with this email or contact number.');

// Error Codes (EC).
define('EC_VALIDATION_FAILED', 'validation_failed');
define('EC_CONTACT_NOT_VERIFIED', 'contact_not_verified');
define('EC_PAYMENT_METHOD_NOT_AVAILABLE', 'payment_method_not_available');
define('EC_RATING_ALREADY_SUBMITTED', 'rating_already_submitted');
define('EC_REVIEW_ALREADY_SUBMITTED', 'review_already_submitted');
define('EC_SERVER_ERROR', 'server_error');
define('EC_DUPLICATE_USER', 'duplicate_user');
define('EC_INVALID_ACCESS_TOKEN', 'invalid_access_token');
define('EC_BAD_GATEWAY', 'bad_gateway');
define('EC_SERVICE_UNAVIALABLE', 'service_unavailable');
define('EC_CONTACT_UNCHANGED', 'contact_unchanged');
define('EC_DUPLICATE_CONTACT', 'duplicate_contact');
define('EC_INVALID_OTP_METHOD', 'invalid_otp_method');
define('EC_INVALID_OTP', 'invalid_otp');
define('EC_NOT_FOUND', 'not_found');
define('EC_PROPERTY_NOT_IN_WISHLIST', 'property_not_in_wishlist');
define('EC_AUTH_TOKEN_MISSING', 'auth_token_missing');
define('EC_FILE_NOT_UPLOADED', 'file_not_uploaded');
define('EC_UNAUTHORIZED', 'unauthorized');
define('EC_FORBIDDEN', 'not_allowed');
define('EC_PAYMENT_GATEWAY_NOT_AVAILABLE', 'payment_gateway_not_available');
define('EC_PAYMENT_GATEWAY_NOT_SUPPORTED', 'gateway_not_supported');
/**

  Contain api response functions
 */
class ApiResponse
{



    /**
     * Check if the access token has been revoked.
     *
     * @param array   $data   Contain array of data.
     * @param integer $status Default code 200.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(array $data, int $status=OK_HTTP_STATUS_CODE)
    {
        $content = [
            'status' => true,
            'data'   => $data,
            'error'  => [],
        ];
         return response()->json($content, $status);

    }//end success()


    /**
     * Send Json response to user with message.
     *
     * @param string $message Success message to send along with success response.
     *
     * @return \Illuminate\Http\JsonResponse Return json response.
     */
    public static function successMessage(string $message)
    {
        return self::success(
            ['message' => $message]
        );

    }//end successMessage()


    /**
     * Send Json response to user with 201 code.
     *
     * @param array $data Contain array of data.
     *
     * @return \Illuminate\Http\JsonResponse Return json response with 201 http code.
     */
    public static function create(array $data)
    {
        return self::success($data, CREATED_HTTP_STATUS_CODE);

    }//end create()


    /**
     * Send Json response to user with message with 201 code.
     *
     * @param string $message Message to send with create.
     *
     * @return \Illuminate\Http\JsonResponse Return json respose with message and 201 http code.
     */
    public static function createMessage(string $message)
    {
        return self::create(
            ['message' => $message]
        );

    }//end createMessage()


    /**
     * Send Json response to user with error and 400 code.
     *
     * @param array   $error  Array of errors.
     * @param integer $status Http status code default 400.
     *
     * @return \Illuminate\Http\JsonResponse Return json respose with error array and 400 http code.
     */
    public static function error(array $error, int $status=BAD_REQUEST_HTTP_STATUS_CODE)
    {
        $content = [
            'status' => false,
            'data'   => new \stdClass,
            'error'  => $error,
        ];

         return response()->json($content, $status);

    }//end error()


    /**
     * Send Json response to user with error and 400 code and message.
     *
     * @param string $message Message to send with error.
     *
     * @return \Illuminate\Http\JsonResponse Return json respose with error array and 400 http code and a message.
     */
    public static function errorMessage(string $message)
    {
        return self::error(
            [
                [
                    'code'    => '',
                    'key'     => '',
                    'message' => $message,
                ],
            ]
        );

    }//end errorMessage()


    /**
     * Check if the access token has been revoked.
     *
     * @param array $params Params whose validation failed.
     *
     * @return \Illuminate\Http\JsonResponse Return json respose with array contained validation failed params.
     */
    public static function validationFailed(array $params)
    {
        $errors = [];
        foreach ($params as $key => $value) {
            $errors[] = [
                'code'    => EC_VALIDATION_FAILED,
                'key'     => $key,
                'message' => $value,
            ];
        }

        return self::error($errors);

    }//end validationFailed()


    /**
     * Function to call if there is error on server side.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data for server error.
     */
    public static function serverError(string $code, string $message)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => '',
                'message' => $message,
            ],
            ],
            INTERNAL_SERVER_ERROR_HTTP_STATUS_CODE
        );

    }//end serverError()


    /**
     * For use when authentication is possible but has failed or not yet been provided.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     * @param string $key     Key for which error happened.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data.
     */
    public static function unauthorizedError(string $code, string $message, string $key='')
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => $key,
                'message' => $message,
            ],
            ],
            UNAUTHORIZED_HTTP_STATUS_CODE
        );

    }//end unauthorizedError()


    /**
     * Sends json response when code breaks.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data and message.
     */
    public static function badRequestError(string $code, string $message)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => '',
                'message' => $message,
            ],
            ],
            BAD_REQUEST_HTTP_STATUS_CODE
        );

    }//end badRequestError()


    /**
     * Function to call if there is request is ok but some condition or constraint failed.
     *
     * @param string      $code    Error code.
     * @param string      $message Message for server error.
     * @param string|null $key     Key name.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data.
     */
    public static function forbiddenError(string $code, string $message, string $key=null)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => $key,
                'message' => $message,
            ],
            ],
            FORBIDDEN_HTTP_STATUS_CODE
        );

    }//end forbiddenError()


    /**
     * Function to call if there is request is ok but resource or record not found.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data.
     */
    public static function notFoundError(string $code, string $message)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => '',
                'message' => $message,
            ],
            ],
            NOT_FOUND_HTTP_STATUS_CODE
        );

    }//end notFoundError()


    /**
     * Function to call if the request depends on an external API but received an invalid response.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data.
     */
    public static function badGatewayError(string $code, string $message)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => '',
                'message' => $message,
            ],
            ],
            BAD_GATEWAY_HTTP_STATUS_CODE
        );

    }//end badGatewayError()


    /**
     * Function to call if the request depends on an external API but received no response.
     *
     * @param string $code    Error code.
     * @param string $message Message for server error.
     *
     * @return \Illuminate\Http\JsonResponse response containing error code and error data.
     */
    public static function serviceUnavailableError(string $code, string $message)
    {
        return self::error(
            [[
                'code'    => $code,
                'key'     => '',
                'message' => $message,
            ],
            ],
            SERVICE_UNAVAILABLE_HTTP_STATUS_CODE
        );

    }//end serviceUnavailableError()


}//end class
