<?php
	require_once './db/dbManage_Film_Example_dbManager.php';
	
/*
 * SCHEMA DB Film
 * 
	{
		genre: {
			type: 'String',
			enum : ["Action","Crime","Fantasy","Horror"], 
		},
		title: {
			type: 'String', 
			required : true
		},
		year: {
			type: 'Number'
		},
		//RELAZIONI
		
		
		//RELAZIONI ESTERNE
		
		cast: [{
			type: Schema.ObjectId,
			ref : "Film"
		}],
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


$app->post('/films',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'genre'	=> isset($body->genre)?$body->genre:'',
		'title'	=> $body->title,
		'year'	=> isset($body->year)?$body->year:'',
		

		'filmMaker' => $body->filmMaker,
	);

	$obj = makeQuery("INSERT INTO film (_id, genre, title, year , filmMaker )  VALUES ( null, :genre, :title, :year , :filmMaker   )", $params, false);
    
    
	// Delete not in array
	$in = " and id_Actor NOT IN (:cast)";
	$sql = "DELETE FROM Film_cast WHERE id_Film=:id_Film ";
		
	$params = array (
		'id_Film'	=> $obj['id']
	);
	
	if (isset($body->cast) && $body->cast != null && sizeOf($body->cast) > 0) {
		$sql = $sql.$in;
		$params['cast'] = join("', '", $body->cast);
	}
	
	makeQuery($sql, $params, false);
	
	
	// Get actual
	$sql="SELECT id_Actor FROM Film_cast WHERE id_Film=:id";
	$params = array (
		'id'	=> $obj['id'],
	);
    $actual = makeQuery($sql, $params, false);
	$actualArray=[];
	foreach ($actual as $val) {
		array_push($actualArray, $val->id_Actor);
	}

	// Insert new
	if (isset($body->cast)) {
    	foreach ($body->cast as $id_Actor) {
    		if (!in_array($id_Actor, $actualArray)){
    			$sql = "INSERT INTO Film_cast (_id, id_Film, id_Actor ) VALUES (null, :id_Film, :id_Actor)";
    
    			$params = array (
    				'id_Film'	=> $obj['id'],
    				'id_Actor'	=> $id_Actor
    			);
        		makeQuery($sql, $params, false);
    		}
    	}
	}
	
	    
	
	echo json_encode($body);
	
});
	
//CRUD - REMOVE

$app->delete('/films/:id',	function ($id) use ($app){
	
	$params = array (
		'id'	=> $id,
	);

	makeQuery("DELETE FROM film WHERE _id = :id LIMIT 1", $params);

});

//CRUD - FIND BY cast

$app->get('/films/findBycast/:key',	function ($key) use ($app){	

	$params = array (
		'key'	=> $key,
	);
	makeQuery("SELECT * FROM film WHERE cast = :key", $params);
	
});

//CRUD - FIND BY filmMaker

$app->get('/films/findByfilmMaker/:key',	function ($key) use ($app){	

	$params = array (
		'key'	=> $key,
	);
	makeQuery("SELECT * FROM film WHERE filmMaker = :key", $params);
	
});
	
//CRUD - GET ONE
	
$app->get('/films/:id',	function ($id) use ($app){
	$params = array (
		'id'	=> $id,
	);
	
	$obj = makeQuery("SELECT * FROM film WHERE _id = :id LIMIT 1", $params, false);
	
	
	$list_cast = makeQuery("SELECT id_Actor FROM Film_cast WHERE id_Film = :id", $params, false);
	$list_cast_Array=[];
	foreach ($list_cast as $val) {
		array_push($list_cast_Array, $val->id_Actor);
	}
	$obj->cast = $list_cast_Array;
	
	
	
	echo json_encode($obj);
	
});
	
	
//CRUD - GET LIST

$app->get('/films',	function () use ($app){
	makeQuery("SELECT * FROM film");
});


//CRUD - EDIT

$app->post('/films/:id',	function ($id) use ($app){

	$body = json_decode($app->request()->getBody());
	
	$params = array (
		'id'	=> $id,
		'genre'	    => isset($body->genre)?$body->genre:'',
		'title'	    => $body->title,
		'year'	    => isset($body->year)?$body->year:''
,
		'filmMaker'      => $body->filmMaker
	);

	$obj = makeQuery("UPDATE film SET  genre = :genre,  title = :title,  year = :year  , filmMaker=:filmMaker  WHERE _id = :id LIMIT 1", $params, false);
    
	// Delete not in array
	$in = " and id_Actor NOT IN (:cast)";
	$sql = "DELETE FROM Film_cast WHERE id_Film=:id_Film ";
	
	$params = array (
		'id_Film'	=> $body->_id
	);
	
	if (isset($body->cast) && $body->cast != null && sizeOf($body->cast) > 0) {
		$sql = $sql.$in;
		$params['cast'] = join("', '", $body->cast);
	}
	
	makeQuery($sql, $params, false);
	
	
	// Get actual
	$sql="SELECT id_Actor FROM Film_cast WHERE id_Film=:id";
	$params = array (
		'id'	=> $body->_id,
	);
    $actual = makeQuery($sql, $params, false);
	$actualArray=[];
	foreach ($actual as $val) {
		array_push($actualArray, $val->id_Actor);
	}

	// Insert new
	if (isset($body->cast)) {
    	foreach ($body->cast as $id_Actor) {
    		if (!in_array($id_Actor, $actualArray)){
    			$sql = "INSERT INTO Film_cast (_id, id_Film, id_Actor ) VALUES (null, :id_Film, :id_Actor)";
    
    			$params = array (
    				'id_Film'	=> $body->_id,
    				'id_Actor'	=> $id_Actor
    			);
        		makeQuery($sql, $params, false);
    		}
    	}
	}
	
	    
	
	echo json_encode($body);
    	
});


/*
 * CUSTOM SERVICES
 *
 *	These services will be overwritten and implemented in  Custom.js
 */

			
?>