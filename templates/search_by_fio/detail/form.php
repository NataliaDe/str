<br>

<form  role="form" id="" class="form-inline">



    <div class="form-group">

        <label for="date_for_search_by_fio">Информация за:</label>
        <div class="input-group date" id="date_for_search_by_fio">
            <?php
            if (isset($_POST['date_for_search_by_fio']) && !empty($_POST['date_for_search_by_fio'])) {

                ?>
                <input type="text" class="form-control"  name="date_for_search_by_fio" id="date" value="<?= $_POST['date_for_search_by_fio'] ?>" />
                <?php
            } else {

                ?>
                <input type="text" class="form-control"  name="date_for_search_by_fio" id="date" />
                <?php
            }

            ?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>

    </div>


    <input type="hidden" value="<?= $id ?>" name="id" id="id_fio">

    <div class="form-group">
        <button type="button" class="btn btn-default" id="show_detail_by_fio"><i class="fa fa-eye" aria-hidden="true" style="color: blue"></i>&nbsp;Просмотреть</button>
    </div>




</form>


<br><br>

<div id="search-by-fio">
    <?php
    //echo dirname(__FILE__). '/result.php';
    include dirname(__FILE__) . '/result.php';

    ?>
</div>

<?php


