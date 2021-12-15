#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Console\Application;

$app = new Application("The Bot", 1.0);

$app->add(new \App\Commands\BotCommand());

$app->run();