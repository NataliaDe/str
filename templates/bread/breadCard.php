<?php
if (isset($duty_ch) && !empty($duty_ch)) {
    $duty_ch = $duty_ch;
} else
    $duty_ch = 1;

?>

<div class="bread noprint">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li id="page">Строевая записка</li>
            <?php
            if (isset($bread_rosn) && !empty($bread_rosn)) {
                foreach ($bread_rosn as $value) {

                    ?>
                    <li id="page"><?= $value['organ'] ?> </li>
                    <li id="active"><?= $value['name'] ?> </li>
                    <?php
                }

                ?>


                <?php
            } elseif (isset($bread_cp) && $bread_cp == 1) {

                ?>
                <li id="page"><?= $_SESSION['reg_cp'][$region] ?> </li>

                <li class="dropdown-submenu" id="page">
                    <a tabindex="-1" href="#"><?= $_SESSION['loc_cp'][$region][$id_grochs] ?></a>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($_SESSION['pasp_cp'][$id_grochs] as $pasp => $name_pasp) {

                            ?>
                            <li class="page"><a href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp" ><?= $name_pasp ?></a> </li>
                            <?php
                        }

                        ?>
                    </ul>
                </li>

                <li id="active"><?= $_SESSION['pasp_cp'][$id_grochs][$record_id] ?> </li>

                <?php
            } elseif ($organ_active == 5) {//rcu
?>
                <li id="active">РЦУРЧС</li>
                <?php
                ?>

                <?php
            } else {

                ?>

                <li id="page"><?= $_SESSION['reg'][$region] ?> </li>

                <li class="dropdown-submenu" id="page">
                    <a tabindex="-1" href="#"><?= $_SESSION['loc'][$region][$id_grochs] ?></a>
                    <ul class="dropdown-menu">
    <?php
    foreach ($_SESSION['pasp'][$id_grochs] as $pasp => $name_pasp) {

        ?>
                            <li class="page"><a href="/str/v1/card/<?= $pasp ?>/ch/<?= $duty_ch ?>/main" class="menu-a-pasp" ><?= $name_pasp ?></a> </li>
                            <?php
                        }

                        ?>
                    </ul>
                </li>

                <li id="active"><?= $_SESSION['pasp'][$id_grochs][$record_id] ?> </li>
                <?php
            }

            ?>


        </ol>
    </div>
</div>

<?php
if (isset($inf) && !empty($inf)) {

    ?>

    <div class="container" id="inf-duty-ch-2">
        <div class="alert alert-success" id="inf-duty-ch-msg">


            <?php
            foreach ($inf as $in) {
                $c = $in['ch'];
                $name = $in['name'];
                $d = date('d.m.Y', strtotime($in['dateduty']));
                $last_update = date('d.m.Y H:i', strtotime($in['last_update']));
                $is_open_update = $in['open_update'];
            }

            ?>
            <strong><b style="color:red;"><u><?= $d ?> дежурная смена -  <?= $c ?>.</u></b></strong>&nbsp;Информацию внес <?= $name ?> <?= $last_update ?>
            <?php
            if ($is_open_update == 1) {

                ?>
                <p class="warning-msg-animate"> Открыт доступ на редактирование!</p>


        <?php
    }

    ?>
        </div>
    </div>


    <?php
} else {

    ?>
    <script>
    //    var t=["red", "green", "blue"];
    //var timerId = setInterval(function() {
    //
    //       $("#danger-msg-animate").css("background-color", t[1]);
    //
    //
    //   // $("#danger-msg-animate").css("background-color", "green");
    //}, 2000);
    //var timerId2 = setInterval(function() {
    //
    //       $("#danger-msg-animate").css("background-color", t[2]);
    //
    //
    //   // $("#danger-msg-animate").css("background-color", "green");
    //}, 2000);

    //  $(function changeColor (curNumber) {
    //
    //    curNumber++;
    //    if(curNumber > 2){
    //        curNumber = 0;
    //    }
    //   // document.getElementById("danger-msg-animate").style.backgroundColor = "lightblue";
    //    //document.body.setAttribute('danger-msg-animate', 'color' + curNumber);
    //     $("#danger-msg-animate").css("background-color", t[curNumber]);
    //    setTimeout(function(){changeColor(curNumber);}, 2000);
    //});
    //changeColor(0);
    </script>

    <div class="container" id="inf-duty-ch-2">
        <div class="warning-msg-animate" >
            <strong><u>Внимание!</u></strong>Сегодня смена не заступила на дежурство
        </div>
        <?php
        if (isset($is_btn_confirm) && $is_btn_confirm == 1) {
            echo '<br>';
            require realpath(dirname(__FILE__) . '/../') . '/card/sheet/confirm/btnconfirm.php';
        }

        ?>
    </div>

    <?php
}

?>







