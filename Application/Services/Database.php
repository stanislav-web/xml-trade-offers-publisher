<?php
namespace Application\Services;

use Application\Exceptions\DbException;

/**
 * Class Database
 *
 * @package Application\Services
 */
class Database {

    /**
     * Db configuration
     *
     * @var array $config
     */
    private $config = null;

    /**
     * Database connection handler
     *
     * @var \PDO
     */
    private $connect = null;

    /**
     * Holding PDO statement
     *
     * @var \PDOStatement $statement
     */
    private $statement;

    /**
     * Init connection
     *
     * @param array $config
     * @throws DbException
     */
    public function __construct(array $config) {

        // Set DSN
        $dsn = $config['driver'] . ":host =" . $config['hostname'].";".
            "dbname=" . $config['database'];

        // Set options
        $options = [
            \PDO::ATTR_ERRMODE      => $config['debug'],
            \PDO::ATTR_PERSISTENT   => $config['connect'],
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $config['charset']
        ];
        try {

            // Create a new PDO instance
            if ($this->connect === null) {
                $this->connect = new \PDO($dsn, $config['username'], $config['password'], $options);
            }
            $this->config = $config;
        }
        catch (\PDOException $e) {
            // Catch any errors
            throw new DbException($e->getMessage());
        }
    }

    /**
     * Prepare query
     *
     * @param string $query
     * @return \PDOStatement
     */
    public function query($query) {

        $this->statement = $this->connect->prepare($query);
        return $this;
    }

    /**
     * Binding PDO data
     *
     * @param      $param
     * @param      $value
     * @param null $type
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }

        $this->statement->bindValue($param, $value, $type);
    }

    /**
     * Execution query
     *
     * @return mixed
     */
    public function execute() {
        return $this->statement->execute();
    }

    /**
     * Fetching all result
     *
     * @throws DbException
     * @return array|object|both
     */
    public function fetchAll() {

        try {
            $this->execute();
            return $this->statement->fetchAll($this->config['fetching']);
        }
        catch(\PDOException $e) {
            throw new DbException($e->getMessage());
        }

    }

    /**
     * Fetching one result
     *
     * @throws DbException
     * @return array|object|both
     */
    public function fetchOne() {

        try {
            $this->execute();
            return $this->statement->fetch($this->config['fetching']);
        }
        catch(\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    /**
     * Get count rows after executing query
     *
     * @return int
     */
    public function rowCount() {
        return $this->statement->rowCount();
    }

    /**
     * Get last insert Id
     *
     * @return int
     */
    public function lastInsertId() {
        return $this->connect->lastInsertId();
    }

    /**
     * Transaction start
     *
     * @return bool
     */
    public function beginTransaction() {
        return $this->connect->beginTransaction();
    }

    /**
     * Transaction end
     *
     * @return bool
     */
    public function endTransaction() {
        return $this->connect->commit();
    }

    /**
     * Transaction cancel
     *
     * @return bool
     */
    public function cancelTransaction()
    {
        return $this->connect->rollBack();
    }
}