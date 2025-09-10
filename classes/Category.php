<?php

class Category
{
  /**
   * Fetch All articles from the data base
   *
   * @param PDO object connection to the database
   * @return array An associative array of all the article records 
   */
  public static function getAll($conn)
  {
    $query = "SELECT *
              FROM category
              ORDER BY name;
    ";

    $response = $conn->query($query);

    return $response->fetchAll(PDO::FETCH_ASSOC);
  }
}
