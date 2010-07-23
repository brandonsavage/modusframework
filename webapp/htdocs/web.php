<?php

require_once('../setup.php');

$session = ModusSession::getSession();

$request = new WebRequest();

$controller = new BaseController($request);

Modus::setRequest($request);
Modus::setController($controller);
Modus::setResponseClassname('WebResponseFormatter');


$cr = $controller->execute();

