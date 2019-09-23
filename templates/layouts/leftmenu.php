<!--<div class="navmenu navmenu-default  offcanvas-sm menu col-lg-3"> leftmenu -->
<?php
if (isset($duty_ch) && !empty($duty_ch)) {
    $duty_ch = $duty_ch;
} else
    $duty_ch = 1;


$id_cp = array(8, 9, 12); //ROSN, EUP, AVIA

?>

<div class="col-sm-3 col-md-2 sidebar offcanvas" id="leftmenu-offcanvas">

    <!--            <div class="row" id="brand-menu">
                    <div class="col-lg-1">
                        <a href="/str/general"><img src="/str/app/images/logo.png" class="navbar-brand" id="imglogo1" data-toggle="tooltip" data-placement="left"></a>
                    </div>
                    <div class="col-lg-7 col-lg-offset-1">
                        <a class="navmenu-brand visible-md visible-lg" href="/str/general" id="logo1"><center>Строевая записка ver 1.0</center></a>
                    </div>
                </div>-->


    <ul class="nav nav-sidebar" >
        <ol class="tree">
            <li class="menu-li">
                <?php
                if ($_SESSION['note'] == 8) {//ROSN авторизован
                    foreach ($_SESSION['menurosn'] as $row) {
                        $name_podr = $row['organ'];
                    }
                    ?>

                <li class="menu-li">  <label for="subfolder5" id="label-checkbox"><?= $name_podr ?></label>  <input type="checkbox" id="subfolder4"  class="input-li" >
                    <ol class="pasp-ul">
                        <?php
                        foreach ($_SESSION['menurosn'] as $myrow) {
                            ?>
                            <li class="menu-li"><label for="subfolder5-subfolder1">

                                    <?php
                                    /* -------------------------- ПАСП выкрашен в другой цвет, если активен ---------------------------------- */

                                    if (isset($pasp_active) && $pasp_active == $myrow['id']) {//ПАСП выкрашен в другой цвет, если активен
                                        ?>

                                        <a href="/str/v1/card/<?= $myrow['id'] ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"  style="color: #f85a03 !important">

                                            <?php
                                        } else {
                                            ?>
                                            <a href="/str/v1/card/<?= $myrow['id'] ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"   >
                                                <?php
                                            }

                                            /* -------------------------- END ПАСП выкрашен в другой цвет, если активен ---------------------------------- */
                                            ?>

                                            <?= $myrow['name'] ?></a> </label> <input type="checkbox" id="subfolder4" class="input-li"/>
                                            <?php
                                        }
                                        ?>
                    </ol>


                    <?php
                } else {
                    if (isset($_SESSION['reg']) && !empty($_SESSION['reg'])) {


                        /*                         * ******************  Меню УМЧС  ********************* */
                        foreach ($_SESSION['reg'] as $id_region => $name_region) {
                            ?>
                <li class="menu-li">  <label for="folder4_<?= $id_region?>"  id="label-checkbox"><?= $name_region ?></label>

                            <?php
                            if (isset($region_active) && $region_active == $id_region && !in_array($organ_active, $id_cp)) {//область развернута, если РОСН выбран - область не разворачивать-развернуть РОСН
                                ?>
                                <input type="checkbox" name="nr<?= $id_region?>" id="folder4_<?= $id_region?>" class="input-li" checked="" />
                                <?php
                            } else {
                                ?>
                                <input type="checkbox" name="nr<?= $id_region?>" id="folder4_<?= $id_region?>" class="input-li" />
                                <?php
                            }
                            ?>


                            <ol class="sub-tree">
                                <?php
                                foreach ($_SESSION['loc'][$id_region] as $grochs => $name_grochs) {
                                    ?>
                                    <li class="menu-li">  <label for="subfolder5_sub_<?= $grochs ?>" id="label-checkbox"><?= $name_grochs ?></label>


                                        <?php
                                        if (isset($grochs_active) && $grochs_active == $grochs) {//ГРОЧС развернут
                                            ?>
                                            <input type="checkbox" id="subfolder5_sub_<?= $grochs ?>"  class="input-li" checked=""/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="checkbox" id="subfolder5_sub_<?= $grochs ?>"  class="input-li"/>
                                            <?php
                                        }
                                        ?>


                                        <ol class="pasp-ul">
                                            <?php
                                            foreach ($_SESSION['pasp'][$grochs] as $pasp => $name_pasp) {
                                                ?>

                                                <li class="menu-li"><label for="subfolder5-subfolder1">

                                                        <?php
                                                        /* -------------------------- ПАСП выкрашен в другой цвет, если активен ---------------------------------- */

                                                        if (isset($pasp_active) && $pasp_active == $pasp) {//ПАСП выкрашен в другой цвет, если активен
                                                            ?>
                                                            <a  href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"   style="color: #f85a03 !important">

                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a  href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"   >
                                                                    <?php
                                                                }
                                                                /* -------------------------- END ПАСП выкрашен в другой цвет, если активен ---------------------------------- */
                                                                ?>

                                                                <?= $name_pasp ?></a>
                                                            </span>
                                                    </label>

                                                    <input type="checkbox" id="subfolder4" class="input-li"/>
                                                    <?php
                                                }
                                                ?>
                                        </ol>
                                        <?php
                                    }
                                    ?>
                            </ol>
                            <?php
                        }
                    }


                    /*                     * ***************** ROSN *************************** */
                    if (isset($_SESSION['menurosn']) && !empty($_SESSION['menurosn'])) {
                        foreach ($_SESSION['menurosn'] as $row) {
                            $name_podr = $row['organ'];
                        }
                        ?>


                    <li class="menu-li">  <label for="subfolder5_<?= $name_podr ?>" id="label-checkbox"><?= $name_podr ?></label>


                        <?php
                        if (isset($organ_active) && $organ_active == 8) {//развернуть РОСН
                            ?>
                            <input type="checkbox" id="subfolder5_<?= $name_podr ?>"  class="input-li" checked=""/>
                            <?php
                        } else {
                            ?>
                            <input type="checkbox" id="subfolder5_<?= $name_podr ?>"  class="input-li"/>
                            <?php
                        }
                        ?>



                        <ol class="pasp-ul">
                            <?php
                            foreach ($_SESSION['menurosn'] as $myrow) {
                                ?>

                                <li class="menu-li"><label for="subfolder5-subfolder1">

                                        <?php
                                        /* -------------------------- ПАСП выкрашен в другой цвет, если активен ---------------------------------- */

                                        if (isset($pasp_active) && $pasp_active == $myrow['id']) {//ПАСП выкрашен в другой цвет, если активен
                                            ?>
                                            <a href="/str/v1/card/<?= $myrow['id'] ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"  style="color: #f85a03 !important">


                                                <?php
                                            } else {
                                                ?>
                                                <a href="/str/v1/card/<?= $myrow['id'] ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp" >
                                                    <?php
                                                }

                                                /* -------------------------- END ПАСП выкрашен в другой цвет, если активен ---------------------------------- */
                                                ?>

                                                <?= $myrow['name'] ?></a> </label> <input type="checkbox" id="subfolder4" class="input-li"/>
                                                <?php
                                            }
                                            ?>
                        </ol>


                        <?php
                    }

                    /*                     * ***************** ЦП без РОСН ************** */
                    if (isset($_SESSION['reg_cp']) && !empty($_SESSION['reg_cp'])) {

                        foreach ($_SESSION['reg_cp'] as $id_region => $name_region) {

                            foreach ($_SESSION['loc_cp'][$id_region] as $grochs => $name_grochs) {
                                ?>
                            <li class="menu-li">  <label for="subfolder5_cp_<?= $grochs ?>" id="label-checkbox"><?= $name_grochs ?></label>



                                <?php
                                if (isset($grochs_active) && $grochs_active == $grochs) {//ГРОЧС развернут
                                    ?>
                                    <input type="checkbox" id="subfolder5_cp_<?= $grochs ?>"  class="input-li" checked=""/>
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" id="subfolder5_cp_<?= $grochs ?>"  class="input-li"/>
                                    <?php
                                }
                                ?>



                                <ol class="pasp-ul">
                                    <?php
                                    foreach ($_SESSION['pasp_cp'][$grochs] as $pasp => $name_pasp) {
                                        ?>
                                        <li class="menu-li"><label for="subfolder5-subfolder1">


                                                <?php
                                                /* -------------------------- ПАСП выкрашен в другой цвет, если активен ---------------------------------- */

                                                if (isset($pasp_active) && $pasp_active == $pasp) {//ПАСП выкрашен в другой цвет, если активен
                                                    ?>
                                                    <a href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"  style="color: #f85a03 !important">

                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp"  >
                                                            <?php
                                                        }

                                                        /* -------------------------- END ПАСП выкрашен в другой цвет, если активен ---------------------------------- */
                                                        ?>

                                                        <?= $name_pasp ?></a> </label> <input type="checkbox" id="subfolder4" class="input-li"/>
                                                        <?php
                                                    }
                                                    ?>
                                </ol>

                                <?php
                            }
                        }
                    }
                }
                ?>
            </li>


        </ol>

    </ul>


</div>
<!--        <div class="navbar navbar-default navbar-fixed-top hidden-md hidden-lg" id="navbar-default-user">
            <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/str/general"><img src="/str/app/images/logo.png" class="navbar-brand"  data-toggle="tooltip" data-placement="left" id="logo3"></a>
            <a class="navbar-brand" href="/str/general" id="logo">Строевая записка</a><a class="ver">ver 1.0</a>
        </div>-->