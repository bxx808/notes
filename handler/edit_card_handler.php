<?php 
session_start();
if (isset($_POST['edit'])){
    require_once ("conection.php");
    $sql = "UPDATE `table` SET `title`:=title, `text`:=text, `soft_delete`:=soft_delete WHERE `id`:=id";
    $query = $pdo->prepare($sql);
    $data = [
        'id' => $_GET['id'],
        'title' => $_POST['title'],
        'text' => $_POST['text'],
        'soft_delete' => 0,
    ];
    $query->execute($data);
    $_SESSION['success'] = "Задача успешно редактированна" ;
    header("location: ../index.php");
    exit;
} else {
    $_SESSION['error'] = "Задача не найдена" ;
    header("location: ../index.php");
    exit;
}