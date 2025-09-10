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
   * The article image
   * @var string
   */
  public $image_file;
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
    $query = "SELECT a.*, category.name AS category_name
              FROM ( SELECT *
              FROM article
              ORDER BY published_at
              LIMIT :limit
              OFFSET :offset ) AS a
              LEFT JOIN article_category
              ON a.id = article_category.article_id
              LEFT JOIN category
              ON article_category.category_id = category.id
    ;";

    $stmt = $conn->prepare($query);

    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $articles = [];

    $previousId = null;

    foreach ($results as $row) {

      $articleId = $row["id"];

      if ($articleId !== $previousId) {

        $row["category_names"] = [];

        $articles[$articleId] = $row;
      }

      $articles[$articleId]["category_names"][] = $row["category_name"];

      $previousId = $articleId;
    }

    return $articles;
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
   * Get article record based on the ID along with associated categories, if any
   *
   * @param PDO $conn Connection to the database
   * @param int $id The article ID
   *
   * @return array The article data with categories
   */
  public static function getWithCategories($id, $conn)
  {
    $query = "SELECT article.*, category.name AS category_name
              FROM article
              LEFT JOIN article_category
              ON article.id = article_category.article_id
              LEFT JOIN category
              ON article_category.category_id = category.id
              WHERE article.id = :id      
    ;";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
   * Get the articles categories if any
   *
   * @param PDO $conn Connection to the database 
   * @return array The category data
   */
  public function getCategories($conn)
  {
    $query = "SELECT category.*
              FROM category
              JOIN article_category
              ON category.id = article_category.category_id  
              WHERE article_id = :id      
    ;";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
   * Sets categories related to the calling Article
   *
   * @param PDO $conn Connection to the database
   * @param array $ids A list of the categories ids to set
   * 
   * @return void
   */
  public function setCategories($conn, $ids)
  {
    if ($ids) {
      $query = "INSERT IGNORE INTO article_category (article_id, category_id)
                VALUES ";

      $values = [];

      foreach ($ids as $id) {
        $values[] = "({$this->id}, ?)";
      }

      $query .= implode(", ", $values) . ";";

      $stmt = $conn->prepare($query);

      foreach ($ids as $i => $id) {
        $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
      }

      $stmt->execute();
    }

    $query = "DELETE FROM article_category
              WHERE article_id = {$this->id}";

    if ($ids) {
      $placeholders = array_fill(0, count($ids), "?");

      $query .= " AND category_id NOT IN (" . implode(", ", $placeholders) . ");";
    }

    $stmt = $conn->prepare($query);

    foreach ($ids as $i => $id) {
      $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
    }

    $stmt->execute();
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

  /**
   * Update the image file property
   *
   * @param PDO $conn The connection to the database
   * @param string $filename The name of the image file
   * 
   * @return boolean True if it was successful, false otherwise
   */
  public function setImageFile($conn, $filename)
  {
    $query = "UPDATE article 
              SET image_file=:filename
              WHERE id=:id 
    ;";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":filename", $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}
