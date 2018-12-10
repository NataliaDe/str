<?php
//print_r($user);
?>

<div class="container">
    <div class="col-lg-12">
        <br>      <br>
        <?php
        if ($type_query == 1) {//put
            ?>
            <form  role="form" id="formNewUser" method="POST" action="/str/user/<?= $id ?>">
                <?php
            }
            if ($type_query == 0) {//post
                ?>
                <form  role="form" id="formNewUser" method="POST" action="/str/user/new/<?= $sub ?>">
                    <?php
                }
                ?>

                <b>Заполните поля формы:</b>
                <br><br><br>
                <div class="row">

                    <?php

                    if (($sub == 0)|| (($sub == 2)&&($note != 8 )&& (!empty($note)))) {//umchs
                        ?>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="region">Область</label>
                                <select class="form-control" name="region" id="region" >

                                    <option value="">Выбрать</option>
                                    <?php
                                    foreach ($region as $re) {

                                        if ($select == 1) {
                                            if ($user->regions_id == $re['id']) {
                                                printf("<p><option value='%s' selected><label>%s</label></option></p>", $re['id'], $re['name']);
                                            } else
                                                printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                        } else
                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">

                                <label for="locorg">Г(Р)ОЧС</label>
                                <select class="form-control" name="locorg" id="locorg" >


                                    <option value="">Выбрать</option>
                                    <?php
                                    foreach ($locorg as $lo) {
                                        if ($select_grochs == 1) {
                                            if ($user->locorg_id == $lo['locorg_id']) {
                                                printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                                            } else
                                                printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                                        } else
                                            printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class = "col-lg-2">
                            <div class = "form-group">
                                <label for = "diviz">Подразделение</label>
                                <select class = "form-control" name = "diviz" id = "diviz" >
                                    <option value="">Выбрать</option>
                                    <?php
                                    foreach ($diviz as $di) {
                                        if ($select_pasp == 1) {
                                            if ($user->records_id == $di['recid']) {
                                                printf("<p><option value='%s' class='%s' selected><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                                            } else
                                                printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                                        } else
                                            printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if (($sub == 2)&& ($note==8)) {//ROSN
                    ?>
                    <div class="col-lg-2">
                        <div class="form-group">

                            <label for="note">Подразделение</label>
                            <select class="form-control" name="note" id="note" >


                                <option value="">Выбрать</option>
                                <?php
                                foreach ($organs as $org) {
                                    if (isset($select_organ) && ( $select_organ == 1)) {
                                        if ($user->note == $org['id']) {
                                            printf("<p><option value='%s'  selected ><label>%s</label></option></p>", $org['id'], $org['name']);
                                        } else
                                            printf("<p><option value='%s'  ><label>%s</label></option></p>", $org['id'], $org['name']);
                                    } else
                                        printf("<p><option value='%s'><label>%s</label></option></p>", $org['id'], $org['name']);
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">

                            <label for="locorg">ОУ</label>
                            <select class="form-control" name="locorg" id="locnamecp" >


                                <option value="">Выбрать</option>
                                <?php
                                foreach ($locorg as $lo) {
                                    if ($select_grochs == 1) {
                                        if ($user->locorg_id == $lo['locorgid']) {
                                            printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorgid'], $lo['organid'], $lo['locname']);
                                        } else
                                            printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorgid'], $lo['organid'], $lo['locname']);
                                    } else{
                               
                                          printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorgid'], $lo['organid'], $lo['locname']);
                                    }
                                      
                                }
                                ?>

                            </select>
                        </div>
                    </div>



                    <?php
                }
                
                ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="tname">Ф.И.О. пользователя</label>
                            <?php
                            if (isset($user)) {
                                ?>
                                <input type="text" class="form-control" id="fiouser" placeholder="Ф.И.О." name="fiouser" value="<?= $user->name ?>">
                                <?php
                            } else {
                                ?>
                                <input type="text" class="form-control" id="fiouser" placeholder="Ф.И.О." name="fiouser" >
                                <?php
                            }
                            ?>

                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="tname">Логин</label>

                            <?php
                            if (isset($user)) {
                                ?>
                                <input type="text" class="form-control" id="loginuser" placeholder="Логин" name="loginuser" value="<?= $user->login ?>" >
                                <?php
                            } else {
                                ?>
                                <input type="text" class="form-control" id="loginuser" placeholder="Логин" name="loginuser"  >
                                <?php
                            }
                            ?>


                        </div>
                    </div>

                    <?php
                    if ($type_query == 0) {//post
                        ?>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="pswuser">Пароль</label>

                                <input type="text" class="form-control" id="pswuser" placeholder="Пароль" name="pswuser"  >
                            </div>
                        </div>
                        <?php
                    }
                    ?>


                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <div class="checkbox checkbox-success">
                                <?php
                                if (isset($user->can_edit)) {
                                    if ($user->can_edit == 1) {
                                        ?>
                                        <input id="checkbox1" type="checkbox" name="can_edit"  checked="">
                                        <?php
                                    } else {
                                        ?>
                                        <input id="checkbox1" type="checkbox" name="can_edit">
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <input id="checkbox1" type="checkbox" name="can_edit" >
                                    <?php
                                }
                                ?>

                                <label for="checkbox1">
                                    Может заполнять/ред.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                                        <div class="col-lg-2">
                        <div class="form-group">
                            <div class="checkbox checkbox-success">
                                <?php
                                if (isset($user->is_admin)) {
                                    if ($user->is_admin == 1) {
                                        ?>
                                        <input id="checkbox2" type="checkbox" name="is_admin"  checked="">
                                        <?php
                                    } else {
                                        ?>
                                        <input id="checkbox2" type="checkbox" name="is_admin">
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <input id="checkbox2" type="checkbox" name="is_admin" >
                                    <?php
                                }
                                ?>

                                <label for="checkbox2">
                                    Админ.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-lg-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-2">
                        <div class="form-group">
                            <a href="/str/user/">  <button type="button" class="btn btn-warning">Назад</button></a>

                        </div>
                    </div>
                </div>
                <?php
                if ($type_query == 1) {//put
                    ?>
                    <input type="hidden" name="_METHOD" value="PUT"/>
                    <?php
                }
                ?>

            </form>
    </div>
</div>
