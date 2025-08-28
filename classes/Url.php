<?php

/**
 * URL
 *
 * Response method 
 */
class Url
{
  /**
   * Redirect to another URL on the same site
   *
   * @param string $path The path to redirect to
   * @return void
   */
  public static function redirect(string $path): void
  {
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != 'off') {
      $protocol = "https";
    } else {
      $protocol = "http";
    }

    header("Location: $protocol://" . $_SERVER["HTTP_HOST"] . $path);
    exit;
  }
}
