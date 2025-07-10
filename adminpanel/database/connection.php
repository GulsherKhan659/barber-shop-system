

<?php

// $dbObject=new Database($servername,$database,$username,$password);
class Database {
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($table, $data, $where) {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");

        $whereClause = "";
        foreach ($where as $key => $value) {
            $whereClause .= "$key = :w_$key AND ";
        }
        $whereClause = rtrim($whereClause, " AND ");

        $sql = "UPDATE $table SET $set WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);

        foreach ($where as $key => $value) {
            $data["w_" . $key] = $value;
        }

        return $stmt->execute($data);
    }

    public function delete($table, $where) {
        $whereClause = "";
        foreach ($where as $key => $value) {
            $whereClause .= "$key = :$key AND ";
        }
        $whereClause = rtrim($whereClause, " AND ");

        $sql = "DELETE FROM $table WHERE $whereClause";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($where);
    }

    public function select($table, $columns = '*', $where = []) {
        $whereClause = "";
        $params = [];

        if (!empty($where)) {
            $whereClause = " WHERE ";
            foreach ($where as $key => $value) {
                $whereClause .= "$key = :$key AND ";
                $params[$key] = $value;
            }
            $whereClause = rtrim($whereClause, " AND ");
        }

        $sql = "SELECT $columns FROM $table$whereClause";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function lastInsertId() {
    return $this->pdo->lastInsertId();
}

}

