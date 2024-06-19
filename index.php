<?php session_start();
require_once "handler/conection.php";
$sql = 'SELECT * FROM `table` WHERE `soft_delete`= 0';
if (isset($_GET['searchTitle'])) {
    $sql .= " AND title LIKE CONCAT('%', :title, '%') ";
}
$sql .= " ORDER BY id DESC";
if (isset($_GET['page'])) {
    $page = ($_GET['page'] * 12) - 12;
    $sql .= " LIMIT 12 OFFSET " . $page;
} else {
    $sql .= " LIMIT 12 OFFSET 0";
}

$query = $pdo->prepare($sql);
$query->execute(['title' => $_GET['searchTitle']]);

$tables = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) FROM `table`";
$query = $pdo->prepare($sql);
$query->execute();
$tableCount = ceil($query->fetchColumn() / 12);


$prepare = $pdo->prepare($sql);
$prepare->execute();
$table = $prepare->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `table` WHERE id=:id";
$query = $pdo->prepare($sql);
$query->execute(['id' => $_GET['id']]);
$patient = $query->fetch();

require_once "loyaut/head.php";
?>


<div class="notes_content">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 d-flex mt-3 mb-4  gap-3">
                    <div class="col-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">
                            Добавить Задачу
                        </button>
                    </div>
                    <div class="col-4 ">
                        <form action="?" method="GET">
                            <input type="text" class="form-control" name="searchTitle" placeholder="Поиск по заголовку"
                                value="<?= $_GET['searchTitle'] ?? "" ?>">
                        </form>
                    </div>
                    <div class="col-4 d-flex justify-content-start">
                        <form action="deleted_card.php" method="post">
                            <button type="submit" class="btn btn-outline-success ">
                                Корзина
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal_card">
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="handler/add_card_handler.php" method="post">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Добавьте Задачу</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <input type="text" name="title" class="form-control mb-3" required>
                                    <input type="text" name="text" class="form-control" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Закрыть</button>
                                    <button name="submit" type="submit" class="btn btn-primary">Добавить</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal_card">
                <?php foreach ($tables as $table): ?>
                    <form action="edit_card_handler.php?id=<?= $table['id']; ?>" method="post">
                        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="handler/add_card_handler.php" method="post">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Редактировать Задачу
                                                №<?= $table['id'] ?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <input type="text" value="<?= $table['title'] ?>" name="title"
                                                class="form-control mb-3" required>
                                            <input type="text" value="<?= $table['text'] ?>" name="text"
                                                class="form-control" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Закрыть</button>
                                            <button name="submit" type="submit" class="btn btn-primary">Добавить</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </form>
                <?php endforeach; ?>
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
                                        <div class="dropdown">
                                            <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button data-bs-toggle="modal" data-bs-target="#editModal"
                                                        class="dropdown-item" name="edit"
                                                        type="submit">Редактировать</button></li>
                                                <form action="handler/delete_card_handler.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $table['id'] ?>">
                                                    <li><button class="dropdown-item" type="submit"
                                                            name="delete">Удалить</button>
                                                    </li>
                                                </form>
                                            </ul>
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