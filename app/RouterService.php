<?php 

namespace App;

use App\Router;

class RouterService{
	
	public function createRouter(string $sapid, string $hostname, string $loopback, string $macaddress):bool{
		$router = new Router;
		$router->sapid=$sapid;
		$router->hostname=$hostname;
		$router->loopback=$loopback;
		$router->macaddress=$macaddress;
		return $router->save();
	}

	public function getAlRouter(){
		return  Router::all();
	}

	public function isRouterExists(int $id){
		return Router::find($id);
	}

	public function isRoutersExistsWithIp(string $ip){
		return Router::where('loopback', $ip)->toSql();
	}

	public function isRouterExistsBySapid(string $sapid){
		return Router::where('sapid', $sapid)->get();
	}

	public function isRouterExistsInRange(string $loopbackstart, string $loopbackend) {
		return Router::whereRaw("INET_ATON(loopback) BETWEEN INET_ATON(?) AND INET_ATON(?)", 
			array($loopbackstart, $loopbackend))->get();
	}

	public function updateRouter($router, $sapid, $hostname, $loopback, $macaddress):bool{
		if(!is_null($sapid)){
			$router->sapid=$sapid;
		}

		if(!is_null($hostname)){
			$router->hostname=$hostname;
		}
		if(!is_null($loopback)){
			$router->loopback=$loopback;
		}
		if(!is_null($macaddress)){
			$router->macaddress=$macaddress;
		}

		return $router->save();
	}

	public function deleteRouter($router): bool{
		return $router->delete();
	}
}