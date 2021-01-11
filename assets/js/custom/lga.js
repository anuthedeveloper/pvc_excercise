// function getLga(){
//   var state = $('#state').val();
//   if (state) {
//     $.ajax({
//       url: 'handlesajax/lga.php',
//       method: 'POST',
//       data: {
//         'state': state
//       },
//       dataType: 'JSON',
//       success:function(data){
//         $('#lga').html(data);
//       }
//     });
//     alert(state);
//   }
// }


;(function($){
  $('#state').on('change', function(){
    var state = $('#state').val();
    if (state) {
      $.ajax({
        url: 'handlesajax/lga.php',
        method: 'POST',
        data: {
          'state': state
        },
        dataType: 'JSON',
        success:function(data){
          $('#lga').html(data);
        }
      });
    }
  });
})(jQuery);
