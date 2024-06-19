<?php
session_start();
if (isset($_POST['delete'])) {
    require_once ('conection.php');
    $sql = "SELECT * FROM `table` WHERE id=:id AND `soft_delete`=0";
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $_POST['id']]);
    $table = $query->fetch(PDO::FETCH_ASSOC);
    if ($table) {
        $sql = "UPDATE `table` SET `soft_delete`='1' WHERE id=:id";
        $query = $pdo->prepare($sql);
        $query->execute(['id' => $_POST['id']]);
        $_SESSION['success'] = "Задача успешно удалена";
    } else {
        $_SESSION['error'] = "Задача не найдена";
    }
    header("location: ../index.php");
}
?>