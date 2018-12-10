<?php

foreach ($empl as $val) {
    $fio = $val['fio'];
    $id_cardch = $val['id'];
    $id_card = $val['id_card'];
    $ch = $val['ch'];
    $id_rank=$val['rank'];
    $id_position=$val['position'];
    $is_vacant=$val['is_vacant'];
    $is_nobody=$val['is_nobody'];
}

//echo $id_position;
//print_r($rank);
?>
<div class="container">
    <div class="col-lg-12">
        <br>      <br>

        <form  role="form" id="editFormListFio" method="POST" action="/str/listfio/<?= $id_empl ?>">


            <b>Заполните поля формы:</b>
            <br><br><br>
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="fio">Ф.И.О.</label>
                        <input type="text" class="form-control" id="fiouser" name="fio" value="<?= $fio ?>"  >
                    </div>
                </div>
                
                 <div class="col-lg-3">
                                    <div class="form-group">
                                <div class="checkbox checkbox-danger">
                                    <?php
                                    if($is_vacant==1){
                                       ?>
                                    <input id="checkbox1" type="checkbox" name="is_vacant" value='1' checked="">
                                    <?php
                                    }
                                    else{
                                        ?>
                                        <input id="checkbox1" type="checkbox" name="is_vacant" value='1'>
                                      <?php
                                    }
                                    ?>
                                  
                                    <label for="checkbox1">
                                       Вакант (Ф.И.О. не указывать)
                                    </label>
                                </div>
                            </div>
                           </div>
                
                          <div class="col-lg-3">
                                    <div class="form-group">
                                <div class="checkbox checkbox-info">
                                             <?php
                                    if($is_nobody==1){
                                       ?>
                                    <input id="checkbox_is_nobody" type="checkbox" name="is_nobody" value='1' checked="">
                                    <?php
                                    }
                                    else{
                                        ?>
                                   <input id="checkbox_is_nobody" type="checkbox" name="is_nobody" value='1'>
                                      <?php
                                    }
                                    ?>
                                  
                                    <label for="checkbox_is_nobody">
                                       Нет работников (ежедневник). Для малочисленных подразделений. В статистику не учитывается. Нужен для заступления смены, где нет работников.
                                    </label>
                                </div>
                            </div>
                           </div>
                
                    <div class="col-lg-3">
                        <div class="form-group">
                              <label for="id_rank">Звание</label>
                             <select class="form-control chosen-select-deselect "  id="id_rank" name="id_rank" tabindex="2" >

                                <?php
                                    foreach ($rank as $r) {
                                       if(isset($id_rank)&& !empty($id_rank)){
                                           if($r['id'] == $id_rank)
                                                printf("<p><option value='%s' selected ><label>%s</label></option></p>", $r['id'], $r['name']);
                                           else
                                                       printf("<p><option value='%s'  ><label>%s</label></option></p>", $r['id'], $r['name']);
                                       } 
                                       else {
                                             printf("<p><option value='%s'  ><label>%s</label></option></p>", $r['id'], $r['name']); 
                                       }
                                }
                                ?>

                            </select>
                          
                          
                        </div>
                    </div>
                        <div class="col-lg-3">
                        <div class="form-group">
                              <label for="id_position">Должность</label>
                             <select class="form-control chosen-select-deselect "  id="id_position" name="id_position" tabindex="2" >

                                <?php
                                    foreach ($position as $p) {
                                         if(isset($id_position) && !empty($id_position)){
                                           if($p['id']==$id_position)
                                             printf("<p><option value='%s' selected ><label>%s</label></option></p>", $p['id'], $p['name']);
                                           else
                                                      printf("<p><option value='%s'  ><label>%s</label></option></p>", $p['id'], $p['name']);
                                       } 
                                         else{
                                             printf("<p><option value='%s'  ><label>%s</label></option></p>", $p['id'], $p['name']);  
                                         }   
                                      
                                }
                                ?>

                            </select>
                          
                          
                        </div>
                    </div>
            </div>
            <div class="row">

                <div class="col-lg-2">
                    <div class="form-group">

                        <label for="note">Подразделение</label>
                        <select class="form-control chosen-select-deselect " name="id_record" id="id_record" >


                            <option value="">Выбрать</option>
                            <?php
                            foreach ($pasp as $p) {
                                if ($id_card == $p['id']) {
                                    printf("<p><option value='%s' selected><label>%s</label></option></p>", $p['id'], $p['divizion_name']);
                                } else
                                    printf("<p><option value='%s'><label>%s</label></option></p>", $p['id'], $p['divizion_name']);
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">

                        <label for="locorg">Смена</label>
                        <select class="form-control" name="id_cardch" id="cardch" >


                            <option value="">Выбрать</option>
                            <?php
                            foreach ($cardch as $c) {
                                if ($id_cardch == $c['id']) {
                                    if ($c['ch'] == 0) {
                                        printf("<p><option value='%s' class='%s' selected><label>%s</label></option></p>", $c['id'], $c['id_card'], 'ежедневник');
                                    } else
                                        printf("<p><option value='%s' class='%s' selected><label>%s</label></option></p>", $c['id'], $c['id_card'], $c['ch']);
                                } else {
                                    if ($c['ch'] == 0) {
                                        printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $c['id'], $c['id_card'], 'ежедневник');
                                    } else {
                                        printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $c['id'], $c['id_card'], $c['ch']);
                                    }
                                }
                            }
                            ?>

                        </select>
                    </div>
                </div>

            </div>
            
            
            
            <input type="hidden" name="_METHOD" value="PUT"/>
            <br>
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
                        <a href="/str/listfio">  <button type="button" class="btn btn-warning">Назад</button></a>

                    </div>
                </div>
            </div>


        </form>
    </div>
</div>
