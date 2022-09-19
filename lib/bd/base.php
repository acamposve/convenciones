<?php
#=============================================================================
# lib/db/base.php
#		Database abstraction and OO framework
#
#		Version: 1.0
#		Author: Sebastian Delmont <sdelmont@loquesea.com>
#		        Maria Paula Herrero <mpaula@loquesea.com>
#		Date: 2000-04-21
#		License: this file is distributed under the BMW License
#		         Blame Microsoft for a While and you can use it.
#=============================================================================
if( defined( '_LIB_DB' )) {
	return;
}else{

define( '_LIB_DB', '1.0' );

#=============================================================================
# BaseDBConnection : class template for database connections
#=============================================================================
class BaseDBConnection {
	#-------------------------------------------------------------------------
	function setup( $url ) {
		# Defines the connection settings
		echo "*** Undefined base function 'DBConnection->setup' ***";
	}

	#-------------------------------------------------------------------------
	function open() {
		# Opens a connection to the database
		echo "*** Undefined base function 'DBConnection->open' ***";
	}

	#-------------------------------------------------------------------------
	function close() {
		# Closes the connection and frees all resources
		echo "*** Undefined base function 'DBConnection->close' ***";
	}

	#-------------------------------------------------------------------------
	function execute( $sql ) {
		# Executes the given SQL query and returns a DBResult object
		echo "*** Undefined base function 'DBConnection->execute' ***";
	}

	#-------------------------------------------------------------------------
	function beginTransaction() {
		# Marks the begginning of a transaction... all the following
		# DBConnection->execute() will be considered part of the transaction
		# Returns false if transactions are not supported or when
		# a transaction has already been started

		return FALSE;
	}

	#-------------------------------------------------------------------------
	function commit() {
		# Commits a pending transaction
		return FALSE;
	}

	#-------------------------------------------------------------------------
	function rollback() {
		# Rollbacks a pending transaction
		return FALSE;
	}

	#-------------------------------------------------------------------------
	function isInTransaction() {
		# Return's true if a transaction is pending
		return FALSE;
	}

	#-------------------------------------------------------------------------
	function getValue( $sql, $isLong = 0 ) {
		# Executes the given SQL query and returns the first field on
		# the first record
		if( $query = $this->execute( $sql )) {
			if( $query->next() ) {
				$val = $query->value( 0, $isLong );
			}
			$query->close();
		}

		return $val;
	}

	#-------------------------------------------------------------------------
	function getValues( $sql ) {
		# Executes the given SQL query and returns an array of all the fields
		# on the first record
		if( $query = $this->execute( $sql )) {
			if( $query->next() ) {
				$result = $query->getValues();
			}
			$query->close();
		}

		return $result;
	}

	#-------------------------------------------------------------------------
	function getAllValues( $sql ) {
		# Executes the given SQL query and returns an array of all the fields
		# on the first record
		if( $query = $this->execute( $sql )) {
			while( $query->next() ) {
				$result[] = $query->getValues();
			}
			$query->close();
		}

		return $result;
	}

	#-------------------------------------------------------------------------
	function lastIdentity() {
		# Returns the value of the 'sequence' field on the last record
		# inserted. Some databases don't support this feature
		echo "*** Undefined base function 'DBConnection->lastIdentity' ***";
	}

	#-------------------------------------------------------------------------
	function quoteDate( $date ) {
		# Returns a date surrounded by the appropiate delimiter characters
		# and in the appropiate format
		if( is_integer( $date )) {
			$date = $this->formatDate( $date );
		}

		return "'" . $date . "'";
	}

	#-------------------------------------------------------------------------
	function quoteString( $str, $noEscape = FALSE ) {
		# Returns a string surrounded by the appropiate delimiter characters
		# and in the appropiate format
		if( ! $noEscape )
			$str = $this->escapeString( $str );

		$str = "'" . $str . "'";

		if( preg_match( "/^(\'.*)\+\'\'$/", $str, $parts ))
			$str = $parts[ 1 ];

		return $str;
	}

	#-------------------------------------------------------------------------
	function formatDate( $date ) {
		# Takes a unix-style date integer and formats properly to be used on
		# an SQL query.
		return date( "M d Y H:i:s", $date );
	}

	#-------------------------------------------------------------------------
	function convertDate( $date ) {
		# Takes a date string as returned by the database and converts it to
		# a unix-style date integer.

		$parts = split( " ", $date, 2 );
		list( $year, $month, $day ) = split( "-", $parts[ 0 ], 3 );
		list( $hours, $minutes, $aux ) = split( ":", $parts[ 1 ], 3 );
		$secs = substr( $aux, 0, 2 );

		return mktime( $hours, $minutes, $secs, $month, $day, $year);
	}

	#-------------------------------------------------------------------------
	function escapeString( $str ) {
		# Escapes invalid characters in a string
		return $str;
	}

	#-------------------------------------------------------------------------
	function wildcardCharacter() {
		# Returs the character to be used as wildcard on LIKE clauses
		return '%';
	}

	#-------------------------------------------------------------------------
	function parsingSql( $sql ) {
		# Do the parsing for chars upper 128 ascii
		echo "*** Undefined base function 'DBConnection->parsingSql' ***";
	}


}

#=============================================================================
# BaseDBResult : class template for database query results
#=============================================================================
class BaseDBResult {
	#-------------------------------------------------------------------------
	function close() {
		# Frees all resources used by this result
		echo "*** Undefined base function 'DBResult->close' ***";
	}

	#-------------------------------------------------------------------------
	function value( $field, $isLong = 0 ) {
		# Returns a value from the current field, usually by field name
		# or position
		# The first field is in position 0
		echo "*** Undefined base function 'DBResult->value' ***";
	}

	#-------------------------------------------------------------------------
	function setVars( $prefix = "db_" ) {
		# Fetches all fields from the current record and stores them as global
		# variables, using the field name and given prefix as variable name
		echo "*** Undefined base function 'DBResult->setVars' ***";
	}

	#-------------------------------------------------------------------------
	function getValues() {
		# Returns an associative array with all the fields
		if( $fields = $this->fieldsInfo()) {
			$result = array();
			while( list( $pos, $data ) = each( $fields )) {
				if( $data[ 'type' ] == 'text' ) {
					$result[ $data[ 'name' ] ] =
									$this->value( $data[ 'name' ], TRUE );
				}
				else {
					$result[ $data[ 'name' ] ] =
									$this->value( $data[ 'name' ] );
				}
			}
		}

		return( $result );
	}

	#-------------------------------------------------------------------------
	function fieldsInfo() {
		# Returns an array containing, for each field, an associative array
		# with 'name', 'type', and 'size'
	}

	#-------------------------------------------------------------------------
	function next() {
		# Moves to the next record on the result
		# Returns false if EOF is reached
		echo "*** Undefined base function 'DBResult->next' ***";
	}

	#-------------------------------------------------------------------------
	function moveTo( $pos ) {
		# Moves to <$pos>th record on the result
		# Returns false if EOF is reached
		# The first record is in position 0
		echo "*** Undefined base function 'DBResult->moveTo' ***";
	}

	#-------------------------------------------------------------------------
	function count() {
		# Returns the number of records on the result
		# -1 if Unknown
		return -1;
	}

	#-------------------------------------------------------------------------
	function realCount() {
		# Returns the number of records on the result
		# If unknown (that is, count() returns -1), it will
		# count the records one by one
		# Since it has to move across the results, it will mess
		# with the current record position.
		# To be consistent, it will always left the position on the first
		# record
		# IMPORTANT!!!! USING THIS FUNCTION WILL LEAVE THE RECORD POSITION
		#               AT THE BEGINNING...

		return -1;
	}

	#-------------------------------------------------------------------------
	function eof() {
		# Returns true if we're beyond the last record on the result
		echo "*** Undefined base function 'DBResult->eof' ***";
	}

	#-------------------------------------------------------------------------
	function fieldNames() {
		# Returns an array with the names of the fields on the result
		# or null if not supported
	}
}


#=============================================================================

function NewDBConnection( $url ) {
	$urldata = parse_url( $url );
	$driver = strtoupper( $urldata[ 'scheme' ] );

	$class = $driver . "Connection";
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	$include = '../lib/bd/drivers/' . strtolower( $driver ) . '.php';
	$define = '_LIB_DB_DRIVERS_' . $driver;

	if( ! defined( $define )) {
		include( $include );
	}

	if( defined( $define )) {
		$db = new $class;

		if( $db ) {
			$db->setup( $url );
		}
	}

	return $db;
}
}

#=============================================================================
?>
