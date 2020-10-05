<br>

<?php
$is_vacant = 0;
foreach ($list_fio as $row) {

    if (strtolower(trim($row['fio'])) == 'вакант' && $row['is_vacant'] == 0) {
        $is_vacant++;
    }
}

if ($is_vacant > 0) {

    ?>
    <div class="container">
        <div class="alert alert-danger">
            <strong>Внимание!</strong> Необходимо установить отметку
            <div class="checkbox checkbox-danger" style="display: inline">
                <input id="checkbox1" type="checkbox" checked >
                <label for="checkbox1">
                    Вакант (Ф.И.О. не указывать)
                </label>
            </div> для вакантов! <b>В противном случае данные будут не достоверными.</b>

        </div>
    </div>

    <?php
}

?>

<div class="container" id="container-query-result">
    <div class="col-lg-12">

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

                    <!--                        /* ------------- Для РЦУ, области  выводить облатсь и ГРОЧС -------------- */-->


                    <th>ГРОЧС</th>
                    <th>Область</th>


                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Звание</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Смена</th>

                    <th>ГРОЧС</th>
                    <th>Область</th>

                </tr>
            </tfoot>

            <tbody>
                <?php
                foreach ($list_fio as $row) {

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

                        <td><?= $row['fio'] ?>

                            <?php
                            if ((strtolower(trim($row['fio']))) == 'вакант' && $row['is_vacant'] == 0) {

                                ?><br>
                                Нет отметки!!!
                                <br>
                                <?php
                            }

                            if ($row['is_vacant'] == 1 && empty($row['vacant_from_date'])) {

                                ?>
                                <i class="fa fa-calendar-times-o" aria-hidden="true" style="color: red"></i>
                                <br>
                                <?php
                            } elseif ($row['is_vacant'] == 1 && !empty($row['vacant_from_date'])) {

                                $date_d = new DateTime($row['vacant_from_date']);
                                $vacant_from_date = $date_d->Format('d.m.Y');

                                echo '<br><b>с ' . $vacant_from_date . '</b>';
                            }

                            ?>

                            <?= (!empty($row['phone'])) ? ('тел: ' . $row['phone']) : '' ?>
                        </td>
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


                        <td>
                            <?= $row['locorg_name'] ?>
                        </td>
                        <td>
                            <?= $row['region_name'] ?>
                        </td>




                    </tr>
                    <?php
                }

                ?>

            </tbody>
        </table>

        <!--        </div>-->
    </div>
</div>