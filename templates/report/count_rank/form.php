<?php
//print_r($main);

?>

<br>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
<span style="color: red;">  Для просмотра информации за весь РОСН/УГЗ необходимо выбрать соответствующее подразделение в списке "Область".<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Для просмотра информации по подчиненным РОСН/УГЗ - выбрать в списке "Подразделение" соответствующей области.</span>
<br><br><br>
<form  role="form"  method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

    <div class="row">


        <div class="col-lg-2">
            <div class="form-group">
                <label for="region">Область</label>
                <select class="form-control" name="region" id="region"  >
                    <option value="">Все</option>
                    <?php
                    if (isset($_POST['region']) && !empty($_POST['region'])) {
                        foreach ($region as $re) {
                            if ($_POST['region'] == $re['id']) {
                                printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                            } else {
                                printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                            }
                        }
                    } else {
                        foreach ($region as $re) {
                            printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                        }
                    }

                    ?>
                </select>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label for="locorg">Подразделение</label>
                <select class="form-control" name="locorg" id="locorg" >

                    <option value="" >все</option>
                    <?php
                    if (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
                        foreach ($locorg as $lo) {
                            if ($_POST['locorg'] == $lo['locorg_id']) {
                                printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                            } else {
                                printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                            }
                        }
                    } else {
                        foreach ($locorg as $lo) {
                            printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                        }
                    }

                    ?>
                </select>
            </div>
        </div>


        <div class="col-lg-2">
            <div class="form-group">
                <label for="diviz">Часть</label>
                <select class="form-control" name="diviz" id="diviz" >
                    <option value="" >все</option>
                    <?php
                    if (isset($_POST['diviz']) && !empty($_POST['diviz'])) {
                        foreach ($diviz as $di) {
                            if ($_POST['diviz'] == $di['recid']) {
                                printf("<p><option value='%s' class='%s' selected><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                            } else {
                                printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                            }
                        }

                        ?>

                        <?php
                    } else {

                        foreach ($diviz as $di) {
                            printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                        }
                    }

                    ?>

                </select>
            </div>
        </div>

        <div class="col-lg-4">
                                <div class="form-group">
                                    <label  for="rank_search[]">Звание</label>

                                    <select class="form-control js-example-basic-multiple" name="rank_search[]" multiple tabindex="4" data-placeholder="Все" >
                                        <option></option>
                                        <?php
                                          if (isset($_POST['rank_search']) && !empty($_POST['rank_search'])) {

                                            foreach ($rank as $p) {
                                                if ( in_array($p['id'] , $_POST['rank_search'])  ) {
                                                    printf("<p><option value='%s' selected><label>%s</label></option></p>", $p['id'], $p['name']);
                                                } else {
                                                    printf("<p><option value='%s'><label>%s</label></option></p>", $p['id'], $p['name']);
                                                }
                                            }

                                            ?>

                                            <?php
                                        } else {

                                            foreach ($rank as $p) {
                                                printf("<p><option value='%s'><label>%s</label></option></p>", $p['id'], $p['name']);
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>


    </div>
    <br><br>
    <center>

        <div class="row">

            <div class="form-group">
                <button type="submit" class="btn btn-success" >Вывод на экран</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-info" name="export_to_excel">Экспорт в Excel</button>
            </div>
        </div>



    </center>

</form>


