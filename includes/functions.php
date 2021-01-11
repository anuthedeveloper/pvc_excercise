<?php
/**
 * Load the database class file and instantiate the `$db` global.
 *
 *
 * @global db $db The WebApp database class.
 */
function require_db() {
	global $db;

	/** The name of the database for webpdo */
	define('DB_NAME', 'pvc');

	/** MySQL database username */
	define('DB_USER', 'root');

	/** MySQL database password */
	define('DB_PASSWORD', '');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

	require_once( ABSPATH . INC. '/db.php');

	if ( isset($db) )
		return;

	$db = new VotersDB(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
}


function getStates(){
	global $db;

	if ( $results = $db->query("SELECT * FROM states") ) {
		return $results;
	}

	return false;
}

function getLga( $state ){
	global $db;

	if (empty($state)) {
		return false;
	}

	if ( $results = $db->query("SELECT DISTINCT lga FROM metadata WHERE state = :state", array('state' => $state)) ) {
		return $results;
	}

	return false;
}

function getWard( $state, $lga ){
	global $db;

	if (empty($state) || empty($lga)) {
		return false;
	}

	if ( $results = $db->query("SELECT DISTINCT ward FROM metadata WHERE state = :state AND lga = :lga", array('state' => $state, 'lga' => $lga)) ) {
		return $results;
	}

	return false;
}

function getPul( $state, $lga, $ward ){
	global $db;

	if (empty($state) || empty($lga) || empty($ward)) {
		return false;
	}

	if ( $results = $db->query("SELECT DISTINCT pul FROM metadata WHERE state = :state AND lga = :lga AND ward = :ward",
	array('state' => $state, 'lga' => $lga, 'ward' => $ward)) ) {
		return $results;
	}

	return false;
}


function display_errors( $args = array() ){

	$results = '';
	if ( !is_array( $args) ) {
		return;
	}

	$json = json_encode($args);

	$response = json_decode($json);

	if ( !empty($response->errors) ) {
		$results = '<div class="alert  alert-danger">'.$response->errors.'</div>';
	}
	if ( !empty($response->success) ) {
		$results = '<div class="alert  alert-success">'.$response->success.'</div>';
	}

	if ( !empty($response->info) ) {
		$results = '<div class="alert  alert-info">'.$response->info.'</div>';
	}
	if ( !empty($response->warning) ) {
		$results = '<div class="alert  alert-warning">'.$response->warning.'</div>';
	}

	echo $results;
}

/**
 * Verifies that a phoneno is valid.
 *
 *
 * @param string $phoneno Phone number to verify.
 * @return string|bool Either false or the valid phone number.
 */
function is_phone( $phoneno ) {
	// if start form 0 then count 11
	if ( preg_match('/[0-9]/', $phoneno) && strlen($phoneno) == 11 ) {
		return $phoneno;
	}
	// if '+' is added then count 14
	if ( preg_match('/[+0-9]/', $phoneno) && strlen($phoneno) == 14 ) {
		return $phoneno;
	}

	if ( ! validate_phone_no( $phoneno ) ){
		return false;
	}

}

/**
* Phone number validator
*
* This function validates Nigerian phone numbers with the country code
*
*
* @param type $input phone number gotten from user
* @return type returns true
*/
function validate_phone_no( $input ) {

    if(preg_match('/^234[0-9]{11}/',$input)){
        return true;
    }

}

/**
 * Inserting into db::voters
 *
 */
function addData( $params ){
	global $db;

	//Array ( [firstname] => [lastname] => [phoneno] => [email] => [gender] => [occupation] => [state] => [lga] => [ward] => [pul] => [observer])
	if ( !$params['type'] || !$params['firstname'] || !$params['lastname'] || !$params['phoneno'] || !$params['email'] || !$params['gender'] || !$params['occupation']
	|| !$params['state'] || !$params['lga'] || !$params['ward'] || !$params['pul'] || !$params['observer'] ) {
		$response = array('errors' => 'All fields are required');
	}elseif ( !filter_var($params['email'], FILTER_VALIDATE_EMAIL) ) {
		$response = array('errors' => 'Please, enter a valid email address');
	}elseif ( !is_phone($params['phoneno']) ) {
		$response = array('errors' => 'Please, enter a valid phone number');
	}else{
		// try submtting data
		try {
			$mobileno = "";
			if ( !empty($params['mobileno']) ) {
				if( is_phone($params['mobileno']) ){
					$mobileno = $params['mobileno'];
				}else{
					throw new Exception("Phone number 2 must be valid", 1);
				}
			}
			$registered_date = date('Y-m-d H:i:sa');
			if(	$db->insert("voters", array(':type', ':firstname', ':lastname', ':phoneno', ':mobileno', ':email', ':gender', ':occupation', ':state', ':lga', ':ward', ':pu', ':observer', ':registered_date'),
				array('type' => $params['type'], 'firstname' => $params['firstname'], 'lastname' => $params['lastname'], 'phoneno' => $params['phoneno'], 'mobileno' => $mobileno, 'email' => $params['email'], 'gender' => $params['gender'], 'occupation' => $params['occupation'],
				'state' => $params['state'], 'lga' => $params['lga'], 'ward' => $params['ward'], 'pu' => $params['pul'], 'observer' => $params['observer'], 'registered_date' => $registered_date) ) ) {
					$response = array('success' => 'Submitted successfully');
				}else{
					throw new Exception("Error Submitting Data", 1);
				}
			} catch (Exception $e) {
				$response = array('errors' => $e->getMessage());
			}
	}

	return $response;
}
