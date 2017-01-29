<?php
namespace Bookshelf;

use Monolog\Logger;

class AuthorMapper
{
    protected $logger;
    protected $db;

    public function __construct(Logger $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    /**
     * Fetch all authors
     *
     * @return [Author]
     */
    public function fetchAll()
    {
        $sql = "SELECT * FROM author ORDER BY name ASC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Author($row);
        }

        return $results;
    }

    /**
     * Load a single author
     *
     * @return Author|false
     */
    public function loadById($id)
    {
        $sql = "SELECT * FROM author WHERE author_id = :author_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['author_id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Author($data);
        }

        return false;
    }

    /**
     * Save a author
     *
     * @return boolean
     */
    public function save(Author $author)
    {
        $data = $author->getArrayCopy();
        $data['updated'] = date('Y-m-d H:i:s');

        $query = "INSERT INTO author (author_id, name, biography, date_of_birth, created, updated)
            VALUES (:author_id, :name, :biography, :date_of_birth, :created, :updated)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($data);

        return new Author($data);
    }
}