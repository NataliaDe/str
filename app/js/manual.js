

$('#login, #loginuser').keypress(function (key) {
    if ((key.charCode > 47 && key.charCode < 58) || (key.charCode > 64 && key.charCode < 91) || (key.charCode > 96 && key.charCode < 123) || (key.charCode === 95) || (key.charCode === 13))
        return true;
    else
        return false;

});

$('#login, #loginuser').keyup(function () {
    var $this = $(this);
    if ($this.val().length > 30)
        $this.val($this.val().substr(0, 30));

});
$('#password, #pswuser').keypress(function (key) {
    if ((key.charCode > 47 && key.charCode < 58) || (key.charCode > 64 && key.charCode < 91) || (key.charCode > 96 && key.charCode < 123) || (key.charCode === 13) || (key.charCode === 95))
        return true;
    else
        return false;

});


//count ill 0 нельзя максимум 9 чел за 1 раз
$('#countill, #counthol, #counttrip, #countother').keypress(function (key) {
    if ((key.charCode < 49) || (key.charCode > 57))
        return false;
});
//numbers
$('#countdisp, #countls, #listls, #vacant, #face, #calculation, #gas, #duty, #kip, #asv').keypress(function (key) {
    if ((key.charCode < 48) || (key.charCode > 57))
        return false;
});
$('#countill, #counthol, #counttrip, #countother').keyup(function () {
    var $this = $(this);
    if ($this.val().length > 1)
        $this.val($this.val().substr(0, 1));

});

$('#foam, #powder').keypress(function (key) {
    if (((key.charCode < 48)&& (key.charCode != 44)) || (key.charCode > 57) )
        return false;
});



/* вакант = по штату-по списку, вычислить автоматически, поле вакант не может быть отрицательным */
//$("#countls").keyup(function(){
//    var vacant= $('#countls').val()-$('#listls').val();
//$('#vacant').val(vacant);
//
//
//});
$("#listls").keyup(function(){
//    var vacant= $('#countls').val()-$('#listls').val();
//$('#vacant').val(vacant);


/********* налицо=список-больной-командировка-отпуск-др.причины+ежедневники+др.ПАСЧ  *********/
var on_every=Number($('#on_every').val());
var on_reserve=Number($('#on_reserve').val());
var s=on_every+on_reserve;
var on_face=$('#listls').val()-$('#on_ill').val()-$('#on_holiday').val()-$('#on_trip').val()-$('#on_other').val()+Number(s);
$('#face').val(on_face);

/*наряд = налицо - б.р , вычислить автоматически, поле налицо не может быть отрицательным*/
  var duty= $('#face').val()-$('#calc').val();
$('#duty').val(duty);
});







/* наряд = налицо - б.р , вычислить автоматически, поле налицо не может быть отрицательным */
$("#face").keyup(function(){
    var duty= $('#face').val()-$('#calc').val();
$('#duty').val(duty);
//if(duty<0)
//$('#save_main').prop('disabled',true);
//else
//   $('#save_main').prop('disabled',false);

});
$("#calc").keyup(function(){
  var duty= $('#face').val()-$('#calc').val();
$('#duty').val(duty);
//if(duty<0)
//$('#save_main').prop('disabled',true);
//else
//   $('#save_main').prop('disabled',false);
});

/* техника. м.б. выбрано 1 из полей боев/рез-ремонт-ТО */
//function getTehParam(j){
//var j=1;
   // $('#type' + 1).change(function(){
        //alert(j);
/*$('#to' + 1).val(3);
$('#repaire' + 1).val(0);
});*/
/*$('#to' + j).change(function(){
$('#type1' + j).val(3);
$('#repaire' + j).val(0);
});
$('#repaire' + j).change(function(){
$('#type1' + j).val(3);
$('#to' + j).val(3);
});*/
//}


/*--------- пересчет налицо ----------*/
function getCountOnFace(){
   /********* налицо=список-больной-командировка-отпуск-др.причины+ежедневники+др.ПАСЧ  *********/
var on_every=Number($('#on_every').val());
var on_reserve=Number($('#on_reserve').val());
var s=on_every+on_reserve;
var on_face=$('#listls').val()-$('#on_ill').val()-$('#on_holiday').val()-$('#on_trip').val()-$('#on_other').val()+Number(s);
$('#face').val(on_face);

/*наряд = налицо - б.р , вычислить автоматически, поле налицо не может быть отрицательным*/
  var duty= $('#face').val()-$('#calc').val();
$('#duty').val(duty);
}

