<?php
require_once './db/dbManage_Film_Example_dbManager.php';
	
/*
 * SCHEMA DB User
 * 
	{
		mail: {
			type: 'String'
		},
		name: {
			type: 'String'
		},
		password: {
			type: 'String', 
			required : true
		},
		surname: {
			type: 'String'
		},
		username: {
			type: 'String', 
			required : true
		},
		//RELAZIONI
		
		
		//RELAZIONI ESTERNE
		
		
	}
 * 
 */


//CRUD METHODS


//CRUD - CREATE


$app->post('/Users/',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'mail'	=> isset($body->mail)?$body->mail:'',
		'name'	=> isset($body->name)?$body->name:'',
		'password'	=> $body->password,
		'surname'	=> isset($body->surname)?$body->surname:'',
		'username'	=> $body->username
	);

	$user = makeQuery("INSERT INTO user (_id, mail, name, password, surname, username )  VALUES ( null, :mail, :name, :password, :surname, :username   )", $params, false);
    
    // Create Roles
    updateRoles($user['id'], $body->roles);
    
	echo json_encode($body);
});
	
//CRUD - REMOVE

$app->delete('/Users/:id',	function ($id) use ($app){
	
	$params = array (
		'id'	=> $id,
	);

	makeQuery("DELETE FROM user WHERE _id = :id LIMIT 1", $params, false);

    // Create Roles
    updateRoles($id, []);

});
	
//CRUD - GET ONE
	
$app->get('/Users/:id',	function ($id) use ($app){
	$params = array (
		'id'	=> $id,
	);
	
	$user = makeQuery("SELECT * FROM user WHERE _id = :id LIMIT 1", $params, false);
    
    // Get roles
	$roles = makeQuery( "SELECT * FROM roles WHERE _user=:id" , $params, false);
	$user->roles = [];
	foreach ($roles as $role) {
		array_push($user->roles, $role->role);
    }
    
    echo json_encode($user);
	
});
	
	
//CRUD - GET LIST

$app->get('/Users/',	function () use ($app){
    $list = makeQuery("SELECT * FROM user", [], false);
    
    foreach ($list as $user) {
        // Get roles
        $params = array (
            'id'	=> $user->_id,
        );

        $roles = makeQuery( "SELECT * FROM roles WHERE _user=:id" , $params, false);
        $user->roles = [];
        foreach ($roles as $role) {
            array_push($user->roles, $role->role);
        }
    }
    
    echo json_encode($list);

});


//CRUD - EDIT

$app->post('/Users/:id',	function ($id) use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'id'	=> $id,
		'mail'	    => isset($body->mail)?$body->mail:'',
		'name'	    => isset($body->name)?$body->name:'',
		'surname'	    => isset($body->surname)?$body->surname:'',
	);

	$user = makeQuery("UPDATE user SET  mail = :mail,  name = :name,  surname = :surname WHERE _id = :id LIMIT 1", $params, false);
    
    // Create Roles
    updateRoles($id, $body->roles);
    
    echo json_encode($body);
    
});

// Utils functions
function updateRoles($id_user, $roles) {
    
    // Remove roles not present
    $rolesStr = "";
    $first = true;
    foreach ($roles as $role) {
        if ($first) {
            $first = false;
        } else {
            $rolesStr = $rolesStr.',';
        }
        $rolesStr = $rolesStr."'".$role."'";
    }

	$params = array (
        'id_user'	=> $id_user,
    );

	$in = " and \"role\" NOT IN ( ".$rolesStr." )";
	$sql = "DELETE FROM Roles WHERE _user=:id_user ";
    
    if ($roles != null && count($roles) > 0)
        $sql = $sql.$in;

    $del = makeQuery($sql, $params, false);    

    // Get actual roles
    $actual_roles_obj = makeQuery("SELECT * from Roles WHERE \"_user\"=:id_user", $params, false);
    $actual_roles = [];
    foreach ($actual_roles_obj as $role) {
        array_push($actual_roles, $role->role);
    }

    // Insert new
    foreach ($roles as $role) {
        if (!in_array($role, $actual_roles)) {
            $params['role'] = $role;
            makeQuery("INSERT INTO Roles (_id, role, _user) VALUES (null, :role, :id_user)", $params, false);
        }
    }

}

/*
 * CUSTOM SERVICES
 *
 */

			
?>