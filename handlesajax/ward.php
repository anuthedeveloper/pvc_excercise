<?php
require_once ( dirname(__DIR__) . '/settings.php' );
if ( isset($_POST['state']) && isset($_POST['lga']) ) {
  $data = '';
  $state = $_POST['state'];
  $lga = $_POST['lga'];
  if ( $wards = getWard($state, $lga) ) {
    //if( $results ) {
      $data .= '<option value="" selected hidden disabled>Choose Ward</option>';
      foreach ($wards as $row) {
        $data .= '<option value="'.$row['ward'].'">'.strtoupper($row['ward']).'</option>';
      }
  }else{
    $data .= '<option value="" selected hidden disabled>No Ward Found</option>';
  }
    echo json_encode($data);
}
