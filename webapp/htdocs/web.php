<?php

require_once('../setup.php');

$session = Session::getSession();

$request = new Request_Web();
$response = new Response_Generic;

$controller = new Controller_Web($request);
$controller->setResponseFormatter(new Formatter_WebResponse);
$controller->setResponse($response);

$response->setDefaultView(Config::get('defaultView'));

$cr = $controller->execute();