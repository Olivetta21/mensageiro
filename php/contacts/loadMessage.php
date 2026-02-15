<?php
require "../include.php";

$info = validUserAndGetDB();
$db = $info['db'];
$user_id = $info['user_id'];

$contact_id = null;
if (isset($_POST['contact_id'])) {
    $contact_id = json_decode($_POST['contact_id'], true);
    if (!is_int($contact_id)) {
        echo fToJson(["error" => "invalid_request", "details"=>"contact_id must be an integer"]);
        exit;
    }
} else {
    echo fToJson(["error" => "invalid_request", "details"=>"missing contact_id"]);
    exit;
}

$sql = "select 1 from contacts_linked where id = :contact_id and :user_id in (user_id_a, user_id_b)";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $realContact = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($realContact)) {
        echo fToJson(["error" => "invalid_request", "details"=>"contact not found"]);
        exit;
    }

    $sql = "select id, case when sender_id = :user_id then true else false end as you, content, created_at from messages where contact_linked_id = :contact_id order by created_at";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':contact_id', $contact_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo fToJson(["success" => true, "messages" => $messages]);

} catch (Exception $e) {
    echo fToJson(["error" => "database_error", "details"=>$e->getMessage()]);
    exit;
}