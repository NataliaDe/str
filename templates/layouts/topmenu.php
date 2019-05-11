<?php
$locorg_umchs=array(1=>145,2=>146,4=>147,5=>148,3=>149,7=>150,6=>151);//oumchs id. declare else in index.php !!!!!

   /* spectator can see all republic, can_edit NO. eye - sign of auth spectator */
            if ($_SESSION['login'] == 'spectator') {
                ?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background-color: #add8fd" >
<?php
            }
            else{
                ?>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" >
    <?php
            }
              //print_r($_SESSION);
?>


    <div class="container-fluid">
        <div class="navbar-header">
            <!--            topmenu-->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#topmenu-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!--         leftmenu-->
            <button type="button" class="navbar-toggle leftmenu-toggle" data-toggle="offcanvas" data-target="#leftmenu-offcanvas">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


            <img src="/str/app/images/man.png" onclick="hideLeftmenu();" class="navbar-brand" id="imglogo1" data-toggle="tooltip" data-placement="left"><a href="/str/general/1" id="logo1">Строевая записка ver 3.0 <!-- <span id="test-mode">опытная эксплуатация</span>--></a>

            <?php
            /* spectator can see all republic, can_edit NO. eye - sign of auth spectator */
            if ($_SESSION['login'] == 'spectator') {

                ?>

                <a href="#" id="logo1" ><i class="fa fa-eye" aria-hidden='true' style="color: blue; font-size: 2.333333em;"></i></a>
                    <?php
                }

                ?>


        </div>
        <div class="navbar-collapse collapse " style="height: auto;" id="topmenu-collapse">

            <ul class="nav navbar-nav navbar-right">

<!--                search by fio-->
 <li class="<?php echo (isset($convex_item['search_by_fio'])) ? 'convex' : ''?>"><a href="/str/search_by_fio" class="item-menu">

            <span class="fa-stack " aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Поиск работника по ФИО" target="_blank">
  <i class="fa fa-user fa-stack-1x"></i>
  <i class="fa fa-search fa-stack-2x text-success"></i>
</span>

