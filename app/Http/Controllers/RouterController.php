<?php

namespace App\Http\Controllers;

use App\Router;
use Illuminate\Http\Request;
use App\Http\Requests\{CreateRouterRequest, RouterRequest};
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\DataTables\UsersDataTable;
use App\RouterService;

class RouterController extends Controller
{
    private static $store_rules=[
            'sapid'             => 'required|min:3|max:18',
            'hostname'          =>'required|min:3|max:14',
            'macaddress'        => 'required|min:3|max:17',
            'loopback'          => 'required|ipv4',
    ];

    private static $update_rules=[
            'sapid'             => 'required_without_all:hostname,macaddress,loopback|min:3|max:18',
            'hostname'          =>'required_without_all:sapid,macaddress,loopback|min:3|max:14',
            'macaddress'        => 'required_without_all:hostname,sapid,loopback|min:3|max:14',
            'loopback'          => 'required_without_all:hostname,macaddress,sapid|ipv4',
    ];

    public function __construct(){
        $this->routerService= new RouterService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('welcome');
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showshape()
    {
      return view('shape');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Loading local form
    }

    private function checkValidation($request, $rules){
        $validator = Validator::make($request->all(), $rules);

        $error_messages=[];

       if ($validator->fails()) {
             $error_messages = $validator->getMessageBag()->toArray();
                foreach ($error_messages as $key => $value) {
                $error_messages[$key] = $value[0];
            }    
        }

         return $error_messages;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors= $this->checkValidation($request, self::$store_rules);
        if(count($errors)>0){
             return ApiResponse::validationFailed($errors);
        }

        $result= $this->routerService->createRouter(
                    $request->input('sapid'), 
                    $request->input('hostname'), 
                    $request->input('loopback'), 
                    $request->input('macaddress')
                );
        if($result == false){
            return ApiResponse::errorMessage("There is some error please try later");
           
        }
        return ApiResponse::successMessage("Data saved succesfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $router = $this->routerService->isRouterExists($id);
        return ApiResponse::success([$router->toArray()]);
    }


    public function showAll(){
        $router = $this->routerService->getAlRouter();
        return datatables()->of(Router::all())->toJson();
        // return ApiResponse::success($router->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function edit(Router $router)
    {
        //will be using custom
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Router $router)
    {
        $errors= $this->checkValidation($request, self::$update_rules);
        if(count($errors)>0){
             return ApiResponse::validationFailed($errors);
        }
        $result = $this->routerService->updateRouter($router, 
            $request->input('sapid'), 
            $request->input('hostname'),
            $request->input('loopback'),
            $request->input('macaddress'));

        if($result == false){
            return ApiResponse::errorMessage("There is some error please try later");
           
        }
        return ApiResponse::successMessage("Data Updated succesfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $router = $this->routerService->isRouterExists($id);
        if(is_null($router)){
           return ApiResponse::notFoundError("validation_failed", "Either it does not exist or already deleted");
        }

       $result= $this->routerService->deleteRouter($router);
        if($result == false){
            return ApiResponse::errorMessage("There is some error please try later");
           
        }
        return ApiResponse::successMessage("Data deleted succesfully");
    }
}
