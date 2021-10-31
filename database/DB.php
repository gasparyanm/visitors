<?php
if ( !isset( $_SERVER['HTTP_REFERER']) ) die ("Direct access not permitted");

/**
 * Make connection to DB
 */
class DB
{
    private string $username;
    private string $password;
    private string $host;
    private string $db;
    private int $port;

    public $conn;
    private ?string $table = null;

    public function __construct(
        string $username,
        string $password,
        string $host,
        string $db,
        int $port = 3306
    )
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->db = $db;
        $this->port = $port;
    }

    public function connect()
    {
        $this->conn = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->db,
        );

        return $this;
    }

    public function close()
    {
        $this->conn->close();
    }

    public function setChar(string $char): DB
    {
        mysqli_set_charset($this->conn, $char);

        return $this;
    }

    public function setTable(string $table): DB
    {
       $this->table = $table;

        return $this;
    }

    public function insert(array $columns, array $values)
    {
        $escapedColumns = $this->makeDbColumns($columns);
        $currentRowValues = $this->makeDbInsertValues($values);

        $query = 'INSERT INTO ' . $this->table . ' (' . $escapedColumns . ') ' .
            'VALUES (' . $currentRowValues . ')';

        return $this->conn->query($query);
    }

    public function getVisitorData(string $pageUrl, string $ip, string $userAgent): ?array
    {
        $query = 'SELECT * FROM ' . $this->table .
            ' WHERE page_url = "' . $this->escape($pageUrl) .
            '" AND ip_address = "' . $this->escape($ip) .
            '" AND user_agent = "' . $this->escape($userAgent) . '"';

        $result = $this->conn->query($query)->fetch_assoc();

        return $result;
    }

    public function updateVisit(int $id, array $data)
    {
        $conditions = [];

        foreach ($data as $column => $value) {
            $conditions[] = "`{$column}` = '{$value}'";
        }

        $conditions = implode(',', $conditions);

        $query = "UPDATE " . $this->table . " SET {$conditions} WHERE id = {$id}";

        return $this->conn->query($query);
    }

    public function makeDbInsertValues(array $values)
    {
        $escapedArray = array_map(function ($value) {
            return $this->escape($value);
        }, $values);

        $currentRowValues  = implode("', '", $escapedArray);
        $currentRowValues = "'" . $currentRowValues . "'";

        return $currentRowValues;
    }

    public function makeDbColumns(array $columns)
    {
        $columnsLowerCased = array_map(function ($column) { return strtolower($column); }, $columns);

        $columns = implode('`, `', $columnsLowerCased);
        $columns = "`" . $columns . "`";

        return $columns;
    }

    public function escape($value): string
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    public static function now()
    {
        return date('Y-m-d H:i:s');
    }
}
?>