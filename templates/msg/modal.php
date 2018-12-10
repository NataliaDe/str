<!--Modal-->
<div id="myModal" class="modal fade in" style="display: block;">
    <div class="modal-dialog" id="modalinst">
        <div class="modal-content instruct">
            <div class="modal-header">  <a onclick="javascript:history.back();"> <button class="close" type="button" data-dismiss="modal">×</button></a>
                <h4 class="modal-title">Сообщение</h4>
            </div>
            <div class="modal-body">

                <?php
                if (isset($sign) && ($sign == 6)) {//sheet car
                    ?>
                    <center><b>Работник может быть назначен только на 1 единицу техники !!!</b></center>
                    <?php
                } else {
                    ?>
                    <center><b>У вас нет прав на выполнение данного действия !!!</b></center>          
                    <?php
                }
                ?>



            </div>

            <div class="modal-footer">



                <center> <a onclick="javascript:history.back();">  <button class="btn btn-danger" type="button" data-dismiss="modal">Назад</button></a></center>
            </div>
        </div>
    </div>
</div>