
<?php
//работники, которых нельзя редакутировать
$not_edit_array = array();
if (isset($not_edit) && !empty($not_edit)) {
    foreach ($not_edit as $key => $value) {
        $not_edit_array[] = $value['id_fio'];
    }
}

//работники на больничном
if (isset($is_ill) && !empty($is_ill)) {
    foreach ($is_ill as $i) {
        $ill[] = $i['id_fio'];
        $id_of_ill[$i['id_fio']] = $i['id']; //массив фио=>ill.id
    }
} else {
    $ill = array();
}

?>
<div class="container" id="container-query-result">
    <div class="col-lg-12">
        <?php
        // НЕ имеет право РЦУ, уровень 2(УМЧС)
        if ($_SESSION['ulevel'] == 1 && ($_SESSION['is_admin'] == 1 || $_SESSION['can_edit'] == 1)) {

            ?>
            <div class="dropdown">
                <a href="/str/listfio_rcu/add">   <button class="btn btn-primary" type="button" >
                        Добавить работников
                    </button></a>

            </div>
            <?php
        }

        ?>

        <br>
        <!--        <div class="table-responsive"  id="tbl-query-result">-->
        <br><br>
        <table class="table table-condensed   table-bordered" id="tbl_list_fio">
            <!-- строка 1 -->
            <thead>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Звание</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Смена</th>
                    <?php
                    /* ------------- rcu ------------------- */
                    if ($_SESSION['ulevel'] == 1 && ($_SESSION['is_admin'] == 1 || $_SESSION['can_edit'] == 1)) {

                        ?>
                        <th>Ред.</th>
                        <th>Уд.</th>
                        <?php
                    }

                    ?>

                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Звание</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Смена</th>
                    <?php
                    /* ------------- rcu ------------------- */
                    if ($_SESSION['ulevel'] == 1 && ($_SESSION['is_admin'] == 1 || $_SESSION['can_edit'] == 1)) {

                        ?>
                        <th>Ред.</th>
                        <th>Уд.</th>
                        <?php
                    }

                    ?>
                </tr>
            </tfoot>

            <tbody>
                <?php
                foreach ($list_fio as $row) {
//                        $yes = 0;
//                        /* ----------- РЦУ админ видит только тех, кому можно закрыть больничный ----------- */
//                        if ($_SESSION['ulevel'] == 1 && $_SESSION['is_admin'] == 1) {//РЦУ админ
//                            if (in_array($row['id_fio'], $ill)) {
//                                $yes = 1; //вывод работника
//                            } else {
//                                $yes = 0; //не выводить работника
//                            }
//                        }
//                        /* ---------------- выводить всех работников ------------------------- */ else {
//                            $yes = 1; //вывод работника
//                        }
                    // if ($yes == 1) {//вывод работника
                    if ($row['ch'] == 1) {

                        ?>
                        <tr class="success">
                            <?php
                        } elseif ($row['ch'] == 2) {

                            ?>
                        <tr class="warning">
                            <?php
                        } elseif ($row['ch'] == 3) {

                            ?>
                        <tr class="info">
                            <?php
                        } else {

                            ?>
                        <tr class="danger">
                            <?php
                        }

                        ?>

                        <td><?= $row['fio'] ?><br>
                            <?= (!empty($row['phone'])) ? ('тел: ' . $row['phone']) : '' ?></td>
                        <td><?= $row['rank'] ?></td>
                        <td><?= $row['position'] ?></td>
                        <td><?= $row['divizion'] ?></td>
                        <?php
                        if ($row['ch'] == 0 && $row['is_swing'] == 0) {

                            ?>
                            <td>ежедневник</td>
                            <?php
                        }
                        elseif($row['ch'] == 0 && $row['is_swing'] == 1){
                                                      ?>
                            <td>подменный</td>
                            <?php
                        }else {

                            ?>
                            <td><?= $row['ch'] ?></td>
                            <?php
                        }

                        ?>


                        <?php
                        /* ------------- rcu ------------------- */
                        if ($_SESSION['ulevel'] == 1 && ($_SESSION['is_admin'] == 1 || $_SESSION['can_edit'] == 1)) {

                            ?>
                            <td>
                                <a href="/str/listfio_rcu/edit/<?= $row['id_fio'] ?>"> <button class="btn btn-xs btn-primary " type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a>
                            </td>

                            <td>
                                <?php
                                if (!in_array($row['id_fio'], $not_edit_array)) {

                                    ?>

                                    <a href="/str/listfio_rcu/delete/<?= $row['id_fio'] ?>"> <button class="btn btn-xs btn-primary" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    <?php
                                } else {//удалить нельзя. т.к. заступил как начальник смены

                                    ?>
                                    <i class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Удаление недоступно, т.к. работник заступил начальником смены"></i>
                                    <?php
                                }

                                ?>
                            </td>
                            <?php
                        }

                        ?>


                    </tr>
                    <?php
                    // }
                }

                ?>

            </tbody>
        </table>

        <!--        </div>-->
    </div>
</div>