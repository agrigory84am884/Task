<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

class DB
{

    /** @var DB */
    private static $instance;

    /** @var PDO  */
    protected $connection;


    private function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            print_r($exception);
            http_response_code(500);
            echo "Can't connect to DB.";
            die;
        }
    }

    /**
     * Returns instance of static
     *
     * @return DB
     */
    public static function getInstance(): DB
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Returns Connected PDO instance
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param string $query
     * @param array $params
     * @return PDOStatement
     * @throws ApplicationException
     */
    public static function getQueryStatement(string $query, array $params = []): PDOStatement
    {
        $statement = static::getInstance()->getStatement($query);

        if (!$statement) {
            throw new \UnexpectedValueException('SQL Statement is false.');
        }

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value); //this will prevent us from sql injections
        }

        try {
            $statement->execute();
        }catch (PDOException $e){
            throw new ApplicationException("Statement execute Error: " . $e->getMessage());
        }

        return $statement;
    }

    /**
     * @param string $query
     * @return false|PDOStatement
     */
    public function getStatement(string $query): false|PDOStatement
    {
        return static::$instance->connection->prepare($query);
    }
}