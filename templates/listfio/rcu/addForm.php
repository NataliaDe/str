<div class="container">
    <div class="col-lg-12">
<!--        <br>
<span class="glyphicon glyphicon-exclamation-sign" style="color: red;" ></span>&nbsp;&nbsp;
В текущей версии ( ver 1.5 )  <span style="color: red;" ><b>вакантов</b></span> в список смен вводить <span style="color: red;" >ЗАПРЕЩЕНО!</span> Будет нарушена формула подсчета л/с.
<br><br><br>-->

<br><br>
        <form  role="form" id="formListFio" method="POST" action="/str/listfio_rcu/add">


            <b>Заполните поля формы:</b>
            <br><br><br>
            <div class="row">

                <div class="col-lg-2">
                    <div class="form-group">

                        <label for="note">Подразделение</label>
                        <select class="form-control" name="id_record"  >

                            <?php
                            foreach ($pasp as $p) {

                                printf("<p><option value='%s'><label>%s</label></option></p>", $p['id'], $p['divizion_name']);
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">

                        <label for="locorg">Смена</label>
                        <select class="form-control" name="id_cardch"  >

                            <?php
                            foreach ($cardch as $c) {
                                if ($c['ch'] == 0) {
                                    printf("<p><option value='%s'  ><label>%s</label></option></p>", $c['id'], 'ежедневник/подменный');

                                } else
                                    printf("<p><option value='%s'  ><label>%s</label></option></p>", $c['id'], $c['ch']);


                            }

                            ?>

                        </select>
                    </div>
                </div>



                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="tname">Введите количество работников</label>
                        <input type="text" class="form-control" id="countill" name="count_empl"  >
                    </div>
                </div>


            </div>
            <br>
            <div class="row">

                <div class="col-lg-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Далее</button>
                        <br>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-2">
                    <div class="form-group">
                        <a href="/str/listfio_rcu">  <button type="button" class="btn btn-warning">Назад</button></a>

                    </div>
                </div>
            </div>


        </form>
    </div>
</div>
