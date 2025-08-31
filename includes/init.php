<?php

/**
 * Initialisations
 *
 * Regsiter an autoloader, start or resume the session.
 */

declare(strict_types=1);

spl_autoload_register(function ($class) {
  require dirname(__DIR__) . "/classes/{$class}.php";
});

session_start();
