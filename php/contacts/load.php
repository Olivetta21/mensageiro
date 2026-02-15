<?php
require "../include.php";

$info = validUserAndGetDB();
$db = $info['db'];
$user_id = $info['user_id'];


try {
    $sql = "select id from contacts_linked where :user_id in (user_id_a, user_id_b)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo fToJson(["success" => true, "contacts" => $contacts]);
    exit;

} catch (Exception $e) {
    echo fToJson(["error" => "database_error", "details"=>$e->getMessage()]);
    exit;
}