// function getPul(){
//   var state = $('#state').val();
//   var lga = $('#lga').val();
//   var ward = $('#ward').val();
//   if (state && lga && ward) {
//     $.ajax({
//       url: 'handlesajax/pul.php',
//       method: 'POST',
//       data: {
//         'state': state,
//         'lga': lga,
//         'ward': ward
//       },
//       dataType: 'JSON',
//       success:function(data){
//         $('#pul').html(data);
//       }
//     });
//   }
// }


;(function($){
  $('#ward').on('change', function(){
    var state = $('#state').val();
    var lga = $('#lga').val();
    var ward = $('#ward').val();
    if (state && lga && ward) {
      $.ajax({
        url: 'handlesajax/pul.php',
        method: 'POST',
        data: {
          'state': state,
          'lga': lga,
          'ward': ward
        },
        dataType: 'JSON',
        success:function(data){
          $('#pul').html(data);
        }
      });
    }
  });
})(jQuery);
