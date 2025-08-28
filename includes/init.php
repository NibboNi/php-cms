<?php

/**
 * Initialisations
 *
 * Regsiter an autoloader, start or resume the session.
 */

declare(strict_types=1);

spl_autoload_register(function ($class) {
  require "classes/{$class}.php";
});

session_start();
