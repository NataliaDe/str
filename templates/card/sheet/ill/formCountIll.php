<form class="form-inline" role="form" id="formCountIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/ill">
    <?php
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {        ?>
        <fieldset disabled>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="countill">&nbsp;&nbsp;&nbsp;Количество работников на больничном</label>

            <?php
            if ($tooltip == 1) {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников на больничном"  id="countill" name="countill" data-toggle="tooltip" data-placement="bottom" title="Без учета представленных в таблице" >
                <?php
            } else {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников на больничном"  id="countill" name="countill" >
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



