
<form class="form-inline" role="form" id="formCountTrip" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/trip">
     <p  style="color:red;" > <strong>Внимание! Отметка "Согласовано с ЦОСМР" ставится только при участии в спортивных мероприятиях!</strong></p>
    <?php
      if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
        ?>
        <fieldset disabled>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="counttrip">&nbsp;&nbsp;&nbsp;Количество работников в командировке</label>

            <?php
            if ($tooltip == 1) {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников в командировке"  id="counttrip" name="counttrip"   data-toggle="tooltip" data-placement="bottom" title="Без учета представленных в таблице" >
                <?php
            } else {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников в командировке"  id="counttrip" name="counttrip"   >
                <?php
            }
            ?>

        </div>
        <button class="btn btn-success " type="submit">OK</button>


        <?php
        if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
            ?>
        </fieldset>
        <?php
    }
    ?>


</form>







