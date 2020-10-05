<div class="container">
    <div class="col-lg-12">
        <br>      <br>

        <?php
//        echo $on_shtat.'<br>';
//        echo $on_list.'<br>';
//        echo $count_empl;
        if (isset($on_shtat) && isset($on_list)) {
            if ($on_shtat != $on_list + $count_empl) {

                ?>
                <div class="container">
                    <div class="alert alert-danger">

                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Обращаем внимание!</strong> В карточке учета сил и средств на сегодняшний день числится <u><?= $on_shtat ?></u> работников в смене.<br>
                        При внесении в список смены следующих работников количество работников по списку(<u><?= $on_list + $count_empl ?></u>) не будет соответствовать количеству работников
                        в смене по штату
                        в карточке учета сил и средств (<u><?= $on_shtat ?></u>).
                    </div>
                </div>
                <?php
            }
        }

        ?>



        <form  role="form" id="formListFioAdd" method="POST" action="/str/listfio">
            <u> <b><?= $pasp ?>  <?= ($ch != 0) ? ('смена ' . $ch) : 'ежедневник' ?></b></u>
            <br><br><br>
            <b>Заполните поля формы :</b>
            <br><br>
            <div class="row">
                <?php
                for ($i = 0; $i < $count_empl; $i++) {

                    ?>


                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="tname"><?= $i + 1 ?>.Ф.И.О.</label>
                                <input type="text" class="form-control" id="fiouser" name="fio<?= $i ?>"  >
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="tel"><?= $i + 1 ?>. тел.</label>
                                <input type="text" class="form-control" id="tel_id" name="tel<?= $i ?>" placeholder="введите номер телефона" >
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="checkbox checkbox-danger">
                                    <input id="checkbox<?= $i ?>" type="checkbox" class='cls-vacant' data-id='<?= $i ?>' name="is_vacant<?= $i ?>" value='1'>
                                    <label for="checkbox<?= $i ?>">
                                        Вакант (Ф.И.О. не указывать)
                                    </label>
                                </div>
                            </div>
                        </div>



                        <div id='div-vacant-date<?= $i ?>' class="hide">
                            <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="">с какого числа</label>
                            <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                                <div class="form-group">
                                    <div class="input-group input-append date vacant_from_date" >
                                        <input type="text" autocomplete="off" class="form-control cls-vacant-date" id="dd<?= $i ?>" name="vacant_from_date<?= $i ?>" value="<?= date("d-m-Y") ?>" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">



                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="id_rank"><?= $i + 1 ?>.Звание</label>
                                <select class="form-control chosen-select-deselect "  id="id_rank" name="id_rank<?= $i ?>" tabindex="2" >

                                    <?php
                                    foreach ($rank as $r) {

                                        printf("<p><option value='%s'  ><label>%s</label></option></p>", $r['id'], $r['name']);
                                    }

                                    ?>

                                </select>


                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="id_position"><?= $i + 1 ?>.Должность</label>
                                <select class="form-control chosen-select-deselect "  id="id_position" name="id_position<?= $i ?>" tabindex="2" >

                                    <?php
                                    foreach ($position as $p) {

                                        printf("<p><option value='%s'  ><label>%s</label></option></p>", $p['id'], $p['name']);
                                    }

                                    ?>

                                </select>


                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="checkbox checkbox-info">
                                    <input id="checkbox_is_nobody<?= $i ?>" type="checkbox" name="is_nobody<?= $i ?>" value='1'>
                                    <label for="checkbox_is_nobody<?= $i ?>">
                                        Нет работников (Ф.И.О. не указывать). Для малочисленных подразделений. В статистику не учитывается.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php
                }

                ?>
            </div>
            <input type="hidden" class="form-control"  name="count_empl" value="<?= $count_empl ?>"  >
            <input type="hidden" class="form-control"  name="id_cardch" value="<?= $id_cardch ?>"  >
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
                        <a href="/str/listfio/add">  <button type="button" class="btn btn-warning">Назад</button></a>

                    </div>
                </div>
            </div>


        </form>
    </div>
</div>

<script type="text/javascript" src="/str/app/js/jquery-1.11.1.js"></script>
<script>



    $('form#formListFioAdd').submit(function (e) {

        // Запрещаем стандартное поведение для кнопки submit
        e.preventDefault();

        var k = 0;
        var d = 0;

        var vacants = $('form#formListFioAdd').find('.cls-vacant');
        var vd = $('form#formListFioAdd').find('.cls-vacant-date');

        vacants.each(function () {
            if ($(this).is(':checked'))
                k++;
        });



        vd.each(function () {
            if ($(this).val() !== '' && $(this).val() !== null)
                d++;
        });
        //alert('v=' + k + ' d=' + d);

        if (k <= d) {
            $(this).unbind('submit').submit();
        } else {
            toastr.error('Для всех вакантов необходимо указать дату, с которой введена вакансия', 'Внимание:', {timeOut: 5000});
        }


    });




    /* notifications */
    $('body').on('change', '.cls-vacant', function () {
        var id = $(this).attr("data-id");

        if ($(this).is(':checked')) {
            $('#div-vacant-date'+id).removeClass('hide');
            $('input[name="fio'+id+'"]').val('ВАКАНТ');
        } else {
            $('#div-vacant-date'+id).addClass('hide');
            $('input[name="fio'+id+'"]').val('');
        }



    });
</script>

