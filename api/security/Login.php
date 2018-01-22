<?php

require_once './db/dbManage_Film_Example_dbManager.php';

require './lib/JWT/JWT.php';

use \Firebase\JWT\JWT;

$app = new \Slim\Slim();


// Login Action
$app->post('/login',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	// Quesry for user on db
	$params = array (
		'username'	    => isset($body->username)?$body->username:'',
		'password'	    => isset($body->password)?$body->password:'',
	);
	$user = makeQuery( "SELECT * FROM user WHERE username=:username AND password=:password LIMIT 1" , $params, false);
	
	if($user == null) {
		$app->response()->status(401);
		echo '{ "message": "Not Authoized" }';
		return;
	}

	$user->password = null;

	// Get roles
	$params = array (
		'user_id'	    => $user->_id
	);
	$roles = makeQuery( "SELECT * FROM roles WHERE _user=:user_id" , $params, false);
	$user->roles = [];
	foreach ($roles as $role) {
		array_push($user->roles, $role->role);
	}

	// Generate JWT token
	global $jwt_secret_key;
	$token = JWT::encode($user, $jwt_secret_key);
	$user->token = $token;

	// Print result
	echo json_encode($user);

});

// Verify Token action
$app->post('/verifyToken',	function () use ($app) {
	$body = json_decode($app->request()->getBody());
	
	// Token non provided
	if (!isset($body->token)) {
		$app->response()->status(403);
		echo '{ "success": false, "message": "No token provided"}';
		return;
	}
	
	// Decode token
	$token = $body->token;
	global $jwt_secret_key;

	try {
		$decoded = JWT::decode($token, $jwt_secret_key, array('HS256'));
	} catch (Exception $err) {
		// Token not valid
		$app->response()->status(401);
		echo '{ "success": false, "message": "Failed to authenticate token"}';
		return;
	}

	// Token valid
	echo json_encode($decoded);

});

?>