// function getWard(){
//   var state = $('#state').val();
//   var lga = $('#lga').val();
//   if (state && lga) {
//     $.ajax({
//       url: 'handlesajax/ward.php',
//       method: 'POST',
//       data: {
//         'state': state,
//         'lga': lga
//       },
//       dataType: 'JSON',
//       success:function(data){
//         $('#ward').html(data);
//       }
//     });
//   }
// }

;(function($){
  $('#lga').on('change', function(){
    var state = $('#state').val();
    var lga = $('#lga').val();
    if (state && lga) {
      $.ajax({
        url: 'handlesajax/ward.php',
        method: 'POST',
        data: {
          'state': state,
          'lga': lga
        },
        dataType: 'JSON',
        success:function(data){
          $('#ward').html(data);
        }
      });
    }
  });
})(jQuery);
