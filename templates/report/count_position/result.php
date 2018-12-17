<br>
<center><b><u>Результат запроса</u><br>
        <?php
//        foreach ($head as $value) {
//            echo $value.', ';
//        }

        echo implode(',', $head);
        echo '<br>';
        if(!empty($head_pos))
        echo 'Должности: '.implode(',', $head_pos);
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
    <table class="table table-condensed   table-bordered tbl_show_inf" id="tbl_count_position" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <th >№ п/п</th>
                <th >Должность</th>
                <th>Количество</th>
            </tr>

        </thead>

        <tfoot>
            <tr >
                <th >№ п/п</th>
                <th >Должность</th>
                <th>Количество</th>
            </tr>

        </tfoot>

        <tbody>
            <?php
            // print_r($main);

            foreach ($res as $row) {
                $k++;
                $all += $row['cnt'];

                ?>
                <tr>
                    <td><?= $k ?></td>
                    <td><?= $row['name_pos'] ?></td>
                    <td><?= $row['cnt'] ?></td>
                </tr>
                <?php
            }

            ?>
        <td>ИТОГО</td>
        <td></td>
        <td><?= $all ?></td>
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


