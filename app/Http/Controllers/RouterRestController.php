<?php

namespace App\Http\Controllers;

use App\Router;
use Illuminate\Http\Request;
use App\Http\Requests\{CreateRouterRequest, RouterRequest};
use Illuminate\Support\Facades\Validator;
use App\ApiResponse;
use App\DataTables\UsersDataTable;
use App\RouterService;

class RouterRestController extends Controller
{
    private static $store_rules=[
            'sapid'             => 'required|min:3|max:18',
            'hostname'          =>'required|min:3|max:14|unique:routers,hostname',
            'macaddress'        => 'required|min:3|max:17',
            'loopback'          => 'required|ipv4|unique:routers,loopback',
    ];

    private static $update_rules=[
            'sapid'             => 'required_without_all:hostname,macaddress,loopback|min:3|max:18',
            'hostname'          =>'required_without_all:sapid,macaddress,loopback|min:3|max:14',
            'macaddress'        => 'required_without_all:hostname,sapid,loopback|min:3|max:14',
            'loopback'          => 'required|ipv4',
    ];

    public function __construct(){
        $this->routerService= new RouterService();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $errors= $this->checkValidation($request, self::$update_rules);
        if(count($errors)>0){
             return ApiResponse::validationFailed($errors);
        }
        $ip= $request->input('loopback');

        $routers= $this->routerService->isRoutersExistsWithIp($ip);
        foreach ($routers as $key => $router) {
            $result = $this->routerService->updateRouter($router, 
                $request->input('sapid'), 
                $request->input('hostname'),
                $request->input('loopback'),
                $request->input('macaddress'));
        }
        return ApiResponse::successMessage("Data Updated succesfully");
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function showBySapid(Request $request, string $sapid)
    {
        $router = $this->routerService->isRouterExistsBySapid($sapid);
        return ApiResponse::success([$router->toArray()]);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function showByIpRange(Request $request, string $ipstart, string $ipend)
    {
        
        $routers= $this->routerService->isRouterExistsInRange($ipstart, $ipend);
        return ApiResponse::success([$routers->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Router  $router
     * @return \Illuminate\Http\Response
     */
    public function deleteByIp(Request $request, string $ip)
    {
        $routers= $this->routerService->isRoutersExistsWithIp($ip);
        
        foreach ($routers as $key => $router) {
            $this->routerService->deleteRouter($router);
        }

        return ApiResponse::successMessage("Deleted sucessfully if exists");
    }


}
