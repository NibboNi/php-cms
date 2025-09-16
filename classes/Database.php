<?php

declare(strict_types=1);

/**
 * Database
 *
 * A connection to the database
 */
class Database
{

  /**
   * Hostname
   * @var string
   */
  protected $dbHost;

  /**
   * Database name
   * @var string
   */
  protected $dbName;

  /**
   * Username
   * @var string
   */
  protected $dbUser;

  /**
   * Password
   * @var string
   */
  protected $dbPass;

  /**
   * Constructor
   *
   * @param string $host Hostname
   * @param string $name Database name
   * @param string $user Username
   * @param string $pasword Password
   * @return void
   */
  public function __construct($host, $name, $user, $password)
  {
    $this->dbHost = $host;
    $this->dbName = $name;
    $this->dbUser = $user;
    $this->dbPass = $password;
  }

  /**
   * Get the database connection
   *
   * @return PDO object Connection to the database server
   */
  public function getConnection(): ?PDO
  {

    $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8mb4";

    try {

      $db = new PDO($dsn, $this->dbUser, $this->dbPass);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $db;
    } catch (PDOException $e) {

      echo $e->getMessage();
      return null;
    }
  }
}
