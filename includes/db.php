<?php
/**
 *  DB - A simple database class
 *
 *
 */
if( !class_exists('VotersDB')) {

	class VotersDB {
      /**
       * List of WebApp tables
       *
       * @access private
       * @see db::tables()
       * @var array
       */
      var $tables = array( 'voters', 'metadata', 'states', 'lgas', 'ward' );

  	  /**
       * Whether to suppress errors during the DB bootstrapping.
       *
       * @access private
       * @var bool
       */
      var $suppress_errors = false;

  	/**
       * Database Username
       *
       * @access protected
       * @var string
       */
      protected $dbuser;

      /**
       * Database Password
       *
       * @access protected
       * @var string
       */
      protected $dbpassword;

      /**
       * Database Name
       *
       * @access protected
       * @var string
       */
      protected $dbname;

      /**
       * Database Host
       *
       * @access protected
       * @var string
       */
      protected $dbhost;

      /**
       * Database Handle
       *
       * @access protected
       * @var string
       */
      protected $dbh;

      /**
       * Handles any error
       *
       * @access protected
       * @var string
       */
      protected $error;

  	/**
       * Holds statement.
       *
       * @object, PDO statement object
       * @access private
       * @var array|null
       */
      private $stmt;

  	/**
       * Whether to use pdo over pdo_mysql.
       *
       * @access private
       * @var bool
       */
      private $use_pdo = false;

      /**
       * Whether we've managed to successfully connect at some point
       *
       * @bool ,  Connected to the dbname
       * @access private
       * @var bool
       */
  	private $has_connected = false;

  	/**
       * parameters that should executed
       *
       * @array, The parameters of the SQL query
       * @access private
       * @var array
       */
  	private $parameters;

      /**
       * Whether MySQL is used as the database engine.
       *
       * Set in db::db_coonect() to true, by default. This is used when checking
       * against the required MySQL version for thetruth. Normally, a replacement
       * database drop-in (db.php) will skip these checks, but setting this to true
       * will force the checks to occur.
       *
       * @access public
       * @var bool
       */
  	private $is_pdo = null;

  	/**
     * Database table columns charset
     *
     * @access public
     * @var string
     */
    public $charset;


    # @object, Object for logging exceptions
    private $log;

    # @object, The PDO object
    private $pdo;

    # @array,  The dbname settings
    private $settings;

    /**
		*   Default Constructor
		*
		*	1. Instantiate Log class.
		*	2. db_coonect to database.
		*	3. Creates the parameter array.
		*/
		public function __construct($dbhost, $dbname, $dbuser, $dbpassword)
		{

			if ( function_exists('pdo') ) {
                $this->use_pdo = true;
            }

			$this->dbuser = $dbuser;
            $this->dbpassword = $dbpassword;
            $this->dbname = $dbname;
            $this->dbhost = $dbhost;



			$this->db_coonect($dbhost, $dbname, $dbuser, $dbpassword);
			$this->parameters = array();
		}

  	/**
		*	This method makes connection to the database.
		*
		*	1. Reads the database settings from a ini file.
		*	2. Puts  the ini content into the settings array.
		*	3. Tries to connect to the database.
		*	4. If connection failed, exception is displayed and a log file gets created.
		*/
		private function db_coonect($dbhost, $dbname, $dbuser, $dbpassword)
		{
			global $settings;
			$dsn = 'mysql:dbname='.$dbname.';host='.$dbhost;
			try
			{
				# Read settings from INI file, set UTF8
				$this->pdo = new PDO($dsn, $dbuser, $dbpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

				# We can now log any exceptions on Fatal error.
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				# Disable emulation of prepared statements, use REAL prepared statements instead.
				$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

				# db_coonection succeeded, set the boolean to true.
				$this->has_connected = true;
			}
			catch (PDOException $e)
			{
				$this->error = $e->getMessage();
				die();
			}
		}

		/**
		 *   You can use this little method if you want to close the PDO connection
		 *
		 */
	 	public function __destruct() {
	 		# Set the PDO object to null to close the connection
	 		# https://www.php.net/manual/en/pdo.connections.php
	 		$this->pdo = null;
	 	}


  	/**
		*	Every method which needs to execute a SQL query uses this method.
		*
		*	1. If not connected, connect to the database.
		*	2. Prepare Query.
		*	3. Parameterize Query.
		*	4. Execute Query.
		*	5. On exception : Write Exception into the log + SQL query.
		*	6. Reset the Parameters.
		*/
		private function prepare($query,$parameters = "")
		{
			# db_coonect to database
			if(! $this->has_connected) {
				$this->db_coonect();
			}

			try {

				# Prepare query
				$this->stmt = $this->pdo->prepare($query);

				# Add parameters to the parameter array
				$this->bindMore($parameters);

				# Bind parameters
				if(!empty($this->parameters)) {
					foreach($this->parameters as $param)
					{
						$parameters = explode("\x7F",$param);
						$this->stmt->bindParam($parameters[0],$parameters[1]);
					}
				}

				# Execute SQL
				$this->success = $this->stmt->execute();

			} catch(PDOException $e) {

				# Write into log and display Exception
				$e->getMessage(). $query;
			}

			# Reset the parameters
			$this->parameters = array();
		}

       /**
		*	@void
		*
		*	Add the parameter to the parameter array
		*	@param string $para
		*	@param string $value
		*/
		public function bind($para, $value)
		{
			$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
		}

       /**
		*	@void
		*
		*	Add more parameters to the parameter array
		*	@param array $parray
		*/
		public function bindMore($parray)
		{
			if(empty($this->parameters) && is_array($parray)) {
				$columns = array_keys($parray);
				foreach($columns as $i => &$column)	{
					$this->bind($column, $parray[$column]);
				}
			}
		}

	    /**
		*   	If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
		*	If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
		*
		*   	@param  string $query
		*	@param  array  $params
		*	@param  int    $fetchmode
		*	@return mixed
		*/
		public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
		{
			$query = trim($query);

			$this->prepare($query,$params);

			$rawStatement = explode(" ", $query);

			# Which SQL statement is used
			$statement = strtolower($rawStatement[0]);

			if ($statement === 'select' || $statement === 'show') {
				return $this->stmt->fetchAll($fetchmode);
			}
			elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
				return $this->stmt->rowCount();
			}
			else {
				return NULL;
			}
		}

     /**
     * Insert a row into a table.
     *
     *  db::insert('table', array(':placehoder1', ':plceholder2') )
     *  db::insert('table', array(':placehoder1', ':plceholder2'), array( 'placeholder1' => 'foo', 'placeholder2' => 1337) )
     *  $query = "INSERT INTO table(Firstname,Age) VALUES(:f,:age)", array("f"=>"Vivek","age"=>"20")
     *
     * @param string       $table  Table name
     * @param array        $placeholders for VALUES to insert array( ':placehoder1', ':plceholder2')
     * @param array|string $placeholders Optional. An array of placeholders to be mapped to each of the value in $params.
     * @param array associative array $params params = array( 'params' => 'value' )
     * @return int|false The number of rows inserted, or false on error.
     */
    public function insert( $table, $fields, $params ) {

        foreach ($fields as $field) {
            $placeholders = ':'.$field;
        }

        $values = implode(', ', $fields);
        $data = str_replace(':', '', $values);

        //construct query for binding
        $sql = "INSERT INTO {$table} ({$data}) VALUES ({$values})";

        $query = trim( $sql );

        $this->prepare( $query, $params );


        return $this->stmt->rowCount();
    }

	}
}
?>
