<?php

require_once('../setup.php');

$session = ModusSession::getSession();

$request = new WebRequest();
$response = new GenericResponse;

$controller = new WebController($request);
$controller->setResponseFormatter(new WebResponseFormatter);
$controller->setResponse($response);

$response->setDefaultView(Config::get('defaultView'));

$cr = $controller->execute();