$(document).ready(function(){
    $('#estacion').change(event => {
        alert("hola mundo desde JQuery");
        //var estacion = $(this).val();
        alert($('select[name=estacion]').val());
    });
    
      $("select[name='estacion']").change(function(){

      var id_country = $(this).val();
      console.log(id_country);
      var token = $("input[name='_token']").val();
      console.log(token);
      $.ajax({

          url: "<?php echo route('select-ajax') ?>",

          method: 'POST',

          data: {id_country:id_country, _token:token},
          
          success: function(data) {

            $("select[name='id_area_estacion'").html('');

            $("select[name='id_area_estacion'").html(data.options);

          }

      });
      console.log(data);
      })
});

