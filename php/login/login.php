<?php
require_once "../include.php";

$access_token = null;
$expires_at = null;

if (isset($_POST['login'])) {
    session_destroy();
    setcookie("access_token", "", time() - 3600, "/");

    $login = json_decode($_POST['login'], true);
    $email = $login[0];
    $password = $login[1];

    if (empty($email) || empty($password)) {
        echo fToJson(["error" => "missing_fields"]);
        exit;
    }
	
    $db = getDataBase();
    $db->beginTransaction();
    try {
        $sql = "SELECT id FROM user WHERE email = :email AND password = :password";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $userExists['id'] ?? null;

        if ($user_id) {
            $access_token = genTK();
            $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $sql = "INSERT INTO access_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':token', $access_token, PDO::PARAM_STR);
            $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);
            $stmt->execute();
            $db->commit();

            setcookie(
                "access_token",
                $access_token,
                [
                    'expires' => strtotime($expires_at),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );
            
            $_SESSION['access_token'] = $access_token;

            echo fToJson(["success" => true, "mode"=>"login"]);
            exit;

        } else {
            echo fToJson(["error" => "invalid_credentials"]);
            exit;
        }

    } catch (Exception $e) {
        $db->rollBack();
        echo fToJson(["error" => "database_error"]);
        exit;
    }
} else if (validUserAndGetDB()) {
    echo fToJson(["success" => true, "mode"=>"auto"]);
    exit;
}



echo fToJson(["error" => "endline"]);
exit;
