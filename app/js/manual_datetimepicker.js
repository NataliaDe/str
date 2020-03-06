/** Все, что связано с датой и временем, datetimepicker **/



/*---------- Основание командирования - дата приказа -----------*/
function getPrikazDate(j) {

    $('#prikaz_date' + j).datetimepicker({
        language: 'ru',
        pickTime: false,
        defaultDate: new Date(),
        format: 'DD-MM-YYYY'
    });

}
/*---------- eND Основание командирования - дата приказа -----------*/




/**** поле дата  разрешено только - и цифры *****/
$('.date').keypress(function (key) {
    if ((key.charCode < 48 && key.charCode !== 45) || (key.charCode > 57))
        return false;
});



//datepicker

$(function () {
    /* дата заступления смены - доступна только сег.дата  */
      $('#dateduty').datetimepicker({
              language: 'ru',
         pickTime: false,
          autoclose: true,
          format: 'DD-MM-YYYY',
     	'minDate': new Date(),
        'maxDate': new Date()
        /*доступна только сег.дата+3day
	'maxDate':  moment(new Date()).add(2, 'days').startOf('day') */
    });


    $('#date_single_report').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY',
        'minDate': moment(new Date()).add(-2, 'days').startOf('day'),
	'maxDate':  new Date()
                //moment(new Date()).add(2, 'days').startOf('day')
    });


   /* дата в запроснике - диапазон дат */
       $('#date_start').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY',
        'minDate': moment(new Date()).add(-2, 'days').startOf('day'),
	'maxDate':  new Date()
                //moment(new Date()).add(2, 'days').startOf('day')
    });
    $('#date_end').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY',
          'minDate': moment(new Date()).add(-2, 'days').startOf('day'),
          'maxDate':  new Date()
                  //moment(new Date()).add(2, 'days').startOf('day')
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date_start').on("dp.change", function (e) {
        $('#date_end').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date_end').on("dp.change", function (e) {
        $('#date_start').data("DateTimePicker").setMaxDate(e.date);
    });


   /* техника в командировке - форма добавления*/
     $('#date1').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date2').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date1').on("dp.change", function (e) {
        $('#date2').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date2').on("dp.change", function (e) {
        $('#date1').data("DateTimePicker").setMaxDate(e.date);
    });
       /* техника в командировке - форма редактирования */
        $('#trip_car_date1').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#trip_car_date2').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#trip_car_date1').on("dp.change", function (e) {
        $('#trip_car_date2').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#trip_car_date2').on("dp.change", function (e) {
        $('#trip_car_date1').data("DateTimePicker").setMaxDate(e.date);
    });


   /* диапазон дат для отсутствующих */
    $('#date11').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date21').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date11').on("dp.change", function (e) {
        $('#date21').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date21').on("dp.change", function (e) {
        $('#date11').data("DateTimePicker").setMaxDate(e.date);
    });

     $('#date12').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date22').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date12').on("dp.change", function (e) {
        $('#date22').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date22').on("dp.change", function (e) {
        $('#date12').data("DateTimePicker").setMaxDate(e.date);
    });

     $('#date13').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date23').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date13').on("dp.change", function (e) {
        $('#date23').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date23').on("dp.change", function (e) {
        $('#date13').data("DateTimePicker").setMaxDate(e.date);
    });

     $('#date14').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date24').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date14').on("dp.change", function (e) {
        $('#date24').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date24').on("dp.change", function (e) {
        $('#date14').data("DateTimePicker").setMaxDate(e.date);
    });


 $('#date15').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date25').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date15').on("dp.change", function (e) {
        $('#date25').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date25').on("dp.change", function (e) {
        $('#date15').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date16').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date26').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date16').on("dp.change", function (e) {
        $('#date26').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date26').on("dp.change", function (e) {
        $('#date16').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date17').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date27').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date17').on("dp.change", function (e) {
        $('#date27').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date27').on("dp.change", function (e) {
        $('#date17').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date18').datetimepicker({
        language: 'ru',
        pickTime: false,
     autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date28').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date18').on("dp.change", function (e) {
        $('#date28').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date28').on("dp.change", function (e) {
        $('#date18').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date19').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date29').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date19').on("dp.change", function (e) {
        $('#date29').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date29').on("dp.change", function (e) {
        $('#date19').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date110').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date210').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date110').on("dp.change", function (e) {
        $('#date210').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date210').on("dp.change", function (e) {
        $('#date110').data("DateTimePicker").setMaxDate(e.date);
    });



     $('#date111').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date211').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date111').on("dp.change", function (e) {
        $('#date211').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date211').on("dp.change", function (e) {
        $('#date111').data("DateTimePicker").setMaxDate(e.date);
    });



     $('#date112').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date212').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date112').on("dp.change", function (e) {
        $('#date212').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date212').on("dp.change", function (e) {
        $('#date112').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date113').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date213').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date113').on("dp.change", function (e) {
        $('#date213').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date213').on("dp.change", function (e) {
        $('#date113').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date114').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date214').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date114').on("dp.change", function (e) {
        $('#date214').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date214').on("dp.change", function (e) {
        $('#date114').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date115').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date215').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date115').on("dp.change", function (e) {
        $('#date215').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date215').on("dp.change", function (e) {
        $('#date115').data("DateTimePicker").setMaxDate(e.date);
    });



     $('#date116').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date216').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date116').on("dp.change", function (e) {
        $('#date216').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date216').on("dp.change", function (e) {
        $('#date116').data("DateTimePicker").setMaxDate(e.date);
    });


     $('#date117').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date217').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date117').on("dp.change", function (e) {
        $('#date217').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date217').on("dp.change", function (e) {
        $('#date117').data("DateTimePicker").setMaxDate(e.date);
    });


    	     $('#date118').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date218').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date118').on("dp.change", function (e) {
        $('#date218').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date218').on("dp.change", function (e) {
        $('#date118').data("DateTimePicker").setMaxDate(e.date);
    });


    	     $('#date119').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date219').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date119').on("dp.change", function (e) {
        $('#date219').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date219').on("dp.change", function (e) {
        $('#date119').data("DateTimePicker").setMaxDate(e.date);
    });


    	     $('#date120').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date220').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date120').on("dp.change", function (e) {
        $('#date220').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date220').on("dp.change", function (e) {
        $('#date120').data("DateTimePicker").setMaxDate(e.date);
    });

         $('#date121').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date221').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date121').on("dp.change", function (e) {
        $('#date221').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date221').on("dp.change", function (e) {
        $('#date121').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date122').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date222').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date122').on("dp.change", function (e) {
        $('#date222').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date222').on("dp.change", function (e) {
        $('#date122').data("DateTimePicker").setMaxDate(e.date);
    });

             $('#date123').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date223').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date123').on("dp.change", function (e) {
        $('#date223').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date223').on("dp.change", function (e) {
        $('#date123').data("DateTimePicker").setMaxDate(e.date);
    });

             $('#date124').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date224').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date124').on("dp.change", function (e) {
        $('#date224').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date224').on("dp.change", function (e) {
        $('#date124').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date125').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date225').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date125').on("dp.change", function (e) {
        $('#date225').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date225').on("dp.change", function (e) {
        $('#date125').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date126').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date226').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date126').on("dp.change", function (e) {
        $('#date226').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date226').on("dp.change", function (e) {
        $('#date126').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date127').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date227').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date127').on("dp.change", function (e) {
        $('#date227').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date227').on("dp.change", function (e) {
        $('#date127').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date128').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date228').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date128').on("dp.change", function (e) {
        $('#date228').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date228').on("dp.change", function (e) {
        $('#date128').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date129').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date229').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date129').on("dp.change", function (e) {
        $('#date229').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date229').on("dp.change", function (e) {
        $('#date129').data("DateTimePicker").setMaxDate(e.date);
    });


             $('#date130').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date230').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date130').on("dp.change", function (e) {
        $('#date230').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date230').on("dp.change", function (e) {
        $('#date130').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date131').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date231').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date131').on("dp.change", function (e) {
        $('#date231').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date231').on("dp.change", function (e) {
        $('#date131').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date132').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date232').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date132').on("dp.change", function (e) {
        $('#date232').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date232').on("dp.change", function (e) {
        $('#date132').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date133').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date233').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date133').on("dp.change", function (e) {
        $('#date233').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date233').on("dp.change", function (e) {
        $('#date133').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date134').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date234').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date134').on("dp.change", function (e) {
        $('#date234').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date234').on("dp.change", function (e) {
        $('#date134').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date135').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date235').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date135').on("dp.change", function (e) {
        $('#date235').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date235').on("dp.change", function (e) {
        $('#date135').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date136').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date236').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date136').on("dp.change", function (e) {
        $('#date236').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date236').on("dp.change", function (e) {
        $('#date136').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date137').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date237').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date137').on("dp.change", function (e) {
        $('#date237').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date237').on("dp.change", function (e) {
        $('#date137').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date138').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date238').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date138').on("dp.change", function (e) {
        $('#date238').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date238').on("dp.change", function (e) {
        $('#date138').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date139').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date239').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date139').on("dp.change", function (e) {
        $('#date239').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date239').on("dp.change", function (e) {
        $('#date139').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date140').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date240').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date140').on("dp.change", function (e) {
        $('#date240').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date240').on("dp.change", function (e) {
        $('#date140').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date141').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date241').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date141').on("dp.change", function (e) {
        $('#date241').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date241').on("dp.change", function (e) {
        $('#date141').data("DateTimePicker").setMaxDate(e.date);
    });




         $('#date142').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date242').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date142').on("dp.change", function (e) {
        $('#date242').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date242').on("dp.change", function (e) {
        $('#date142').data("DateTimePicker").setMaxDate(e.date);
    });




         $('#date143').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date243').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date143').on("dp.change", function (e) {
        $('#date243').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date243').on("dp.change", function (e) {
        $('#date143').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date144').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date244').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date144').on("dp.change", function (e) {
        $('#date244').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date244').on("dp.change", function (e) {
        $('#date144').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date145').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date245').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date145').on("dp.change", function (e) {
        $('#date245').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date245').on("dp.change", function (e) {
        $('#date145').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date146').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date246').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date146').on("dp.change", function (e) {
        $('#date246').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date246').on("dp.change", function (e) {
        $('#date146').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date147').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date247').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date147').on("dp.change", function (e) {
        $('#date247').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date247').on("dp.change", function (e) {
        $('#date147').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date148').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date248').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date148').on("dp.change", function (e) {
        $('#date248').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date248').on("dp.change", function (e) {
        $('#date148').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date149').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date249').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date149').on("dp.change", function (e) {
        $('#date249').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date249').on("dp.change", function (e) {
        $('#date149').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date150').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date250').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date150').on("dp.change", function (e) {
        $('#date250').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date250').on("dp.change", function (e) {
        $('#date150').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date151').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date251').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date151').on("dp.change", function (e) {
        $('#date251').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date251').on("dp.change", function (e) {
        $('#date151').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date152').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date252').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date152').on("dp.change", function (e) {
        $('#date252').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date252').on("dp.change", function (e) {
        $('#date152').data("DateTimePicker").setMaxDate(e.date);
    });



         $('#date153').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date253').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date153').on("dp.change", function (e) {
        $('#date253').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date253').on("dp.change", function (e) {
        $('#date153').data("DateTimePicker").setMaxDate(e.date);
    });



     $('#date154').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date254').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date154').on("dp.change", function (e) {
        $('#date254').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date254').on("dp.change", function (e) {
        $('#date154').data("DateTimePicker").setMaxDate(e.date);
    });


         $('#date155').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    $('#date255').datetimepicker({
        language: 'ru',
        pickTime: false,
         autoclose: true,
          format: 'DD-MM-YYYY'
    });
    //При изменении даты в 8 datetimepicker, она устанавливается как минимальная для 9 datetimepicker
    $('#date155').on("dp.change", function (e) {
        $('#date255').data("DateTimePicker").setMinDate(e.date);
    });
    //При изменении даты в 9 datetimepicker, она устанавливается как максимальная для 8 datetimepicker
    $('#date255').on("dp.change", function (e) {
        $('#date155').data("DateTimePicker").setMaxDate(e.date);
    });


    $('#date_for_search_by_fio').datetimepicker({
        language: 'ru',
        pickTime: false,
        autoclose: true,
        format: 'DD-MM-YYYY'
    });

});