<?php defined('SCAFFOLD') or die();

class DatabaseDriverPDO extends DatabaseDriver {

    private $conn = false;
    private $query = false;

    /**
     * Connect to the database via PDO
     */
    public function connect() {
        $exports = array('type', 'host', 'username', 'password', 'database');
        $vals = arguments($exports, $this->config);
        extract($vals);

        $connstring = strtolower($type) . ':host=' . $host .';dbname=' . $database;
        $this->connection = new PDO($connstring, $username, $password);

        return $this;
    }

    /**
     * Find a row
     *
     * @param string $table Table to search
     */
    public function find($table, $options) {

        if (!is_array($options)) {
            $options = ['where' => ['id' => $options]];
        }

        $values = [
            'from' => $table,
            'vals' => ['*'],
            'where' => [],
            'order' => [],
            'group' => [],
            'having' => [],
            'limit' => []
        ];

        foreach ($options as $key => $val) {
            if (isset($values[$key])) {
                $values[$key] = $val;
            }
        }

        $query = call_user_func_array([$this->builder, 'select'], $values);

        return $this->query($query);
    }

    /**
     * Fetch one row.
     *
     * @return array Associative array of data
     */
    public function fetch($table = null, $options = null) {
        if (!is_null($table) && !is_null($options)) $this->find($table, $options);

        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all rows
     *
     * @return array Array of associative arrays of data
     */
    public function fetch_all($table = null, $options = null) {
        if (!is_null($table) && !is_null($options)) $this->find($table, $options);

        return $this->query->fetch_all(PDO::FETCH_ASSOC);
    }

    /**
     * Run a query.
     *
     * @param string $sql sql to run
     */
    private function query($sql) {
        if ($this->connection) {
            $this->query = $this->connection->query($sql);

            return $this;
        }

        return false;
    }

}
