#!/usr/bin/env php

<?php

require './vendor/autoload.php';

$app = new \Lcarr\CommandRunner\App(new \Lcarr\CommandRunner\Arguments());

$app->run();