/******************** ТО, ремонт, боевая/резерв  м.б. выбрано 1 из полей боев/рез-ремонт-ТО ********************************/
function getTehType(j){
    //alert($('#type' + j).val());
    if(($('#type' + j).val()==1)||($('#type' + j).val()==2)){
        $('#to' + j).val(3);
$('#repaire' + j).val(0);
    }
      else{
                $('#to' + j).val(1);
$('#repaire' + j).val(0);
    }

}
function getTehTo(j){
    //alert($('#type' + j).val());
    if(($('#to' + j).val()==1)||($('#to' + j).val()==2)){
        $('#type' + j).val(3);
$('#repaire' + j).val(0);
    }
    else{
         $('#type' + j).val(1);
$('#repaire' + j).val(0);
    }
}
function getTehRepaire(j){
    //alert($('#type' + j).val());
    if($('#repaire' + j).val()==1){
        $('#to' + j).val(3);
$('#type' + j).val(3);
    }
    else{
        $('#to' + j).val(3);
$('#type' + j).val(1);
    }
}
/******************************************/

//создать пользователя
    $('#fiouser').keypress(function (key) {
        if (((key.charCode < 46) && (key.charCode != 32)) || ((key.charCode > 46) && (key.charCode < 1040)) || (key.charCode > 1103))
            return false;
    });
    //fioill max 90 chars
     $('#fiouser').keyup(function () {
    var $this = $(this);
    if ($this.val().length > 90)
        $this.val($this.val().substr(0, 90));

});


for (var i = 1; i <= 99; i++) {

//diagnosis max 250 chars
     $('#diagnosis' + i).keyup(function () {
    var $this = $(this);
    if ($this.val().length > 250)
        $this.val($this.val().substr(0, 250));

});

}

//technics
for (var i = 1; i <= 150; i++) {
    //number,
    $('#petrol'+i).keypress(function (key) {
     if (((key.charCode < 48)&& (key.charCode != 44)) || (key.charCode > 57) )
        return false;
});
$('#diesel'+i).keypress(function (key) {
      if (((key.charCode < 48)&& (key.charCode != 44)) || (key.charCode > 57) )
        return false;
});
$('#foam'+i).keypress(function (key) {
    if (((key.charCode < 48)&& (key.charCode != 44)) || (key.charCode > 57) )
        return false;
});
$('#powder'+i).keypress(function (key) {
    if (((key.charCode < 48)&& (key.charCode != 44)) || (key.charCode > 57) )
        return false;
});
//max 8
$('#petrol'+i).keyup(function () {
    var $this = $(this);
    if ($this.val().length > 8)
        $this.val($this.val().substr(0, 8));

});
$('#diesel'+i).keyup(function () {
    var $this = $(this);
    if ($this.val().length > 8)
        $this.val($this.val().substr(0, 8));

});
$('#foam'+i).keyup(function () {
    var $this = $(this);
    if ($this.val().length > 8)
        $this.val($this.val().substr(0, 8));

});
$('#powder'+i).keyup(function () {
    var $this = $(this);
    if ($this.val().length > 8)
        $this.val($this.val().substr(0, 8));

});
}


     //fiochief rus .
    $('#fiochief').keypress(function (key) {
        if (((key.charCode < 46) && (key.charCode != 32)) || ((key.charCode > 46) && (key.charCode < 1040)) || (key.charCode > 1103))
            return false;
    });
    //fiochief max 90 chars
     $('#fiochief').keyup(function () {
    var $this = $(this);
    if ($this.val().length > 90)
        $this.val($this.val().substr(0, 90));

});

    //fiodisp rus .
    $('#fiodisp').keypress(function (key) {
        if (((key.charCode < 46) && (key.charCode != 32)&& (key.charCode != 44)) || ((key.charCode > 46) && (key.charCode < 1040)) || (key.charCode > 1103))
            return false;
    });
    //fiodisp max 250 chars
     $('#fiodisp').keyup(function () {
    var $this = $(this);
    if ($this.val().length > 250)
        $this.val($this.val().substr(0, 250));

});







//всплывающая подсказка
 $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });


