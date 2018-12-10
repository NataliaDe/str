
<form class="form-inline" role="form" id="formFillIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/ill/save">
    <?php
     if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
        ?>
        <fieldset disabled>
            <?php
        }
        ?>
<!--        <div class="table-responsive" id="conttabl">-->
            <br><br><br>
            <table class="table table-condensed   table-bordered tbl_show_inf" >
                <!-- строка 1 -->
                <thead>
                    <tr >
                        <th>№п/п</th>
                        <th >Ф.И.О</th>
                        <th >Дата открытия</th>
                        <th>Дата закрытия</th>
                        <th >Вид травмы</th>
                        <th>Предварительный<br>диагноз</th>
<!--                        <th>Снять с учета</th>-->
                        <th>Удалить</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($ill as $row) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i ?></td>

                            <td>
                                <div class="form-group">
                             <?php
                                echo $row['fio'].',<br>'.'<i>'.$row['position_name'].'</i>'.',<br>'.'<i>'.$row['rank_name'].'</i>';
                             ?>
                                </div>
                            </td>
                            <td>
                                <div class="input-group date" id="date1<?= $i ?>">
                                    <?php
                                    if ($row['date1'] == '00.00.0000') {
                                        ?>
                                        <input type="text" class="form-control" name="date1<?= $i ?>" style="width: auto;"/>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" name="date1<?= $i ?>" value="<?= $row['date1'] ?>" style="width: auto;"/>
                                        <?php
                                    }
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <div class="input-group date" id="date2<?= $i ?>">
                                        <?php
                                        if ($row['date2'] == '00.00.0000') {
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" style="width: auto;"/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" value="<?= $row['date2'] ?>" style="width: auto;"/>
                                            <?php
                                        }
                                        ?>

                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>

                                <div class="form-group">
                                    <select class="form-control" name="maim<?= $i ?>">

                                        <?php
                                        foreach ($maim as $ma) {
                                            if ($ma['id'] == $row['maim']) {
                                                printf("<p><option value='%s' selected ><label>%s</label></option></p>", $ma['id'], $ma['name']);
                                            } else {
                                                printf("<p><option value='%s' ><label>%s</label></option></p>", $ma['id'], $ma['name']);
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea cols="25" rows="6" class="form-control" placeholder="Диагноз"  id="diagnosis<?= $i ?>" name="diagnosis<?= $i ?>"><?= $row['diagnosis'] ?></textarea>
                                </div>
                            </td>

<!--                            <td>-->
                                <!-- <div class="btn-group" data-toggle="buttons">-->

                                <?php
                                // if ($row['deregister'] == 1) {
                                ?>
                                <!--   <div class="radio radio-danger">
                                       <input type="radio" name="deregister<? $i ?>" checked="checked"  id="radio5" value="1">
                                       <label for="radio5">
                                           Да
                                       </label>
                                   </div>

                                   <div class="radio radio-danger">
                                       <input type="radio" name="deregister<?$i ?>"   id="radio5" value="0">
                                       <label for="radio5">
                                           Нет
                                       </label>
                                   </div>-->


                                <?php
                                // } else {//если 0, не снят с учета
                                ?>


<!--  снятие с учета       
<div class="radio radio-danger">
                                    <input type="radio" name="deregister<?= $i ?>"   id="radio<?= $i ?>" value="1">
                                    <label for="radio<?= $i ?>">
                                        Да
                                    </label>
                                </div>

                                <div class="radio radio-danger">
                                    <input type="radio" name="deregister<?= $i ?>"   id="radio<?= $i ?>" value="0" checked="checked">
                                    <label for="radio<?= $i ?>">
                                        Нет
                                    </label>
                                </div>-->
                                <?php
                                // }
                                ?>
                                <!--</div>
                            </td>-->

                            <td>
                                <?php
                                //удалить можно только что добавленного работника, а работника добавленного несколько дней назад-нет
                                if ($row['date_insert'] == date("Y-m-d")) {
                                    ?>
                                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/ill/<?= $row['id'] ?>"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Удалить"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></a>
                                    <?php
                                }
                                ?>


                            </td>


                        </tr>
                    <input type="hidden" class="form-control"   id="idill<?= $i ?>" name="idill<?= $i ?>" value="<?= $row['id'] ?>">
                    <?php
                }
                ?>

                </tbody>
            </table>
<!--        </div>-->

        <input type="hidden" class="form-control"   id="countill" name="countill" value="<?= $i ?>">
        <input type="hidden" name="_METHOD" value="PUT"/>

       
        
           <center>
        <div class="row">
            <div class="form-group">
                 <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </center>

        <?php
      if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
            ?>
        </fieldset>
        <?php
    }
    ?>
</form>