<?php
	require_once './db/dbManage_Film_Example_dbManager.php';
	
/*
 * SCHEMA DB FilmMaker
 * 
	{
		name: {
			type: 'String', 
			required : true
		},
		surname: {
			type: 'String'
		},
		//RELAZIONI
		
		
		//RELAZIONI ESTERNE
		
		filmMaker: {
			type: Schema.ObjectId, 
			required : true,
			ref : "Film"
		},
		
	}
 * 
 */


//CRUD METHODS


//CRUD - CREATE


$app->post('/filmmakers',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'name'	=> $body->name,
		'surname'	=> isset($body->surname)?$body->surname:'',
		
	);

	$obj = makeQuery("INSERT INTO filmmaker (_id, name, surname )  VALUES ( null, :name, :surname   )", $params, false);
    
	
	echo json_encode($body);
	
});
	
//CRUD - REMOVE

$app->delete('/filmmakers/:id',	function ($id) use ($app){
	
	$params = array (
		'id'	=> $id,
	);

	makeQuery("DELETE FROM filmmaker WHERE _id = :id LIMIT 1", $params);

});
	
//CRUD - GET ONE
	
$app->get('/filmmakers/:id',	function ($id) use ($app){
	$params = array (
		'id'	=> $id,
	);
	
	$obj = makeQuery("SELECT * FROM filmmaker WHERE _id = :id LIMIT 1", $params, false);
	
	
	
	echo json_encode($obj);
	
});
	
	
//CRUD - GET LIST

$app->get('/filmmakers',	function () use ($app){
	makeQuery("SELECT * FROM filmmaker");
});


//CRUD - EDIT

$app->post('/filmmakers/:id',	function ($id) use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'id'	=> $id,
		'name'	    => $body->name,
		'surname'	    => isset($body->surname)?$body->surname:''
	);

	$obj = makeQuery("UPDATE filmmaker SET  name = :name,  surname = :surname   WHERE _id = :id LIMIT 1", $params, false);
    
	
	echo json_encode($body);
    	
});


/*
 * CUSTOM SERVICES
 *
 *	These services will be overwritten and implemented in  Custom.js
 */

			
?>