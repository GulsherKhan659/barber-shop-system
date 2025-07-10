
<?php

class Configue {
    public $servername;
    public $username;
    public $password;
    public $database;

    public function __construct() {
        $this->servername = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->database = "barberbookingsystem";
    }
}

?>
