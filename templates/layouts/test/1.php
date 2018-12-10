<div class="navmenu navmenu-default navmenu-fixed-left offcanvas-sm menu">
    <div class="row" id="brand-menu">
        <div class="col-lg-1">
            <a href="/str/general"><img src="/str/app/images/logo.png" class="navbar-brand" id="imglogo1" data-toggle="tooltip" data-placement="left"></a>
        </div>
        <div class="col-lg-7 col-lg-offset-1">
            <a class="navmenu-brand visible-md visible-lg" href="/str/general" id="logo1"><center>РЎС‚СЂРѕРµРІР°СЏ Р·Р°РїРёСЃРєР° ver 1.0</center></a>
        </div>
    </div>


    <?php
    //print_r($_SESSION['reg']);
    /* print_r($_SESSION['pasp']); */
    // print_r($_SESSION['loc']);
    ?>

    <div class="container" id="container-menu">
      
        <ol class="tree">
            <li class="menu-li">
            <?php
            foreach ($_SESSION['reg'] as $id_region => $name_region) {
                ?>
            <li class="menu-li">  <label for="folder4" id="label-checkbox"><?= $name_region ?></label> <input type="checkbox" id="folder4" class="input-li"/> 

                    <ol>
                        <?php
                        foreach ($_SESSION['loc'][$id_region] as $grochs => $name_grochs) {
                            ?>
                         <li class="menu-li">  <label for="subfolder5" id="label-checkbox"><?= $name_grochs ?></label>  <input type="checkbox" id="subfolder4"  class="input-li"/>
                                <ol class="pasp-ul">
                                    <?php
                                    foreach ($_SESSION['pasp'][$grochs] as $pasp => $name_pasp) {
                                        ?>
                                   
                                    <li class="menu-li"><label for="subfolder5-subfolder1"> <a href="/str/v1/card/<?= $pasp ?>/ch/1/main" class="menu-a-pasp" ><?= $name_pasp ?></a> </label> <input type="checkbox" id="subfolder4" class="input-li"/>
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
                ?>
                </li>


        </ol>
  
    </div>


    <?php
    /* if ($_SESSION['ulevel'] != 1) {//РІСЃРµ РѕР±Р»Р°СЃС‚Рё, РєСЂРѕРјРµ Р Р¦РЈ
      ?>
      <table class="table tree" id="tree-menu-user">
      <?php
      if ($_SESSION['note'] == 8) {//ROSN
      $i = 0;
      ?>
      <tr class="treegrid-<?php echo $i; ?>">
      <td class="success">Р РћРЎРќ </td>
      </tr>
      <?php
      foreach ($_SESSION['menurosn'] as $m => $r) {//С†РµРЅС‚СЂС‹ Р РћРЎРќ(РџРёРЅСЃРє, РЎРѕР»РёРіРѕСЂСЃРє, РњРёРЅСЃРє
      ?>
      <tr class="treegrid-9000 treegrid-parent-<?php echo $i; ?>">
      <td><a href="/str/v1/card/<?= $r['id'] ?>/ch/1/main"><?= $r['name'] ?></a> </td>
      </tr>

      <?php
      }
      ?>
      <?php
      } else {//oblast
      if ($_SESSION['ulevel'] == 2) {//РѕР±Р»Р°СЃС‚СЊ
      foreach ($_SESSION['reg'] as $key => $value) {//РѕР±Р»Р°СЃС‚СЊ
      ?>
      <tr class="treegrid-9292">
      <td class="success"><?= $value ?> </td>
      </tr>

      <?php
      //echo $key . '*' . $value;
      }
      }


      foreach ($_SESSION['pasp'] as $key => $value) {

      foreach ($_SESSION['loc'] as $k => $lo) {
      if ($k == $key) {//СЂР°Р№РѕРЅ
      $y = 0;
      foreach ($value as $ke => $p) {//РїР°СЃРї
      if (!empty($p)) {
      $y++;
      }
      }

      if ($y != 0) {
      if ($_SESSION['ulevel'] == 2) {//РѕР±Р»Р°СЃС‚СЊ
      ?>
      <tr class="treegrid-<?php echo $k; ?> treegrid-parent-9292" >
      <?php
      } else {
      ?>
      <tr class="treegrid-<?php echo $k; ?>" >
      <?php
      }
      ?>

      <td class="warning"><?= $lo ?> </td>
      <?php
      } else {
      if ($_SESSION['ulevel'] == 2) {//РѕР±Р»Р°СЃС‚СЊ
      ?>
      <tr class="treegrid-<?php echo $k; ?> treegrid-parent-9292" >
      <?php
      } else {
      ?>
      <tr class="treegrid-<?php echo $k; ?>" >
      <?php
      }
      ?>

      <td class="warning" id="noactive-user"><?= $lo ?> </td>
      <?php
      }
      ?>

      </tr>

      <?php
      foreach ($value as $ke => $p) {//РїР°СЃРї
      //echo $ke . '*' . $p;
      ?>
      <tr class="treegrid-9293 treegrid-parent-<?php echo $k; ?>">
      <?php
      if (!empty($p)) {
      ?>

      <td> <a href="/str/v1/card/<?= $ke ?>/ch/1/main" ><?= $p ?></a> </td>
      <?php
      }
      ?>

      </tr>
      <?php
      }
      ?>

      <?php
      //echo $key . '*' . $p;
      }
      }
      }
      }
      ?>

      </table>
      <?php
      } else {//Р Р¦РЈ
      ?>
      <table class = "table tree" id = "tree-menu-user">
      <?php
      $i = 9292;
      foreach ($_SESSION['reg'] as $key => $value) {//РѕР±Р»Р°СЃС‚СЊ
      foreach ($_SESSION['level1'] as $kk => $vv) {
      if ($kk == $key) {
      $i++;
      ?>
      <tr class="treegrid-<?php echo $i; ?>">
      <td class="success"><?= $value ?> </td>
      </tr>

      <?php
      foreach ($vv as $a => $s) {
      foreach ($_SESSION['pasp'] as $kp => $vp) {//РїР°СЃРї
      if ($kp == $a) {
      $x = 0;
      foreach ($vp as $l => $p) {
      if (!empty($p)) {
      $x++;
      }
      ?>

      <?php
      }
      if ($x != 0) {//СЂР°Р№РѕРЅ
      ?>
      <tr class="treegrid-<?php echo $a; ?> treegrid-parent-<?php echo $i; ?>">
      <td class="warning"><?= $s ?> </td>
      <?php
      } else {
      ?>
      <tr class="treegrid-<?php echo $a; ?> treegrid-parent-<?php echo $i; ?>" >
      <td class="warning" id="noactive-user"><?= $s ?> </td>
      <?php
      }
      ?>


      </tr>
      <?php
      foreach ($vp as $l => $p) {//РїР°СЃРї
      ?>
      <tr class="treegrid-9000 treegrid-parent-<?php echo $a; ?>">
      <?php
      if (!empty($p)) {
      ?>
      <td><a href="/str/v1/card/<?= $l ?>/ch/1/main"><?= $p ?></a> </td>
      <?php
      }
      ?>

      </tr>
      <?php
      }
      }
      }
      ?>

      <?php
      }
      }
      //echo $key . '*' . $value;
      }
      }
      ;
      $i++;

      if (isset($_SESSION['menurosn']) && !empty($_SESSION['menurosn'])) {//v > v.1.0
      ?>
      <tr class="treegrid-<?php echo $i; ?>">
      <td class="success">Р РћРЎРќ </td>
      </tr>
      <?php
      foreach ($_SESSION['menurosn'] as $m => $r) {
      ?>
      <tr class="treegrid-9000 treegrid-parent-<?php echo $i; ?>">
      <td><a href="/str/v1/card/<?= $r['id'] ?>/ch/1/main"><?= $r['name'] ?></a> </td>
      </tr>

      <?php
      }
      }
      ?>

      </table>
      <?php
      } */
    ?>
</div>
<div class="navbar navbar-default navbar-fixed-top hidden-md hidden-lg" id="navbar-default-user">
    <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navmenu">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a href="/str/general"><img src="/str/app/images/logo.png" class="navbar-brand"  data-toggle="tooltip" data-placement="left" id="logo3"></a>
    <a class="navbar-brand" href="/str/general" id="logo">РЎС‚СЂРѕРµРІР°СЏ Р·Р°РїРёСЃРєР°</a><a class="ver">ver 1.0</a>
</div>

<!--<div class="row content-user">
    55555
</div>-->
<!-- РРЅС„РѕСЂРјР°С†РёСЏ Рѕ РґРµР¶СѓСЂРЅРѕР№ СЃРјРµРЅРµ, РІСЂРµРјСЏ, РґР°С‚Р° -->
<div class="row">

    <div class="col-lg-2" id="container-calendar">

        <!-- <div id="jqueryScriptClock">
             <span class="hours"></span> <b id="cal-date-time">:</b>
             <span class="min"></span> <b id="cal-date-time">:</b>
             <span class="sec"></span>

         </div>
        <p></p>-->

        <?php
        if (isset($inf) && !empty($inf)) {
            foreach ($inf as $in) {
                $c = $in['ch'];
                $name = $in['name'];
                $d = $in['dateduty'];
                $last_update = $in['last_update'];
            }
            ?>
            <center> <p id="cal-date-today"><?= date("d.m.Y") ?><br>СЃРјРµРЅР° <?= $c ?>
                    <i class="fa fa-hand-o-up" id="inf-duty-ch" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Р—Р°РїРѕР»РЅРёР» <?= $name ?> <?= $last_update ?>"></i> </p>

            </center>
            <?php
        } else {
            ?>
            <center> <p id="cal-date-today"><?= date("d.m.Y") ?></p></center>
            <?php
        }
        ?>
    </div>



    <div class="col-lg-10" id="container-content">
        <div class="container" id="chief-container">

            <?php
            if ($_SESSION['note'] == 8) {
                $gr = 'Р РћРЎРќ';
            } else {
                if ($_SESSION['ulevel'] == 1) {
                    $gr = 'Р Р¦РЈР Р§РЎ';
                } else {
                    $obl = array(
                        1 => 'Р‘СЂРµСЃС‚СЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ',
                        2 => 'Р’РёС‚РµР±СЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ',
                        3 => 'Рі.РњРёРЅСЃРє',
                        4 => 'Р“РѕРјРµР»СЊСЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ',
                        5 => 'Р“СЂРѕРґРЅРµРЅСЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ',
                        6 => 'РњРёРЅСЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ',
                        7 => 'РњРѕРіРёР»РµРІСЃРєР°СЏ РѕР±Р»Р°СЃС‚СЊ'
                    );
                    $gr = $obl[$_SESSION['uregions']];
                }
            }
            ?>
            <div class="row">




                <div id="auth" class="col-lg-5 col-md-8 col-xs-11 col-sm-8">



                    <span ><?= $_SESSION['uname'] ?> | <?= $gr ?> <a href="/str/logout"><span class="glyphicon glyphicon-share" data-toggle="tooltip" data-placement="left" title="Р’С‹С…РѕРґ"></span></a></span>


                </div>

            </div>





