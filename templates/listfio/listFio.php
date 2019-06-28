<br>
<?php

if($_SESSION['note']==NULL && $_SESSION['is_deny']==0){//только для ГРОЧС, кроме РОСН, УГЗ, Авиации

    ?>

<div class="container">
    <div class="alert alert-danger">
<?php
if(in_array($_SESSION['ulocorg'], locorg_umchs))  {//ЦОУ области сам может открыть себе доступ
    ?>
       <strong>Внимание!</strong> Доступ на редактирование закрыт. Чтобы открыть: Список смен (штат) -> Открыть доступ на ред.<b> После получения права - авторизоваться заново!</b>
        <?php
}
else{
    ?>
        <strong>Внимание!</strong> Доступ на редактирование закрыт. Обращаться в областное управление.<b> После получения права - авторизоваться заново!</b>
       <?php
}
?>

    </div>
</div>

<?php
}

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
        if ($_SESSION['can_edit'] == 1 && ($_SESSION['ulevel'] == 3 || $_SESSION['ulevel'] == 4 || ($_SESSION['ulevel'] == 2 && $_SESSION['note'] != NULL) )) {
            ?>
            <div class="dropdown">
                <a href="/str/listfio/add">   <button class="btn btn-primary" type="button" >
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
                        /* ------------- Для РЦУ, области  выводить облатсь и ГРОЧС -------------- */
                        if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) {
                            ?>
                            <th>ГРОЧС</th>
                            <th>Область</th>
                            <?php
                        }

                        /* ------------- право только у уровня 3, 4 и ур 2(УГЗ,РОСН) ------------------- */
                        if ($_SESSION['can_edit'] == 1 && ($_SESSION['ulevel'] == 3 || $_SESSION['ulevel'] == 4 || ($_SESSION['ulevel'] == 2 && $_SESSION['note'] != NULL) )) {
                            ?>
                            <th>Ред.</th>
                            <th>Уд.</th>
                            <?php
                        }
                        /* ------------- право только у РЦУ админ, область админ, Авиация админ(ур 3) ------------------- */
                        if (($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 || ($_SESSION['ulevel'] == 3 && $_SESSION['note'] == AVIA)) && $_SESSION['is_admin'] == 1) {
                            ?>
                            <th>Закрыть<br>больничный</th>
                             <th>Отозвать из<br>отпуска</th>
                             <th>Отозвать из<br>др.причин</th>
                              <th>Отозв. из<br>ком-ки</th>
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
                        /* ------------- Для РЦУ, области  выводить облатсь и ГРОЧС -------------- */
                        if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) {
                            ?>
                            <th>ГРОЧС</th>
                            <th>Область</th>
                            <?php
                        }
                        /* ------------- право только у уровня 3, 4 и ур 2(УГЗ,РОСН) ------------------- */
                        if ($_SESSION['can_edit'] == 1 && ($_SESSION['ulevel'] == 3 || $_SESSION['ulevel'] == 4 || ($_SESSION['ulevel'] == 2 && $_SESSION['note'] != NULL) )) {
                            ?>
                            <th>Ред.</th>
                            <th>Уд.</th>
                            <?php
                        }

                        /* ------------- право только у РЦУ админ, область админ, Авиация админ(ур 3) ------------------- */
                        if (($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 || ($_SESSION['ulevel'] == 3 && $_SESSION['note'] == AVIA)) && $_SESSION['is_admin'] == 1) {
                            ?>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                                <?= (!empty($row['phone'] ))? ('тел: '.$row['phone']) : '' ?></td>
                            <td><?= $row['rank'] ?></td>
                            <td><?= $row['position'] ?></td>
                            <td><?= $row['divizion'] ?></td>
                            <?php
                            if ($row['ch'] == 0) {
                                ?>
                                <td>ежедневник</td>
                                <?php
                            } else {
                                ?>
                                <td><?= $row['ch'] ?></td>
                                <?php
                            }
                            ?>

                            <?php
                            /* ------------- Для РЦУ, области  выводить облатсь и ГРОЧС -------------- */
                            if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) {
                                ?>
                                <td>
                                    <?= $row['locorg_name'] ?>
                                </td>
                                <td>
                                    <?= $row['region_name'] ?>
                                </td>
                                <?php
                            }

                            /* ------------- право только у уровня 3, 4 и ур 2(УГЗ,РОСН) ------------------- */
                            if ($_SESSION['can_edit'] == 1 && ($_SESSION['ulevel'] == 3 || $_SESSION['ulevel'] == 4 || ($_SESSION['ulevel'] == 2 && $_SESSION['note'] != NULL) )) {
                                ?>
                                <td>
                                    <?php
                                  //  if (!in_array($row['id_fio'], $not_edit_array)) {

                                        ?>
                                        <a href="/str/listfio/edit/<?= $row['id_fio'] ?>"> <button class="btn btn-xs btn-primary " type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a>
                                        <?php
                                  //  } else {//ред нельзя. т.к. заступил как начальник смены
                                        ?>
<!--                                        <i class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Редактирование недоступно, т.к. работник заступил начальником смены"></i>-->
                                        <?php
                                    //}
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!in_array($row['id_fio'], $not_edit_array)) {
                                        ?>
                                        <a href="/str/listfio/delete/<?= $row['id_fio'] ?>"> <button class="btn btn-xs btn-primary" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
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

                            /* ------------- право только у РЦУ админ, область админ, Авиация админ(ур 3) ------------------- */
                            if (($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2 || ($_SESSION['ulevel'] == 3 && $_SESSION['note'] == AVIA)) && $_SESSION['is_admin'] == 1) {
                                ?>
                                <td>
                                    <?php
                                    if (isset($is_ill) && !empty($is_ill)) {
                                        foreach ($is_ill as $i) {
                                            $ill[] = $i['id_fio'];
                                            $id_of_ill[$i['id_fio']] = $i['id']; //массив фио=>ill.id
                                        }
                                    } else {
                                        $ill = array();
                                    }
                                    if (in_array($row['id_fio'], $ill)) {
                                        ?>
                                        <a href="/str/listfio/close_ill/<?= $id_of_ill[$row['id_fio']] ?>">закр.</a>
                                        <a href="/str/listfio/close_ill/<?= $id_of_ill[$row['id_fio']] ?>"> <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button></a>
                                                <?php
                                            }
                                            ?>

                                </td>

<!--                                отозвать из отпуска-->
   <td>
                                    <?php
                                    if (isset($is_hol) && !empty($is_hol)) {
                                        foreach ($is_hol as $i) {
                                            $hol[] = $i['id_fio'];
                                            $id_of_hol[$i['id_fio']] = $i['id']; //массив фио=>ill.id
                                        }
                                    } else {
                                        $hol = array();
                                    }
                                    if (in_array($row['id_fio'], $hol)) {
                                        ?>
                                        <a href="/str/listfio/close_ill/<?= $id_of_hol[$row['id_fio']] ?>">отозв.</a>
                                        <a href="/str/listfio/close_hol/<?= $id_of_hol[$row['id_fio']] ?>"> <button class="btn btn-xs btn-success" type="button"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button></a>
                                                <?php
                                            }
                                            ?>

                                </td>
                                <!--                       END         отозвать из отпуска-->

                                <td>
                                    <?php
                                    if (isset($is_other) && !empty($is_other)) {
                                        foreach ($is_other as $i) {
                                            $o[] = $i['id_fio'];
                                            $id_of_other[$i['id_fio']] = $i['id']; //массив фио=>ill.id
                                        }
                                    } else {
                                        $o = array();
                                    }
                                    if (in_array($row['id_fio'], $o)) {
                                        ?>
                                        <a href="/str/listfio/close_other/<?= $id_of_other[$row['id_fio']] ?>">закр.</a>
                                        <a href="/str/listfio/close_other/<?= $id_of_other[$row['id_fio']] ?>"> <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button></a>
                                                <?php
                                            }
                                            ?>

                                </td>


                                 <td>
                                    <?php
                                    if (isset($is_trip) && !empty($is_trip)) {
                                        foreach ($is_trip as $i) {
                                            $tr[] = $i['id_fio'];
                                            $id_of_trip[$i['id_fio']] = $i['id']; //массив фио=>ill.id
                                        }
                                    } else {
                                        $tr = array();
                                    }
                                    if (in_array($row['id_fio'], $tr)) {
                                        ?>
                                        <a href="/str/listfio/close_trip/<?= $id_of_trip[$row['id_fio']] ?>">закр.</a>
                                        <a href="/str/listfio/close_trip/<?= $id_of_trip[$row['id_fio']] ?>"> <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button></a>
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