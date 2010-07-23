<?php

require_once '../config/application.php';
require_once '../../lib/modus/Modus.php';

Modus::setIncludePath(BASE_DIR, PROPEL_INSTALL, PROPEL_MODEL);
Modus::setAutoloader();

Propel::init(BASE_DIR . '/webapp/config/model-conf.php');