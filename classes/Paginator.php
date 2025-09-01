<?php

/**
 * Paginator
 *
 * Data for selecting a page of records 
 */
class Paginator
{

  /**
   * Number of records to return
   * @var int
   */
  public $limit;
  /**
   * Number of records to skip
   * @var int
   */
  public $offset;
  /**
   * Previous page number
   * @var int
   */
  public $prev;
  /**
   * Next page number
   * @var int
   */
  public $next;

  /**
   * Constructor
   *
   * @param int $page Page number
   * @param int $recordsPerPage Max number of records per page
   * @return void
   */
  public function __construct($page, $recordsPerPage, $totalPages)
  {
    $this->limit = $recordsPerPage;

    $page = filter_var($page, FILTER_VALIDATE_INT, [
      "options" => [
        "default" => 1,
        "min_range" => 1,
      ]
    ]);

    if ($page - 1 > 0) {
      $this->prev = $page - 1;
    }

    $totalPages = ceil($totalPages / $recordsPerPage);

    if ($page < $totalPages) {
      $this->next = $page + 1;
    }

    $this->offset = $recordsPerPage * ($page - 1);
  }
}
