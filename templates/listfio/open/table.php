<br>
<?php

?>
<div class="container" id="container-query-result">
    <div class="col-lg-12">


        <!--        <div class="table-responsive"  id="tbl-query-result">-->

       <b>Открыть доступ на редактирование списка смен</b>
        <br> <br> <br>
        <table class="table table-condensed   table-bordered tbl_show_inf" id="tbl_user_open_listfio">
            <!-- строка 1 -->
            <thead>
                <tr>
                    <th>Пользователь</th>
                    <th>Открыть/закрыть доступ</th>

                </tr>
            </thead>

            <tfoot>
                <tr>
                   <th>Пользователь</th>
                    <th>Открыть/закрыть доступ</th>
                </tr>
            </tfoot>

            <tbody>
                <?php
                foreach ($user as $row) {
                    ?>
<td>
                            <?= $row['name'] ?>
                        </td>
                        <td>
                            <?php
                            if($row['is_deny'] == 0){//доступ закрыт-можно открыть
                           ?>
                                       <a href="/str/listfio/open/<?= $row['id'] ?>">закрыт <button type="button"  class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Открыть"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>
                            <?php
                            }
                            else{
                                ?>
                                       <a href="/str/listfio/close/<?= $row['id'] ?>">открыт <button type="button" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Закрыть"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>
                                       <?php
                            }
                            ?>

                        </td>




                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>

        <!--        </div>-->
    </div>
</div>