<?php
foreach ($empl as $val) {
    $fio = $val['fio'];
    $id_cardch = $val['id'];
    $id_card = $val['id_card'];
    $ch = $val['ch'];
    $id_rank = $val['rank'];
    $id_position = $val['position'];
    $is_vacant = $val['is_vacant'];
    $is_nobody = $val['is_nobody'];
    $phone = $val['phone'];
    $vacant_from_date = $val['vacant_from_date'];

    if (!empty($vacant_from_date)) {
        $date_d = new DateTime($vacant_from_date);
        $vacant_from_date = $date_d->Format('d-m-Y');
    }
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
                        <label for="tel">тел.</label>
                        <input type="text" class="form-control" id="tel_id" name="tel" value="<?= $phone ?>" >
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <div class="checkbox checkbox-danger">
                            <?php
                            if ($is_vacant == 1) {

                                ?>
                            <input id="checkbox1" type="checkbox" name="is_vacant" value='1' checked="" class="cls-vacant">
                                <?php
                            } else {

                                ?>
                            <input id="checkbox1" type="checkbox" name="is_vacant" value='1' class="cls-vacant">
                                <?php
                            }

                            ?>

                            <label for="checkbox1">
                                Вакант (Ф.И.О. не указывать)
                            </label>
                        </div>
                    </div>
                </div>





                <div id='div-vacant-date' class="<?=($is_vacant == 1) ? '' : 'hide'?>">
                    <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="">с какого числа</label>
                    <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                        <div class="form-group">
                            <div class="input-group input-append date vacant_from_date" >
                                <input type="text" autocomplete="off" class="form-control cls-vacant-date" id="dd" name="vacant_from_date" value="<?= $vacant_from_date ?>" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="id_rank">Звание</label>
                        <select class="form-control chosen-select-deselect "  id="id_rank" name="id_rank" tabindex="2" >

                            <?php
                            foreach ($rank as $r) {
                                if (isset($id_rank) && !empty($id_rank)) {
                                    if ($r['id'] == $id_rank)
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
                                if (isset($id_position) && !empty($id_position)) {
                                    if ($p['id'] == $id_position)
                                        printf("<p><option value='%s' selected ><label>%s</label></option></p>", $p['id'], $p['name']);
                                    else
                                        printf("<p><option value='%s'  ><label>%s</label></option></p>", $p['id'], $p['name']);
                                }
                                else {
                                    printf("<p><option value='%s'  ><label>%s</label></option></p>", $p['id'], $p['name']);
                                }
                            }

                            ?>

                        </select>


                    </div>
                </div>




                <div class="col-lg-3">
                    <div class="form-group">
                        <div class="checkbox checkbox-info">
                            <?php
                            if ($is_nobody == 1) {

                                ?>
                                <input id="checkbox_is_nobody" type="checkbox" name="is_nobody" value='1' checked="">
                                <?php
                            } else {

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
            </div>


            <div class="row">

                <div class="col-lg-3">
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
                <div class="col-lg-3">
                    <div class="form-group">

                        <label for="locorg">Смена</label>
                        <select class="form-control " name="id_cardch" id="cardch" >


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



<script type="text/javascript" src="/str/app/js/jquery-1.11.1.js"></script>
<script>



    $('form#editFormListFio').submit(function (e) {

        // Запрещаем стандартное поведение для кнопки submit
        e.preventDefault();

        var k = 0;
        var d = 0;

        var vacants = $('form#editFormListFio').find('.cls-vacant');
        var vd = $('form#editFormListFio').find('.cls-vacant-date');


        var id_record = $('form#editFormListFio').find('#id_record').val();
        var id_cardch = $('form#editFormListFio').find('#cardch').val();
        var fio = $('form#editFormListFio').find('#fiouser').val();


        if(id_record ==='' || id_record === null){
            toastr.error('Выберите подразделение', 'Внимание:', {timeOut: 5000});
        }
        else if(id_cardch ==='' || id_cardch === null){
            toastr.error('Выберите смену', 'Внимание:', {timeOut: 5000});
        }
         else if(fio ===''){
            toastr.error('Введите Ф.И.О. работникоа', 'Внимание:', {timeOut: 5000});
        }
        else if (vacants.is(':checked') && (vd.val() === '' || vd.val() === null)){
            toastr.error('Для всех вакантов необходимо указать дату, с которой введена вакансия', 'Внимание:', {timeOut: 5000});

        }
        else{
$(this).unbind('submit').submit();
        }

    });




    /* notifications */
    $('body').on('change', '.cls-vacant', function () {

        if ($(this).is(':checked')) {
            $('#div-vacant-date').removeClass('hide');
            $('input[name="fio"]').val('ВАКАНТ');
        } else {
            $('#div-vacant-date').addClass('hide');
            $('input[name="fio"]').val('');
        }



    });
</script>
