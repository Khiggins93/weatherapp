<?php

use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('America/Bogota');

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});


//Ruta de demostración, para validar que se recibe(n) dato(s) y se responde con este mismo
$app->post('/enviarDato', function (Request $request) use ($app) {
   return $request;
});



//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/guardarDato', function (Request $request) use ($app) {	

  $temperature = $request->get('temperature');
  $humidity = $request->get('humidity');

	$dbconn = pg_pconnect("host=ec2-54-160-18-230.compute-1.amazonaws.com
  port=5432 dbname=dbal62q3heftpo user=kpshnmcnemzbbl password=578e316675899fc6a891736045d00f0f4adc63171016be40bc8f16c90f0cf2de");
  
  $data = array(
  "Date" => date("Y-m-d H:i:s"),
  "temperature" => $temperature,
  "humidity" => $humidity
);
  $respuesta = pg_insert($dbconn, "weather_db", $data);

   	return $respuesta;
});

//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/postArduino', function (Request $request) use ($app) {
   	return "OK";
});

$app->run();
