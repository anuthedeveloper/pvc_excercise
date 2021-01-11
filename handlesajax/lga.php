<?php
require_once ( dirname(__DIR__) . '/settings.php' );
if (isset($_POST['state'])) {
  $data = '';
  $state = $_POST['state'];
  if ( $lgas = getLga($state) ) {
    //if( $results ) {
      $data .= '<option value="" selected hidden disabled>Choose LGA</option>';
      foreach ($lgas as $row) {
        $data .= '<option value="'.$row['lga'].'">'.strtoupper($row['lga']).'</option>';
      }
  }else{
    $data .= '<option value="" selected hidden disabled>No LGA Found</option>';
  }
    echo json_encode($data);
}
