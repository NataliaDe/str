<div class="bread noprint">
     <div class="container-fluid">
        <ol class="breadcrumb">
            <li id="page">Строевая записка</li>
            <li class="active">Закрыть доступ на редактирование </li>
            <?php
            if (isset($bread_array)) {
                foreach ($bread_array as $row) {
                    ?>
                    <li id="page"><?= $row['region'] ?></li>
                    <li id="page"><?= $row['locorg_name'] ?></li>
                    <li id="page"><?= $row['divizion'] ?></li>
                        <?php
                    }
                }
                ?>
        </ol>
    </div>
</div>