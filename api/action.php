<?php
//dependency import
require 'properties.php';
require 'lib/Slim/Slim.php';
require 'security/Security.php';

//init Slim Framework
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \Security($app));
require 'security/Login.php';
require 'security/ManageUser.php';

//resources
	//db Manage_Film_Example_db
		require('./resource/Manage_Film_Example_db/custom/ActorCustom.php');
		require('./resource/Manage_Film_Example_db/Actor.php');
		require('./resource/Manage_Film_Example_db/custom/FilmCustom.php');
		require('./resource/Manage_Film_Example_db/Film.php');
		require('./resource/Manage_Film_Example_db/custom/FilmMakerCustom.php');
		require('./resource/Manage_Film_Example_db/FilmMaker.php');
	

$app->run();


?>
