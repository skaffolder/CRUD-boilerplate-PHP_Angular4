<?php
require_once 'lib/Slim/Middleware.php';

/*
 * Authentication Middleware
 */
class Security extends \Slim\Middleware
{
    public function call()
    {
    	$app = $this->app;

		$response = $app->response();
		$response->header('Access-Control-Allow-Origin', '*');
    	$response->header('Access-Control-Allow-Headers', 'Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, pass, user');
    	$response->header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS,PATH');
		$response->header('Access-Control-Allow-Credentials', 'true');
		$response->header('Access-Control-Allow-Origin-param', '*');

		if($app->request()->getMethod() == 'OPTIONS')
			return;
		
    	$app->response->headers->set('Content-Type', 'application/json');

		/*

    	//Get headers variable of HTTP request
    	$params= array(
    			'user' => $app->environment['HTTP_USER'],
    			'pass' => $app->environment['HTTP_PASS'],
    	);

    	//seach user in DB
    	$query = "select * FROM user WHERE user=:user AND pass=:pass LIMIT 1";
    	$userLogged = makeQuery( $query, $params, false);
    	
    	if (isset($userLogged->_id))
    	{
			//User logged
    		if (isset($userLogged->_id))
    		{
    			//set user for use in services
    			$app->response()->userLogged = $userLogged;
    		}
    		$this->next->call();
    	}
    	else
    	{
    		//user not logged
    		$response = array ('error' => '401 Unauthorized');
    		if ( $app->request()->getResourceUri() == "/Login")
    			$response['disableError'] = true;
    		
    		echo json_encode($response);
    		$app->response()->status(401);
    	}

		*/

    }
}
?>