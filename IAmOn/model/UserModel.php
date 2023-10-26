<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertUser($username, $password, $email) {
        $sql = "INSERT INTO usuarios (Alias, Password, Email) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        return $stmt->execute();
    }
}
?>

