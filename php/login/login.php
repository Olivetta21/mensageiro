<?php
require_once "../include.php";

if (isset($_POST['login'])) {
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
        $sql = "SELECT id FROM users WHERE email = :email AND password = :password";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $result['id'] ?? null;

        if ($user_id) {
            $access_token = genTK();
            $expires_in = '300';

            $sql = "INSERT INTO access_tokens (user_id, token, expires_at) VALUES (:user_id, :token, NOW() + INTERVAL '$expires_in second')";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':token', $access_token, PDO::PARAM_STR);
            $stmt->execute();
            $db->commit();

            setcookie(
                "access_token",
                $access_token, time()+$expires_in, "/"
            );            
            $_SESSION['access_token'] = $access_token;

            echo fToJson(["success" => true, "mode"=>"login", "expires_in"=>$expires_in, "session"=>$_SESSION, "cookie"=>$_COOKIE]);
            exit;

        } else {
            echo fToJson(["error" => "invalid_credentials"]);
            exit;
        }

    } catch (Exception $e) {
        $db->rollBack();
        echo fToJson(["error" => "database_error", "details"=>$e->getMessage()]);
        exit;
    }
} else {
    $info = validUserAndGetDB();
    echo fToJson(["success" => true, "mode"=>"auto", "expires_in"=>$info['expires_in'], "session"=>$_SESSION, "cookie"=>$_COOKIE]);
    exit;
}
