<?php

/**
 * Article
 *
 * A price of writing for publications
 */
class Article
{

  /**
   * The article id
   * @var int
   */
  public $id;
  /**
   * The article title
   * @var string
   */
  public $title;
  /**
   * The article content
   * @var string
   */
  public $content;
  /**
   * The article publication date and time
   * @var string
   */
  public $published_at;
  /**
   * The article update date and time
   * @var string
   */
  public $updated_at;
  /**
   * Validatation errors 
   * @var array
   */
  public $formErrors = [];

  /**
   * Get a count of the total number of records
   *
   * @param PDO $conn Connection to the database
   * @return int Total number of records
   */
  public static function getTotal($conn)
  {
    return $conn->query("SELECT COUNT(*) FROM article;")->fetchColumn();
  }

  /**
   * Fetch All articles from the data base
   *
   * @param PDO object connection to the database
   * @return array An associative array of all the article records 
   */
  public static function getAll($conn)
  {
    $query = "SELECT *
              FROM article
              ORDER BY published_at;
    ";

    $response = $conn->query($query);

    return $response->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Fetch all articles within our limit and offset
   *
   * @param PDO $conn connection to the database
   * @param int $limit max amout of articles to fetch
   * @param int $offset from where to start fetching
   * @return array An associative array of all the article records within range
   */
  public static function getPage($conn, $limit = 8, $offset = 0)
  {
    $query = "SELECT *
              FROM article
              ORDER BY published_at
              LIMIT :limit
              OFFSET :offset
    ;";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get the article recprd based on the ID
   *
   * @param integer $id The article ID
   * @param PDO object $conn Connection to the database
   * @param string $columns Optional list of columns for the select, defaults to *
   *
   * @return mixed An object of this class, or null if not found
   */
  public static function getById($id, $conn, $columns = "*")
  {
    $query = "SELECT $columns
              FROM article
              WHERE id = :id
    ;";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    $stmt->setFetchMode(PDO::FETCH_CLASS, "Article");

    if ($stmt->execute()) {
      return $stmt->fetch();
    }
  }

  /**
   * Insert an article record
   *
   * @param PDO Object $conn Connection to the database 
   * @return boolean True if the insert query was successful, false otherwise
   */
  public function create(PDO $conn)
  {
    if ($this->validate()) {

      $query = "INSERT INTO article 
                (title, content)
                VALUES (:title, :content)
      ;";

      $stmt = $conn->prepare($query);
      $stmt->bindValue(":title", $this->title, PDO::PARAM_STR);
      $stmt->bindValue(":content", $this->content, PDO::PARAM_STR);

      if ($stmt->execute()) {
        $this->id = $conn->lastInsertId();
        return true;
      }
    } else {
      return false;
    }
  }

  /**
   * Update an article record
   *
   * @param PDO Object $conn Connection to the database 
   * @return boolean True if the update query was successful, false otherwise
   */
  public function update(PDO $conn)
  {
    if ($this->validate()) {

      $query = "UPDATE article 
                SET title=:title, content=:content, updated_at=:updated_at
                WHERE id=:id
      ;";

      $stmt = $conn->prepare($query);
      $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
      $stmt->bindValue(":title", $this->title, PDO::PARAM_STR);
      $stmt->bindValue(":content", $this->content, PDO::PARAM_STR);
      $stmt->bindValue(":updated_at", $this->updated_at, PDO::PARAM_STR);

      return $stmt->execute();
    } else {
      return false;
    }
  }

  /**
   * Validate the article properties, putting any validation error messages in the $errors property
   *
   * @return boolean True if the current properties are valid, false otherwise
   */
  protected function validate()
  {

    if (trim($this->title) === "") {
      $this->formErrors[] = "A title for the post is required";
    }
    if (strlen(trim($this->title)) > 128) {
      $this->formErrors[] = "A title can be longer than 128 characters";
    }
    if (trim($this->content) === "") {
      $this->formErrors[] = "A content for the post is required";
    }

    return empty($this->formErrors);
  }


  /**
   * Deletes record from DB
   *
   * @param object $conn Connection to the DB
   * @return boolean True if the delete query was successful, false otherwise
   */
  public function delete($conn)
  {
    $query = "DELETE FROM article 
              WHERE id=:id;
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function getDates()
  {

    $formatDate = function (?string $dateStr, bool $withTime = false): ?array {
      if (!$dateStr) {
        return null;
      }

      $date = new DateTime($dateStr);

      return [
        "tag" => $date->format($withTime ? "Y/m/d H:i:s" : "Y/m/d"),
        "attr" => $date->format($withTime ? "Y-m-d H:i:s" : "Y-m-d"),
      ];
    };

    $hasUpdate = !empty($this->updated_at);

    $published = $formatDate($this->published_at, $hasUpdate) ?? ["tag" => null, "attr" => null];
    $updated = $formatDate($this->updated_at, true) ?? ["tag" => null, "attr" => null];

    return [
      "publishedDate" => $published["tag"],
      "publishedDateTime" => $published["attr"],
      "updatedDate" => $updated["tag"],
      "updatedDateTime" => $updated["attr"]
    ];
  }
}
