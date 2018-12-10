<?php
//print_r($ill);
?>
<form class="form-inline" role="form" id="formDeleteListFio" method="POST" action="/str/listfio/close_ill/<?= $id_ill ?>">

            <table class="table table-condensed   table-bordered tbl_show_inf" >
                <!-- строка 1 -->
                <thead>
                    <tr >
                        <th >Ф.И.О</th>
                        <th >Дата открытия</th>
                        <th>Дата закрытия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($ill as $row) {
                        ?>
                        <tr>
                            <td>
                                <div class="form-group">
                             <?php
                                echo $row['fio'].',<br>'.'<i>'.$row['position'].'</i>';
                             ?>
                                </div>
                            </td>
                            <td>
                                <div class="input-group date" id="date11">
                                    <?php
                                    if ($row['date1'] == '00.00.0000') {
                                        ?>
                                        <input type="text" class="form-control" name="date1" style="width: auto;" />
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" name="date1" value="<?= $row['date1'] ?>" style="width: auto;"/>
                                        <?php
                                    }
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <div class="input-group date" id="date21">
                                        <?php
                                        if ($row['date2'] == '00.00.0000') {
                                            ?>
                                            <input type="text" class="form-control" name="date2" style="width: auto;"/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="text" class="form-control" name="date2" value="<?= $row['date2'] ?>" style="width: auto;"/>
                                            <?php
                                        }
                                        ?>

                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    <input type="hidden" class="form-control"   name="id" value="<?= $row['id'] ?>">
                    <?php
                }
                ?>

                </tbody>
            </table>
 
<center>
        <div class="row">
            <div class="form-group">
            <button type="submit" class="btn btn-danger">  Сохранить изменения  </button>
                    <br>    <br>
            </div>
        </div>
    </center>
</form>



