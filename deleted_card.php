<?php session_start();
require_once "handler/conection.php";
if (isset($_GET['page'])) {
    $page = ($_GET['page'] * 12) - 12;
    $sql = "SELECT * FROM `table` WHERE `soft_delete`= 1 LIMIT 12 OFFSET " . $page;
    $query = $pdo->prepare($sql);
    $query->execute();
} else {
    $sql = "SELECT * FROM `table` WHERE `soft_delete`= 1 LIMIT 12 OFFSET 0";
    $query = $pdo->prepare($sql);
    $query->execute();
}

$tables = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) FROM `table`";
$query = $pdo->prepare($sql);
$query->execute();
$tableCount = ceil($query->fetchColumn() / 12);

require_once "loyaut/head.php";
?>
<div class="notes_content">
    <div class="d-md-flex justify-content-center mt-3 mb-3">
        <form action="index.php" method="post">
            <button type="submit" class="btn btn-outline-danger ">
                Назад
            </button>
        </form>
    </div>
    <div class="container">
        <div class="row">
            <?php foreach ($tables as $table): ?>
                <div class="col-3">
                    <div class="card border-info mb-4">

                        <div class="header_content">
                            <div class="left_content">
                                <div class="card_header">Задача №<?= $table['id']; ?></div>
                            </div>

                            <div class="right_content">
                                <div class="function_btn">
                                    <form action="handler/recovery_card_handler.php" method="post">
                                        <input type="hidden" name="id" value="<?= $table['id']; ?>">
                                        <button class="recovery_btn" name="submit" type="submit">
                                            <i class="fa-duotone fa-backward"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <h5 name="title"><?= $table['title']; ?></h5>
                            </div>
                            <div class="">
                                <p name="text"><?= $table['text']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i < $tableCount + 1; $i++): ?>
            <li class="page-item <?= $_GET['page'] == $i ? ' active' : '' ?>"><a class="page-link"
                    href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor; ?>
    </ul>
</nav>

<?php require_once "loyaut/footer.php"; ?>