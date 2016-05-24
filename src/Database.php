<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 16.24.5
 * Time: 14:26
 */

namespace Application;

class Database
{
    /**
     * @var Database The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Database The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @var \PDO
     */
    protected $connection;
    protected $connectionsPool = array();

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {

    }

    public function getConnection()
    {
        if ($this->connection === null) {
            $this->switchConnection('bachelors_analytics');
        }

        return $this->connection;
    }

    public function switchConnection($dbname)
    {
        if (array_key_exists($dbname, $this->connectionsPool)) {
            $this->connection = $this->connectionsPool[$dbname];
            return true;
        }
        try {
            $this->connectionsPool[$dbname] = $this->connection = new \PDO('mysql:host=localhost;dbname=' . $dbname, 'root', '');
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}