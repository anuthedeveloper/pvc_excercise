<?php
require_once ( dirname(__DIR__) . '/settings.php' );
if ( isset($_POST['state']) && isset($_POST['lga']) && isset($_POST['ward']) ) {
  $data = '';
  $state = $_POST['state'];
  $lga = $_POST['lga'];
  $ward = $_POST['ward'];
  if ( $puls = getPul($state, $lga, $ward) ) {
      $data .= '<option value="" selected hidden disabled>Choose Polling Unit Location</option>';
      foreach ($puls as $row) {
        $data .= '<option value="'.$row['pul'].'">'.strtoupper($row['pul']).'</option>';
      }
      unset($puls);
  }else{
    $data .= '<option value="" selected hidden disabled>No Polling Unit Location Found</option>';
  }
    echo json_encode($data);
}
