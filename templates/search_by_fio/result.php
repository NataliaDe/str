<style>
    #myTable_wrapper{
        width: 72%;
    }
</style>

<?php
if(isset($result) && !empty($result)){
   ?>



<center>
    <caption><span style="font-weight: 600;"><u>Результат поиска  </u></span></caption>
    <br><br>
    <span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
 <span style="color: red;">  Для просмотра подробной информации - нажмите на Ф.И.О. соответствующего работника</span>
<br><br>
<table class="table table-condensed tbl_show_inf  table-bordered" id="myTable">




    <thead>
                    <tr>
                        <th>Ф.И.О.</th>
                        <th>Звание</th>
                        <th>Должность</th>
                        <th>Подразделение</th>
                        <th>Смена</th>
                         <th>ГРОЧС</th>
                            <th>Область</th>
                    </tr>
                </thead>

                <tfoot style="display: table-header-group">
                    <tr>
                       <th>Ф.И.О.</th>
                        <th>Звание</th>
                        <th>Должность</th>
                        <th>Подразделение</th>
                         <th>Смена</th>
                            <th>ГРОЧС</th>
                            <th>Область</th>

                    </tr>
                </tfoot>

                <tbody>
                    <?php
                    foreach ($result as $row) {

                        if ($row['ch'] == 1) {
                            ?>
                            <tr class="success">
                                <?php
                            } elseif ($row['ch'] == 2) {
                                ?>
                            <tr class="warning">
                                <?php
                            } elseif ($row['ch'] == 3) {
                                ?>
                            <tr class="info">
                                <?php
                            } else {
                                ?>
                            <tr class="danger">
                                <?php
                            }
                            ?>

                                <td><a href="/str/search_by_fio/detail/<?= $row['id_fio'] ?>" data-toggle="tooltip" data-placement="left" title="Подробная информация" target="_blank"><?= $row['fio'] ?></a></td>
                            <td><?= $row['rank'] ?></td>
                            <td><?= $row['position'] ?></td>
                            <td><?= $row['divizion'] ?></td>
                            <?php
                            if ($row['ch'] == 0) {
                                ?>
                                <td>ежедневник</td>
                                <?php
                            } else {
                                ?>
                                <td><?= $row['ch'] ?></td>
                                <?php
                            }
                            ?>

                                <td>
                                    <?= $row['locorg_name'] ?>
                                </td>
                                <td>
                                    <?= $row['region_name'] ?>
                                </td>


                        </tr>
                        <?php

                    }
                    ?>

                </tbody>
</table>

</center>

<?php
}
?>

<script>
  $(document).ready(function() {
    $(function () {
        $('#myTable').DataTable({
            language: {
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }
            }
        });

    });
  });

/* таблица со списком людей по сменам*/
$('#myTable tfoot th').each( function (i) {
     var table = $('#myTable').DataTable();
   // if((i !== 5)&&(i !== 6)){

        if((i == 1)||(i == 2)){
     //выпадающий список
     var y = 1;
                var select = $('<select class="' + i + '  noprint" id="sel' + y + i + '"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function () {

                            var val = $(this).val();

                            table.column(i) //Only the first column
                                    .search(val ? '^' + $(this).val() + '$' : val, true, false)
                                    .draw();
                        });
                table.column(i).data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });


        }
        else{
            var title = $('#myTable tfoot th').eq( $(this).index() ).text();
        var x = $('#myTable tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск ' + title + '" />');
        }

 //   }
});

$("#myTable tfoot input").on( 'keyup change', function () {
     var table = $('#myTable').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});

</script>