<!--         <span><i class="fa fa-search" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Поиск" target="_blank"></i></span>-->

     </a></li>




                <?php

                /* any user can auth as spectator */
                if (isset($_SESSION['id_user_spectator']) && !empty($_SESSION['id_user_spectator']) && $_SESSION['login'] != 'spectator' && $_SESSION['psw'] != 'spectator') {
  if(in_array($_SESSION['ulocorg'], $locorg_umchs)){

   }
   else{
       ?>
             <li ><a href="/str/login_as_spectator/<?= $_SESSION['id_user_spectator'] ?>" class="item-menu"><span>Обозреватель</span></a></li>
                <?php
   }
                    ?>

                    <?php
                }
                /* user authorized as spectator, can return  */ elseif ($_SESSION['login'] == 'spectator' && $_SESSION['psw'] == 'spectator') {

                    ?>

                    <li style="background-color:#f0f168"><a href="/str/login_as_spectator/<?= $_SESSION['past_user'] ?>" class="item-menu"><span>Обозреватель</span></a></li>

                    <?php
                }


                /* umchs level=oblast, admin can auth as cou of region */
                if($_SESSION['ulevel']==2 && $_SESSION['note']==NULL && $_SESSION['is_admin']==1 ){

                    if(isset($_SESSION['id_user_region_cou']) && !empty($_SESSION['id_user_region_cou'])){
                            ?>
                 <li ><a href="/str/login_as_cou/<?= $_SESSION['id_user_region_cou'] ?>" class="item-menu"><span>ЦОУ</span></a></li>
                 <?php
                    }

                }
                /* umchs level=oblast authorized as cou of region, can return  */
                elseif(in_array($_SESSION['ulocorg'], $locorg_umchs)){
                    ?>

 <li style="background-color:#f0f168"><a href="/str/login_as_cou/<?= $_SESSION['past_user'] ?>" class="item-menu"><span>ЦОУ</span></a></li>

<?php
}



                if ((isset($sign)) && ($sign == 5)) {// отчеты отображаем только на стр main
                    ?>
<!--                    <li><a href="/str/v1/report/spr_info/grochs/<?= $id_grochs ?>/<?= $pasp_active ?>" >Строевая</a></li>-->
                    <?php
                }
                ?>

                <!-------------------------  Отчеты общ отображать всегда ------------------------>
                <?php
                //   if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) {
                ?>
                <li class="dropdown <?php echo (isset($convex_item['report'])) ? 'convex' : ''?>">
                    <a href="#" class="dropdown-toggle navbar-right-customer" data-toggle="dropdown" >Отчеты (общ) <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        // if ($_SESSION['ulevel'] == 1) {
                        ?>

                          <li class="dropdown-submenu <?php echo (isset($convex_item['big_report_teh2_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/big_report_teh2" class="caret-spr_inf" target="_blank">Техника (руководство)</a>
                        </li>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['big_report_teh_sub'])) ? 'active-li-dropdown-submenu' : ''?>" >
                            <a tabindex="-1" href="/str/v1/report/big_report_teh" class="caret-spr_inf" target="_blank">Техника (общий)</a>
                        </li>
                        <?php
                        // }
                        ?>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['teh_in_trip_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/teh_in_trip" class="caret-spr_inf" target="_blank">Техника в командировке</a>
                        </li>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['teh_br_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/teh_br" class="caret-spr_inf" target="_blank">Техника (б.р.)</a>
                        </li>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['teh_repaire_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/teh_repaire" class="caret-spr_inf" target="_blank">Неисправности техники</a>
                        </li>

                            <li class="dropdown-submenu <?php echo (isset($convex_item['min_br_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/min_br" class="caret-spr_inf" target="_blank">Мин.боевой расчет</a>
                        </li>



                                                    <li class="dropdown-submenu <?php echo (isset($convex_item['detail_teh_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/detail_teh" class="caret-spr_inf" target="_blank">Техника+Склад(Могилев, ГРОЧС)</a>
                        </li>

                                                                            <li class="dropdown-submenu <?php echo (isset($convex_item['detail_teh_region_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/detail_teh/region" class="caret-spr_inf" target="_blank">Техника+Склад(Могилев, область)</a>
                        </li>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['count_position_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/count_position" class="caret-spr_inf" target="_blank">Отчет по должностям</a>
                        </li>

                        <li class="dropdown-submenu <?php echo (isset($convex_item['sz_spec_donos'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/sz_spec_donos" class="caret-spr_inf" target="_blank">СЗ в спецдонесение</a>
                        </li>


                        <li class="dropdown-submenu <?php echo (isset($convex_item['count_rank_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                            <a tabindex="-1" href="/str/v1/report/count_rank" class="caret-spr_inf" target="_blank">Отчет по званиям</a>
                        </li>

                    </ul>
                </li>
                <?php
                // }
                ?>
                <!-------------------------  КОНЕЦ Отчеты общ отображать всегда ------------------------>


                <!-- отображать на каждой странице-->
                <li class="<?php echo (isset($convex_item['query'])) ? 'convex' : ''?>"><a href="/str" target="_blank">Запросы</a></li>

                <!-------------------------  Больничныеи отпуска ------------------------>
                <?php

                if ($_SESSION['ulevel'] == 1 && $_SESSION['is_admin'] == 1) {//РЦУ admin
                    ?>
                    <li class="dropdown <?php echo (isset($convex_item['listfio'])) ? 'convex' : ''?>">
                        <a href="#" class="dropdown-toggle navbar-right-customer" data-toggle="dropdown" >Больничные и отпуска <b class="caret"></b></a>
                        <ul class="dropdown-menu">

                            <li class="dropdown-submenu <?php echo (isset($convex_item['close_ill_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                <a tabindex="-1" href="/str/listfio/ill" class="caret-spr_inf" target="_blank">Закрыть больничный</a>
                            </li>

                            <li class="dropdown-submenu <?php echo (isset($convex_item['close_hol_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                <a tabindex="-1" href="/str/listfio/holiday" class="caret-spr_inf" target="_blank">Отозвать из отпуска</a>
                            </li>

                        </ul>
                    </li>
                    <?php
                }
                //ГРОЧС, ПАСЧ, РОСН(за весь),  Область админ, УГЗ-г.Минска (за весь) , Авиация
                elseif ((($_SESSION['ulevel'] == 3) || ($_SESSION['ulevel'] == 4)) || ($_SESSION['ulevel'] == 2 && $_SESSION['is_admin'] == 1) || ($_SESSION['ulevel'] == 3 && $_SESSION['note'] == 12 && $_SESSION['is_admin'] == 1)) {
                    ?>

                    <?php
                    //уровень области , НО не РОСН и не УГЗ, т.к. они видят весь список
                    if (($_SESSION['ulevel'] == 2 && ($_SESSION['note'] != 8 && $_SESSION['note'] != 9 ))) {
                        ?>
                        <li class="dropdown <?php echo (isset($convex_item['listfio'])) ? 'convex' : ''?>">
                            <a href="#" class="dropdown-toggle navbar-right-customer" data-toggle="dropdown" >Больничные и отпуска <b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li class="dropdown-submenu <?php echo (isset($convex_item['close_ill_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio/ill" class="caret-spr_inf" target="_blank">Закрыть больничный</a>
                                </li>

                                <li class="dropdown-submenu <?php echo (isset($convex_item['close_hol_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio/holiday" class="caret-spr_inf" target="_blank">Отозвать из отпуска</a>
                                </li>

                                <li class="dropdown-submenu <?php echo (isset($convex_item['all_listfio_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio" class="caret-spr_inf" target="_blank">Весь список</a>
                                </li>

<!--                                раскомементировать 01.10-->
                                <li class="dropdown-submenu <?php echo (isset($convex_item['open_listfio_sub'])) ? 'active-li-dropdown-submenu' : ''?>" >
                                    <a tabindex="-1" href="/str/listfio/open/table" class="caret-spr_inf" target="_blank">Открыть доступ на ред.</a>
                                </li>

                            </ul>
                        </li>
                        <?php
                    }
                    elseif(in_array($_SESSION['ulocorg'], $locorg_umchs) && $_SESSION['is_admin']==1){//ЦОУ области - может открыть/закрыть доступ на ред списка смен, отозвать из отпуска/бол
                        ?>
                                <li class="dropdown <?php echo (isset($convex_item['listfio'])) ? 'convex' : ''?>">
                            <a href="#" class="dropdown-toggle navbar-right-customer" data-toggle="dropdown" >Список смен (штат) <b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li class="dropdown-submenu <?php echo (isset($convex_item['close_ill_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio/ill" class="caret-spr_inf" target="_blank">Закрыть больничный</a>
                                </li>

                                <li class="dropdown-submenu <?php echo (isset($convex_item['close_hol_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio/holiday" class="caret-spr_inf" target="_blank">Отозвать из отпуска</a>
                                </li>

                                <li class="dropdown-submenu <?php echo (isset($convex_item['all_listfio_sub'])) ? 'active-li-dropdown-submenu' : ''?>">
                                    <a tabindex="-1" href="/str/listfio" class="caret-spr_inf" target="_blank">Весь список</a>
                                </li>

<!--                                раскомементировать 01.10-->
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="/str/listfio/open/table" class="caret-spr_inf" target="_blank">Открыть доступ на ред.</a>
                                </li>

                            </ul>
                        </li>
                        <?php
                    }
                    else {
                        ?>

                        <li class="<?php echo (isset($convex_item['listfio'])) ? 'convex' : ''?>"><a href="/str/listfio"> Список смен(штат)</a></li>

        <?php
    }
    ?>

                    <?php
                }
                ?>
                <!-------------------------  КОНЕЦ Больничныеи отпуска ------------------------>


                <li><a href="#" class="item-menu"><span  data-toggle="modal" data-target="#myModal" >Справка</span></a></li>

            </ul>

        </div>

    </div>
</div>

<?php
include 'reference.php';
?>

