<?php
//print_r($resultill);

?>

        <!--  <u><b>Запрашиваемые данные:</b></u><br>-->
        <?php
//print_r($query);

        /* foreach ($query as $key => $val) {//выбранная область либо подразд ЦП
          echo '<b>' . $key . '</b>' . $val . '<br>';
          } */

        if ($type == 1) {
            ?>
            <br>
            <a name='result_page'></a>
            <center><b>РЕЗУЛЬТАТ запроса на <?= date("d-m-Y"); ?><br>
                    <?php
                    if (isset($date_start)) {
                        echo 'c ' . $date_start;
                    }
                    if (isset($date_end)) {
                        echo ' по ' . $date_end;
                    }
                    ?>
                </b></center>
            <?php
        }

        if (isset($ill_view) && !empty($ill_view)) {//столбцы по больным для вывода (для дальнейшего обЪединения столбца 'Больничные')
            $count = array_count_values($ill_view);
            if (in_array(1, $ill_view)) {
                $coll_ill = $count[1]; //количетсво столбцов для вывода по больным
            } else
                $coll_ill = 0;
        }
        $bol[][] = array();
        $all_count_ill = 0; //итого больных
        if (isset($resultill) && !empty($resultill)) {//подсчет кол-ва больных конкретной карточки конкретной смены
            foreach ($result as $row) {//основные данные
                //$bol[$row['record_id']][$row['ch']] = 0;
                $bol[$row['id_cardch']][$row['id_main']] = 0; //определяет смену карточки и ее дату дежурства...у одной смены карточки может быть много дат заступления(если будем хранить инф за месяц)

                foreach ($resultill as $key_ill=> $ill) {//больничные
                    if (($ill['id_cardch'] == $row['id_cardch'])&&(date("Y-m-d", strtotime($row['dateduty'])) >= date("Y-m-d", strtotime( $ill['date1']))) && (date("Y-m-d", strtotime($row['dateduty'])) <= date("Y-m-d", strtotime($ill['date2'])) )) {//больной конкретной карточки конкретной смены в конкретную dateduty
                        //$bol[$row['record_id']][$row['ch']] +=1;
                        //выбрать больных, которые на момент dateduty ch card были больными

                            $bol[$row['id_cardch']][$row['id_main']] ++;

                            $all_count_ill+=1;

                    }
                }
            }
        } else {
            //$bol = 0;
            foreach ($result as $row) {//основные данные
                //$bol[$row['record_id']][$row['ch']] = 0;
                $bol[$row['id_cardch']][$row['id_main']] = 0; //определяет смену карточки и ее дату дежурства...у одной смены карточки может быть много дат заступления(если будем хранить инф за месяц)
            }
        }
        ?>
        <div class="table-responsive" id="tbl-query-result">
            <table class="table table-condensed   table-bordered" id="tblcapt">
                <!--   строка 1 -->
                <thead>
                    <tr >
                        <?php
                        if ($type == 1) {//Заголовок 1
                            ?>
                            <th rowspan="2">Область</th>
                            <th rowspan="2">Г(Р)ОЧС</th>
                            <th rowspan="2">Подразделение</th>
                            <?php
                        } else {//Заголовок2
                            ?>

                            <th rowspan="2">Подразделение</th>
                            <?php
                        }
                        ?>

                        <th colspan="3">Смена</th>
                        <?php
                        /* Какие столбцы по лс надо вывести  */

                        if ($ls_view['c_ls'] == 1) {
                            ?>
                            <th rowspan="2">По штату</th>
                            <?php
                        }
                        if ($ls_view['listcount'] == 1) {
                            ?>
                            <th rowspan="2">По списку</th>
                            <?php
                        }
                        if ($ls_view['vacant'] == 1) {
                            ?>
                            <th rowspan="2">Вакант</th>
                            <?php
                        }
                        if ($ls_view['face'] == 1) {
                            ?>
                            <th rowspan="2">Налицо</th>
                            <?php
                        }
                        if ($ls_view['calc'] == 1) {
                            ?>
                            <th rowspan="2">Боевой<br>расчет</th>
                            <?php
                        }
                        if ($ls_view['gas'] == 1) {
                            ?>
                            <th rowspan="2">Газо-<br>дымо-<br>защит-<br>ники</th>
                            <?php
                        }
                        if ($ls_view['duty'] == 1) {
                            ?>
                            <th rowspan="2">Наряд</th>
                            <?php
                        }
                       /* if ($ls_view['countdisp'] == 1) {
                            ?>
                            <th rowspan="2">Коли-<br>чество<br>радио-<br>теле-<br>фонистов</th>
                            <?php
                        }*/
                        if ($ls_view['fiodisp'] == 1) {
                            ?>
                            <th rowspan="2">Ф.И.О радиотеле-<br>фонистов</th>
                            <?php
                        }
                        /* конец */
                        if ($coll_ill != 0) {
                            ?>
                            <th colspan="<?= $coll_ill ?>">Больничные</th>
                            <?php
                        }
                        ?>
                    </tr>
                    <!-- строка 2 -->
                    <tr>
                        <th>Ф.И.О.<br>начальника</th>
                        <th>№</th>
                        <th>Дата<br>заступления</th>

                        <?php
                        if ($ill_view['c_ill'] == 1) {
                            ?>
                            <th>Коли-<br>чество</th>
                            <?php
                        }

                        if ($ill_view['fioill'] == 1) {
                            ?>
                            <th>Ф.И.О.</th>
                            <?php
                        }
                        if ($ill_view['maim'] == 1) {
                            ?>
                            <th>Вид<br>травмы</th>
                            <?php
                        }

                        if ($ill_view['diagnosis'] == 1) {
                            ?>
                            <th>Предвари-<br>тельный<br>диагноз</th>
                            <?php
                        }

                        if ($ill_view['dateill'] == 1) {
                            ?>
                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Дата&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <?php
                        }
                        ?>

                    </tr>

                </thead>

                <tbody>
                    <?php
                    foreach ($result as $row) {
                        //$recid = $row['record_id'];
                        //$chnum = $row['ch'];
                        $id_cardch = $row['id_cardch'];
                        $id_main = $row['id_main'];
                        ?>
                        <tr class="success">
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['region'] ?></td>
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['locorg'] ?></td>
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['divizion_name'] ?></td>
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['fio_ch'] ?></td>
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['ch'] ?></td>
                            <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['dateduty'] ?></td>
                           <!-- <td rowspan="<? $bol[$id_cardch][$id_main] ?>"><? $row['is_duty_name'] ?></td>-->

                            <?php
                            /* Какие столбцы по лс надо вывести  */
                            if ($ls_view['c_ls'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['countls'] ?></td>
                                <?php
                            }
                            if ($ls_view['listcount'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['listcount'] ?></td>
                                <?php
                            }
                            if ($ls_view['vacant'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['vacant'] ?></td>
                                <?php
                            }
                            if ($ls_view['face'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['face'] ?></td>
                                <?php
                            }
                            if ($ls_view['calc'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['calc'] ?></td>
                                <?php
                            }
                            if ($ls_view['gas'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['gas'] ?></td>
                                <?php
                            }
                            if ($ls_view['duty'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['duty'] ?></td>
                                <?php
                            }
                          /*  if ($ls_view['countdisp'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['countdisp'] ?></td>
                                <?php
                            }*/
                            if ($ls_view['fiodisp'] == 1) {
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $row['fiodisp'] ?></td>
                                <?php
                            }

                            if ($ill_view['c_ill'] == 1) {//если надо вывести значение кол-ва больных
                                ?>
                                <td rowspan="<?= $bol[$id_cardch][$id_main] ?>"><?= $bol[$id_cardch][$id_main] ?></td>
                                <?php
                            }

                            /* больничные */

                            if (isset($resultill) && !empty($resultill)) {
                                if ($bol[$id_cardch][$id_main] != 0) {//кол-во больных не=0, первый больной отображается в первой строке
                                    $i = 0;
                                    foreach ($resultill as $key_ill=> $ill) {
   //выбрать больных, которые на момент dateduty ch card были больными
                 
                                        if (($ill['id_cardch'] == $row['id_cardch'])&&(date("Y-m-d", strtotime($row['dateduty'])) >= date("Y-m-d", strtotime( $ill['date1']))) && (date("Y-m-d", strtotime($row['dateduty'])) <= date("Y-m-d", strtotime($ill['date2'])) )) {//больной конкретной карточки конкретной смены в конкретную dateduty
                                            $i++;
                                            if ($i == 1) {
                                                if ($ill_view['fioill'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['fio'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['maim'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['m_name'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['diagnosis'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['diagnosis'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['dateill'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td>с <?= $ill['date1'] ?><br>по <?= $ill['date2'] ?></td>
                                                    <?php
                                                }
                                            }
                                           unset($resultill[$key_ill]);
                                            break;
                                        }
                                    }
                                } else {//нечего вывести-больных нет в БД
                                    if ($ill_view['fioill'] == 1) {//если надо вывести значение
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                    if ($ill_view['maim'] == 1) {//если надо вывести значение
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                    if ($ill_view['diagnosis'] == 1) {//если надо вывести значение
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                    if ($ill_view['dateill'] == 1) {//если надо вывести значение
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                }
                            } else {//больных нет
                                if ($ill_view['fioill'] == 1) {//если надо вывести значение
                                    ?>
                                    <td></td>
                                    <?php
                                }
                                if ($ill_view['maim'] == 1) {//если надо вывести значение
                                    ?>
                                    <td></td>
                                    <?php
                                }
                                if ($ill_view['diagnosis'] == 1) {//если надо вывести значение
                                    ?>
                                    <td></td>
                                    <?php
                                }
                                if ($ill_view['dateill'] == 1) {//если надо вывести значение
                                    ?>
                                    <td></td>
                                    <?php
                                }
                            }
                            /* Конец больничные */
                            ?>
                        </tr>
                        <?php
                        if ($bol[$id_cardch][$id_main] > 1) {//если больных больше чем 1, выводим последующих больных

                            /*  последующие больничные */
                            if (isset($resultill) && !empty($resultill)) {

                                $i = 0;
                                foreach ($resultill as $ill) {

                                 if (($ill['id_cardch'] == $row['id_cardch'])&&(date("Y-m-d", strtotime($row['dateduty'])) >= date("Y-m-d", strtotime( $ill['date1']))) && (date("Y-m-d", strtotime($row['dateduty'])) <= date("Y-m-d", strtotime($ill['date2'])) )) {//больной конкретной карточки конкретной смены в конкретную dateduty
                                        $i++;
                                       // if ($i != 1) {//вывод каждого больног с новой строки в перделах одной смены карточки
                                            ?>
                                            <tr class="success">
                                                <?php
                                                if ($ill_view['fioill'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['fio'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['maim'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['m_name'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['diagnosis'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td><?= $ill['diagnosis'] ?></td>
                                                    <?php
                                                }
                                                if ($ill_view['dateill'] == 1) {//если надо вывести значение
                                                    ?>
                                                    <td>с <?= $ill['date1'] ?><br>по <?= $ill['date2'] ?></td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>

                                            <?php
                                        //}
                                    }
                                }
                            }
                            /* Конец последующие больничные */
                        }
                    }
                    ?>

                </tbody>
            </table>
            <?php
            if ($coll_ill != 0) {
                ?>
                <i><b>ИТОГО на больничном: <?= $all_count_ill ?></b></i>
                <?php
            }
            ?>
        </div>







