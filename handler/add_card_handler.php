<?php
session_start();
if(isset($_POST['submit'])){
    require_once "conection.php";
    $sql = "INSERT INTO `table` (`id`, `title`, `text`, `soft_delete`) VALUES (NULL, :title, :text, :soft_delete)" ;
    $query = $pdo->prepare($sql);
    $query->execute([
        'title' => $_POST['title'],
        'text' => $_POST['text'],
        'soft_delete' => 0,
    ]);
    $_SESSION['success'] = "Задача успешно добавлена";
    header("location:../index.php");
    exit;
}
?>