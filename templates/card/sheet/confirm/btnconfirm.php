
<!--
    <div class="container noprint">

        <div class="col-lg-12 col-lg-offset-1">

            <div class="row">
                <p class="warning-main-confirm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;После внесения информации на всех вкладках - заступите на дежурство</p>     
                <div class="form-group ">
                    <div class="col-sm-offset-4 col-sm-10 col-md-offset-4 col-lg-offset-2 ">
                        <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/confirm">   <button type="button" class="btn btn-lg btn-danger warning-msg-animate"  data-toggle="tooltip" data-placement="left" title="После внесения информации на всех вкладках  подтвердите данные">Заступить на дежурство</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

<center><a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/confirm">  
        <?php
//button confirm date
if ($_SESSION['can_edit'] == 1) {
    ?>
        <button type="button" class="btn btn-lg btn-danger"  data-toggle="tooltip" data-placement="left" title="После внесения информации на всех вкладках  подтвердите данные">Заступить на дежурство</button>
        <?php
}
else{
    ?>
        <button type="button" class="btn btn-lg btn-danger"  data-toggle="tooltip" data-placement="left" title="После внесения информации на всех вкладках  подтвердите данные" disabled="">Заступить на дежурство</button>
        <?php
}
?>
    </a></center>
                   