//  $( "#danger-msg-animate" ).animate({
//    width: "70%",
//    opacity: 0.4,
//    marginLeft: "0.6in",
//    fontSize: "3em",
//    borderWidth: "10px",
//   backgroundColor: "red"
//  }, 300 );

  // jQuery('#elId').animate({backgroundColor: "red", 300});

 jQuery("#locorg").chained("#region"); //отчеты, зависимость района от области
 jQuery("#diviz").chained("#locorg");//зависимость ПАСЧ от выбранного ГРОЧС

  jQuery("#lev").chained("#sub");//зависимость ПАСЧ от выбранного ГРОЧС

   jQuery("#locnamecp").chained("#note");//зависимость ОУ ЦП от выбранного подразделения(РОСН)
   jQuery("#cardch").chained("#id_record");//зависимость cardch от records

   /* форма выбора техники из др ПАСЧ */
   jQuery("#id_grochs_for_car").chained("#id_region_for_car"); // зависимость района от области
 jQuery("#id_diviz_for_car").chained("#id_grochs_for_car");//зависимость ПАСЧ от выбранного ГРОЧС
 jQuery("#id_teh_for_reserve").chained("#id_diviz_for_car");//зависимость техники от выбранного ПАСЧ


$(document).ready(function () {  // поиск значения в выпад меню
$(".chosen-select-deselect").chosen({
   allow_single_deselect: true,
   width: '100%'

});
});




//validation form ----------------------------------------------------------------------------------------------

$('#check')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                login: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите логин'
                        }
                    }
                },
                password: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите пароль'
                        }
                    }
                }
            }

        });
$('#formCountIll')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                countill: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                           message: 'От 1 до 9 работников '
                        }
                    }
                }
            }
        });

        $('#formCountHol')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                counthol: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                           message: 'От 1 до 9 работников '
                        }
                    }
                }
            }
        });

            $('#formCountTrip')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                counttrip: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'От 1 до 9 работников '
                        }
                    }
                }
            }
        });

               $('#formCountOther')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                countother: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'От 1 до 9 работников '
                        }
                    }
                }
            }
        });



        $(document).ready(function() {
            $('#formFillMain')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                dateduty: {
                validators: {
                     notEmpty: {
                            message: 'Выберите дату заступления смены'
                        },
                    date: {
                       format: 'DD-MM-YYYY',
                        message: 'Неправильный формат'
                    }
                }
            },
               id_fio: {

                    validators: {
                        notEmpty: {
                            message: 'Выберите Ф.И.О. начальника смены'
                        }
                    }
                }

            }


        });

    $('#dateduty').on('dp.change dp.show', function(e) {
        $('#formFillMain').bootstrapValidator('revalidateField', 'dateduty');
    });
});

//форма добавления нового польз
$('#formNewUser')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                    region: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите Ф.И.О.'
                        }
                    }
                },
                fiouser: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите Ф.И.О.'
                        }
                    }
                },
                    loginuser: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите логин'
                        }
                    }
                },
                    pswuser: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите пароль'
                        }
                    }
                },
                    note: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Выберите подразделение'
                        }
                    }
                }


            }

        });
$('#formListFio')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                id_record: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Выберите подразделение'
                        }
                    }
                },
                cardch: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Выберите смену'
                        }
                    }
                },
                   count_empl: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите количество работников'
                        }
                    }
                }
            }

        });


        $('#editFormListFio')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                id_record: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Выберите подразделение'
                        }
                    }
                },
                id_cardch: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Вберите смену'
                        }
                    }
                },
                   fio: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите Ф.И.О. работникоа'
                        }
                    }
                }
            }

        });


/*-------- DataTables -----------------*/
(function ($, undefined) {
    $(function () {
        $('#tbluser').DataTable({
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
            },
                       "order": [],
              "aoColumnDefs": [
      { "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] }
    ]




        });
    });
})(jQuery);

(function ($, undefined) {
    $(function () {
        $('#tbl_general, #tbl_list_fio, #tbl_basic_inf_ill, #tbl_basic_inf_hol, #tbl_basic_inf_trip, #tbl_basic_inf_other, #tbl_user_open_listfio').DataTable({
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


                $('#tbl_count_position').DataTable({
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
            },
                                   "order": [],
              "aoColumnDefs": [
      { "bSortable": false, "aTargets": [ 0,1,2 ] }
    ]
        });


        /*-------------- скрыть/отобразить колонки таблицы builder absent----------------*/
          var tbl_basic_inf_ch = $('#tbl_basic_inf_ch').DataTable( {
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
    } );

            $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        // Get the column API object
        var column = tbl_basic_inf_ch.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );


    } );

    });


})(jQuery);

