<?php

require_once '../../lib/modus/config/Config.php';
require_once '../../lib/modus/Modus.php';

Config::readConfigFile('../config/application.php');

Modus::setIncludePath(Config::get('basedir'), Config::get('propel/install'), Config::get('propel/model'));
Modus::setAutoloader();

//Propel::init(Config::get('basedir') . '/webapp/config/model-conf.php');