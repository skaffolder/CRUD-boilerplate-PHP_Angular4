<?php
	require_once './db/dbManage_Film_Example_dbManager.php';
	
/*
 * SCHEMA DB Actor
 * 
	{
		birthDate: {
			type: 'Date'
		},
		name: {
			type: 'String', 
			required : true
		},
		surname: {
			type: 'String'
		},
		//RELAZIONI
		
		
		//RELAZIONI ESTERNE
		
		cast: [{
			type: Schema.ObjectId,
			ref : "Film"
		}],
		
	}
 * 
 */


//CRUD METHODS


//CRUD - CREATE


$app->post('/actors',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'birthDate'	=> isset($body->birthDate)?$body->birthDate:'',
		'name'	=> $body->name,
		'surname'	=> isset($body->surname)?$body->surname:'',
		
	);

	$obj = makeQuery("INSERT INTO actor (_id, birthDate, name, surname )  VALUES ( null, :birthDate, :name, :surname   )", $params, false);
    
	
	echo json_encode($body);
	
});
	
//CRUD - REMOVE

$app->delete('/actors/:id',	function ($id) use ($app){
	
	$params = array (
		'id'	=> $id,
	);

	makeQuery("DELETE FROM actor WHERE _id = :id LIMIT 1", $params);

});
	
//CRUD - GET ONE
	
$app->get('/actors/:id',	function ($id) use ($app){
	$params = array (
		'id'	=> $id,
	);
	
	$obj = makeQuery("SELECT * FROM actor WHERE _id = :id LIMIT 1", $params, false);
	
	
	
	echo json_encode($obj);
	
});
	
	
//CRUD - GET LIST

$app->get('/actors',	function () use ($app){
	makeQuery("SELECT * FROM actor");
});


//CRUD - EDIT

$app->post('/actors/:id',	function ($id) use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'id'	=> $id,
		'birthDate'	    => isset($body->birthDate)?$body->birthDate:'',
		'name'	    => $body->name,
		'surname'	    => isset($body->surname)?$body->surname:''
	);

	$obj = makeQuery("UPDATE actor SET  birthDate = :birthDate,  name = :name,  surname = :surname   WHERE _id = :id LIMIT 1", $params, false);
    
	
	echo json_encode($body);
    	
});


/*
 * CUSTOM SERVICES
 *
 *	These services will be overwritten and implemented in  Custom.js
 */

			
?>