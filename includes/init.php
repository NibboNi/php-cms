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

require dirname(__DIR__) . "/config.php";

function errorHandler($level, $message, $file, $line)
{
  throw new ErrorException($message, 0, $level, $file, $line);
}

function exceptionHandler($exception)
{
  echo "<div class='local-debug'>";
  echo "<h1>An error ocurred</h1>";

  if (SHOW_ERROR_DETAIL) {
    echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
    echo "<p>'" . $exception->getMessage() . "'</p>";
    echo "<p>Stack trace: <pre>" . $exception->getTraceAsString() . "</pre></p>";
    echo "<p>In file '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
  } else {
    echo "<p>Please try again latar.</p>";
  }

  echo "</div>";

  exit();
}


set_error_handler("errorHandler");
set_exception_handler("exceptionHandler");
