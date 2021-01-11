<?php require_once ( dirname(__FILE__) . '/settings.php' ); ?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PVC</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>

    <!-- Right Panel -->

    <div id="" class="d-flex align-content-center flex-wrap">
        <?php if ( isset($_POST['submit-data']) ):
          $results = addData( $_POST );
        endif; ?>
        <!-- <div class="content mt-3"> -->
            <div class="container animated fadeIn mt-5 col-lg-6">

                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">PVC</strong>
                        </div>
                        <div class="card-body">
                            <!-- <div class="card-title">
                                <h3 class="text-center"></h3>
                            </div> -->
                            <?php display_errors($results); ?>
                            <form action="" method="post" novalidate="novalidate">
                                <div class="form-group text-center">
                                    <ul class="list-inline">
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label mb-1">Choose form type </label>
                                    <select class="form-control" name="type">
                                      <option value="" selected >-- Please, select form type</option>
                                      <option value="New" >First time applicant</option>
                                      <option value="Not Received">Registered to vote but did not receive PVC</option>
                                      <option value="Lost">Received PVC but misplaced it</option>
                                    </select>
                                    <!-- <span class="help-block" data-valmsg-for="observer" data-valmsg-replace="true"></span> -->
                                </div>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="firstname" class="control-label mb-1">First Name</label>
                                        <input id="firstname" name="firstname" type="text" class="form-control" aria-required="true" aria-invalid="false" placeholder="First Name">
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="lastname" class="control-label mb-1">Last Name</label>
                                        <input id="lastname" name="lastname" type="text" class="form-control" aria-required="true" aria-invalid="false" placeholder="Last Name">
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phoneno" class="control-label mb-1">Phone Number</label>
                                        <input id="phoneno" name="phoneno" type="text" class="form-control" aria-required="true" aria-invalid="false" placeholder="Phone Number">
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="mobileno" class="control-label mb-1">Phone Number 2</label>
                                        <input id="mobileno" name="mobileno" type="text" class="form-control" aria-required="true" aria-invalid="false" placeholder="Phone Number 2">
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label mb-1">Email Address</label>
                                    <input id="email" name="email" type="text" class="form-control" aria-required="true" aria-invalid="false" placeholder="Email Address">
                                </div>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="gender" class="control-label mb-1">Gender</label>
                                        <select id="gender" name="gender" class="form-control" aria-required="true" aria-invalid="false" >
                                          <option value="" selected >-- Gender</option>
                                          <option value="Male">Male </option>
                                          <option value="Female">Female </option>
                                        </select>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="occupation" class="control-label mb-1">Occupation</label>
                                        <select class="form-control" id="occupation" name="occupation" >
                                          <option value="" selected >-- Select Occupation </option>
                                          <option value="Employed">Employed</option>
                                          <option value="Self-employed">Self Employed</option>
                                          <option value="Unemployed">Unemployed</option>
                                          <option value="Student">Student</option>
                                          <option value="Retired">Retired</option>
                                        </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="state" class="control-label mb-1">State</label>
                                        <select class="form-control" name="state" id="state" >
                                          <option value="" selected >-- Select State </option>
                                          <?php
                                          if ($states = getStates()){
                                            foreach ($states as $state) {
                                              echo '<option value="'.$state['state'].'">'.$state['state'].'</option>';
                                            }
                                          }
                                          ?>
                                        </select>
                                        <!-- <span class="help-block field-validation-valid" data-valmsg-for="state" data-valmsg-replace="true"></span> -->
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="lga" class="control-label mb-1">LGA</label>
                                        <select class="form-control" name="lga" id="lga">
                                          <option value="" selected >-- Select Local Govt. Area </option>
                                        </select>
                                        <!-- <span class="help-block" data-valmsg-for="lga" data-valmsg-replace="true"></span> -->
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="ward" class="control-label mb-1">Ward</label>
                                    <select class="form-control" name="ward" id="ward">
                                      <option value="" selected >-- Select Ward </option>
                                    </select>
                                    <!-- <span class="help-block field-validation-valid" data-valmsg-for="ward" data-valmsg-replace="true"></span> -->
                                </div>
                                <div class="form-group">
                                    <label for="pul" class="control-label mb-1">Polling Unit Location</label>
                                    <select class="form-control" name="pul" id="pul">
                                      <option value="" selected >-- Select Polling Unit Location </option>
                                    </select>
                                    <!-- <span class="help-block" data-valmsg-for="pul" data-valmsg-replace="true"></span> -->
                                </div>
                                <div class="form-group">
                                    <label for="observer" class="control-label mb-1">Do you wish to become an Observer?</label>
                                    <select class="form-control" name="observer" id="observer">
                                      <option value="" selected >-- Do you wish to become an Observer? </option>
                                      <option value="No" >No</option>
                                      <option value="Yes">Yes</option>
                                    </select>
                                    <!-- <span class="help-block" data-valmsg-for="observer" data-valmsg-replace="true"></span> -->
                                </div>

                                <div class="mt-4">
                                    <button id="submit-button" type="submit" name="submit-data" class="btn btn-lg btn-info btn-block">Submit
                                        <!-- <span id="submit-button-sending" style="display:none;">Sending…</span> -->
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div> <!-- .card -->




            <!-- </div>animated -->
        </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/custom/lga.js"></script>
    <script src="assets/js/custom/ward.js"></script>
    <script src="assets/js/custom/pul.js"></script>
</body>
</html>
