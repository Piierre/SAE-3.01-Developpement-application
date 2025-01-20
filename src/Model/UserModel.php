<?php
require '../Config/Conf.php';

class UserModel {
    public static function getUserByLogin($login) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch();
    }

    public static function createUser($login, $hashedPassword) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO Utilisateur (login, mdp) VALUES (?, ?)");
            return $stmt->execute([$login, $hashedPassword]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
