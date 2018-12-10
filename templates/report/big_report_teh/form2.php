<?php
//print_r($main);

?>
<br>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
Информация доступна за последние <b>3 дня</b>. Данные актуальны, если на указанную дату все строевые заполнены. <b>Не учитываются данные по ЦОУ!</b>

 <br><br>
<form  role="form" id="formFillCar" method="POST" action="/str/v1/report/big_report_teh2/">
    
     <div class="row">
        <div class="col-lg-2">
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11">    Дата деж.смены:</label>
                <div class="input-group date" id="date_start">
                    <input type="text" class="form-control"  name="date_start" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
          
     </div>
    <br><br>
    <center>
        <div class="row">

            <div class="form-group">
                <button type="submit" class="btn btn-info" name="export_to_excel">Экспорт в Excel</button>
            </div>  
        </div>
        
        <div class="row">
             <center> <a onclick="javascript:history.back();">  <button class="btn btn-warning" type="button" data-dismiss="modal">Назад</button></a></center>
        </div>
    </center>

</form>


