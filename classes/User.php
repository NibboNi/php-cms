<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class User
{

  /**
   * Unique identifier
   * @var integer
   */
  public $id;
  /**
   * Unique username
   * @var string
   */
  public $username;
  /**
   * Password
   * @var string
   */
  public $password;

  /**
   * Authenticate username and password credentials
   *
   * @param PDO Database connection
   * @param string username
   * @param string password
   *
   * @return boolean True if credentials are valid, null otherwise
   */
  public static function auth($conn, $username, $password)
  {
    $query = "SELECT username, password
              FROM user
              WHERE username=:username
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
    $stmt->execute();
    // $user = $stmt->fetch();

    if ($user = $stmt->fetch()) {
      return password_verify($password, $user->password);
    }
  }
}