$(document).ready(function() {
     $("tfoot").css("display", "table-header-group");
     //таблица со списком пользователей
$('#tbluser tfoot th').each( function (i) {
     var table = $('#tbluser').DataTable();
    if((i !== 7)&&(i !== 8)){
        var title = $('#tbluser tfoot th').eq( $(this).index() ).text();
        var x = $('#tbluser tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск ' + title + '" />');
    }
});
$("#tbluser tfoot input").on( 'keyup change', function () {
     var table = $('#tbluser').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});

/* таблица о заполненности строевой*/
$('#tbl_general tfoot th').each( function (i) {
     var table = $('#tbl_general').DataTable();
    if(i !== 6){

        if((i == 0)||(i == 3)||(i==4)||(i==7) ){
     //выпадающий список
     var y='tbl_general';
                var select = $('<select class="' + i + '  noprint"  id="sel_' + y + i + '"><option value=""></option></select>')
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
            var title = $('#tbl_general tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_general tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }

    }
});
$("#tbl_general tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_general').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});

/* таблица со списком людей по сменам*/
$('#tbl_list_fio tfoot th').each( function (i) {
     var table = $('#tbl_list_fio').DataTable();
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
            var title = $('#tbl_list_fio tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_list_fio tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск ' + title + '" />');
        }

 //   }
});

$("#tbl_list_fio tfoot input").on( 'keyup change', function () {
     var table = $('#tbl_list_fio').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});



/* таблица c информацией по отсутствующим в запроснике basic*/
$('#tbl_basic_inf_ch tfoot th').each( function (i) {
     var table = $('#tbl_basic_inf_ch').DataTable();

        if((i == 0)||(i == 1)||(i==5)||(i==8)){
     //выпадающий список
                var select = $('<select class="' + i + '  noprint" id="sel' + i + '" ><option value=""></option></select>')
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
            var title = $('#tbl_basic_inf_ch tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_basic_inf_ch tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }
});
$("#tbl_basic_inf_ch tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_basic_inf_ch').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});


/*--------------------- таблица Запросы-больничные -------------------------*/
$('#tbl_basic_inf_ill tfoot th').each( function (i) {
     var table = $('#tbl_basic_inf_ill').DataTable();

        if((i == 0)||(i == 2)||(i==4) || (i==8) ){
     //выпадающий список
     var y='tbl_basic_inf_ill';
                var select = $('<select class="' + i + '  noprint"  id="sel_' + y + i + '"><option value=""></option></select>')
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
            var title = $('#tbl_basic_inf_ill tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_basic_inf_ill tfoot th').index($(this));
        var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }


});
$("#tbl_basic_inf_ill tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_basic_inf_ill').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});
/*-------------- запросы-отпуска ----------------*/
$('#tbl_basic_inf_hol tfoot th').each( function (i) {
     var table = $('#tbl_basic_inf_hol').DataTable();

        if((i == 0)||(i == 2) ){
     //выпадающий список
     var y='tbl_basic_inf_hol';
                var select = $('<select class="' + i + '  noprint"  id="sel_' + y + i + '"><option value=""></option></select>')
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
            var title = $('#tbl_basic_inf_hol tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_basic_inf_hol tfoot th').index($(this));
        var y = 'tbl_basic_inf_hol';
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt_' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }


});
$("#tbl_basic_inf_hol tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_basic_inf_hol').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});
/*-------------- запросы-отпуска ----------------*/

/*-------------- запросы-командировка ----------------*/
$('#tbl_basic_inf_trip tfoot th').each( function (i) {
     var table = $('#tbl_basic_inf_trip').DataTable();

        if((i == 0)||(i == 2) ||(i == 8)  ){
     //выпадающий список
     var y='tbl_basic_inf_trip';
                var select = $('<select class="' + i + '  noprint"  id="sel_' + y + i + '"><option value=""></option></select>')
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
            var title = $('#tbl_basic_inf_trip tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_basic_inf_trip tfoot th').index($(this));
        var y = 'tbl_basic_inf_trip';
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt_' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }


});
$("#tbl_basic_inf_trip tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_basic_inf_trip').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});
/*-------------- КОНЕЦ запросы-командировка ----------------*/

/*-------------- запросы-др.причины ----------------*/
$('#tbl_basic_inf_other tfoot th').each( function (i) {
     var table = $('#tbl_basic_inf_other').DataTable();

        if((i == 0)||(i == 2)  ){
     //выпадающий список
     var y='tbl_basic_inf_other';
                var select = $('<select class="' + i + '  noprint"  id="sel_' + y + i + '"><option value=""></option></select>')
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
            var title = $('#tbl_basic_inf_other tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_basic_inf_other tfoot th').index($(this));
        var y = 'tbl_basic_inf_other';
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt_' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        }
});
$("#tbl_basic_inf_other tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_basic_inf_other').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});
/*-------------- КОНЕЦ запросы-др.причины ----------------*/


/*-------------- открыть доступ на ред списка смен ----------------*/
$('#tbl_user_open_listfio tfoot th').each( function (i) {
     var table = $('#tbl_user_open_listfio').DataTable();

            var title = $('#tbl_user_open_listfio tfoot th').eq( $(this).index() ).text();
        var x = $('#tbl_user_open_listfio tfoot th').index($(this));
        var y = 'tbl_user_open_listfio';
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt_' + y + x + '" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');


});
$("#tbl_user_open_listfio tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_user_open_listfio').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});

/*-------------- КОНЕЦ открыть доступ на ред списка смен ----------------*/


/*--------------------- report table count position -------------------------*/
$('#tbl_count_position tfoot th').each( function (i) {
     var table = $('#tbl_count_position').DataTable();

      //  if((i !== 0)){

            var title = $('#tbl_count_position tfoot th').eq( $(this).index() ).text();
      //  var x = $('#tbl_count_position tfoot th').index($(this));
        //var y = 1;
        //$(this).html( '<input type="text" placeholder="Поиск '+title+'" />' );
        $(this).html('<input type="text" class="noprint" id="inpt" placeholder="Поиск"  />');
       // document.getElementById("inpt11").html('placeholder="<i class="fa fa-search" aria-hidden="true"></i>"');
        //}


});
$("#tbl_count_position tfoot input").on( 'keyup change', function () {
       var table = $('#tbl_count_position').DataTable();
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
});
/*-------------- END report table count position  ----------------*/


});

/*----- свернуть/развернуть меню -----*/
function hideLeftmenu(){

  var a=$('#leftmenu-offcanvas').css("display");
  if(a == 'none'){

     $('#content-center').css({
      "margin-left": "16.66666667%"
  });
  $('#leftmenu-offcanvas').css({
      "display":"block"
  });
  }
  else{
       $('#content-center').css({
      "margin-left": "0%"
  });
    $('#leftmenu-offcanvas').css({
      "display":"none"
  });
  }
}




/**** поле № приказа  разрешено только буквы - . , цифры *****/
$('.prikaz_number').keypress(function (key) {
    if ((key.charCode < 48 && key.charCode !== 45 && key.charCode !== 44 && key.charCode !== 46 && key.charCode !== 32 && key.charCode !== 47) || (key.charCode > 57 &&  key.charCode < 1072 ))
        return false;
});


//  валидация формы добавления командировки на 5 работников
$('#formFillTrip')
        .bootstrapValidator({
            message: 'This value is not valid',
            //live: 'submitted',
//                feedbackIcons: {
//                    valid: 'fab fa-adn',
//                    invalid: 'fab fa-adn',
//                    validating: 'fab fa-address-car'
//                },
            fields: {
                prikaz_number1: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },

                                prikaz_number2: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                prikaz_number3: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                prikaz_number4: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                                prikaz_number5: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                             prikaz_number6: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                             prikaz_number7: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                             prikaz_number8: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                             prikaz_number9: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                                             prikaz_number10: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Введите №'
                        }

                    }
                },
                prikaz_date1: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date2: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date3: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date4: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date5: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date6: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date7: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date8: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date9: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                },
                                prikaz_date10: {
                    validators: {
                        notEmpty: {
                            message: 'Выберите дату '
                        },
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Неправильный формат'
                        }
                    }
                }

            }

        });
$('#prikaz_date1').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date1');
});
$('#prikaz_date2').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date2');
});
$('#prikaz_date3').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date3');
});
$('#prikaz_date4').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date4');
});
$('#prikaz_date5').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date5');
});
$('#prikaz_date6').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date6');
});
$('#prikaz_date7').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date7');
});
$('#prikaz_date8').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date8');
});
$('#prikaz_date9').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date9');
});
$('#prikaz_date10').on('dp.change dp.show', function (e) {
    $('#formFillTrip').bootstrapValidator('revalidateField', 'prikaz_date10');
});




/*--------------------  MULTIPLE DROPDOWN with select2 ----------------------*/

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});