<br>
<center><b><u>Результат запроса</u><br>
        <?php
//        foreach ($head as $value) {
//            echo $value.', ';
//        }

        echo implode(', ', $head);
        echo '<br>';
        if(!empty($head_pos))
        echo 'Должности: '.implode(', ', $head_pos);
//                foreach ($head_pos as $value) {
//            echo $value.', ';
//        }
        ?>
    </b></center>
<br>
<?php
$all = 0;
$k = 0;


if (isset($res) && !empty($res)) {

    ?>
<center>
    <table class="table table-condensed   table-bordered tbl_show_inf" id="tbl_report_position_by_fio" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <th >№ п/п</th>
                <th >Должность</th>
                <th>ФИО</th>
                <th>Область</th>
                <th>Г(Р)ОЧС</th>
                <th>Часть</th>
            </tr>

        </thead>

        <tfoot>
            <tr >
                <th >№ п/п</th>
                <th >Должность</th>
                <th>ФИО</th>
                <th>Область</th>
                <th>Г(Р)ОЧС</th>
                <th>Часть</th>
            </tr>

        </tfoot>

        <tbody>
            <?php
            // print_r($main);

            foreach ($res as $row) {
                $k++;

                ?>
                <tr>
                    <td><?= $k ?></td>
                    <td><?= $row['position'] ?></td>
                    <td><?= $row['fio'] ?></td>
                    <td><?= $row['region_name'] ?></td>
                    <td><?= $row['locorg_name'] ?></td>
                    <td><?= $row['divizion'] ?></td>
                </tr>
                <?php
            }

            ?>

    </tbody>
    </table>
    </center>
    <br>
    <?php ?>

    <!--    </div>-->
    <?php
} else {

    ?>
    <div class="container">
        <div class="alert alert-danger">

            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Внимание!</strong> Нет данных для отображения
        </div>
    </div>
    <?php
}

?>


