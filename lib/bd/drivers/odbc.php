<?
#=============================================================================
# lib/db/drivers/odbc.php
#		ODBC Classes for lib/db
#
#		Version: 1.0
#		Author: Sebastian Delmont <sdelmont@loquesea.com>
#		        Maria Paula Herrero <mpaula@loquesea.com>
#		Date: 2000-04-21
#		License: this file is distributed under the BMW License
#		         Blame Microsoft for a While and you can use it.
#=============================================================================

if( ! defined( '_LIB_DB_DRIVERS_ODBC' )) {
	define( '_LIB_DB_DRIVERS_ODBC', '1.0' );

	#=========================================================================
	# ODBCConnection : Database connections using odbc functions
	#=========================================================================
	class ODBCConnection extends BaseDBConnection {
		var $connectionType = "ODBC";

		var $dataSource;
		var $user;
		var $password;
		var $cursorType;
		var $dbType;
		var $actualDateFunc = "getdate()";
		var $connectionID;
		
		var $transparentQuoting = true;
		
		#---------------------------------------------------------------------
		function setup( $url ) {
			$urldata = parse_url( $url );
		
			$this->dataSource = substr( $urldata[ 'path' ], 1 );
			$this->user = $urldata[ 'user' ];
			$this->password = rawurldecode( $urldata[ 'pass' ] );
			$this->cursorType = SQL_CUR_DEFAULT;
			$this->dbType = 'Jet';
			
			$this->connectionID = 0;
	
			parse_str( $urldata[ 'query' ] );
			if( isset( $type ))
				$this->dbType = $type;
		}
		
		#---------------------------------------------------------------------
		function open() {
			if( $this->connectionID == 0 ) {
				$this->connectionID = odbc_connect( $this->dataSource, 
													$this->user, 
													$this->password, 
													$this->cursorType );
/*
				if( $this->connectionID )
					odbc_setoption( $this->connectionID, 1, 108, 1 );
*/
			}
			
			return $this->connectionID != 0;
		}
		
		#---------------------------------------------------------------------
		function close() {
			if( $this->connectionID ) {
				@odbc_close( $this->connectionID );
			}
			$this->connectionID = 0;
		}

		#---------------------------------------------------------------------
		function parsingSql( $sql ) {

			$sql_new = $sql;
		
			$sql_new = str_replace( '�' , '=n;' , $sql_new);
			$sql_new = str_replace( '�' , '=mn;' , $sql_new);
		
			$sql_new = str_replace( '�' , '=a0;' , $sql_new);
			$sql_new = str_replace( '�' , '=e0;' , $sql_new);
			$sql_new = str_replace( '�' , '=i0;' , $sql_new);
			$sql_new = str_replace( '�' , '=o0;' , $sql_new);
			$sql_new = str_replace( '�' , '=u0;' , $sql_new);
		
			$sql_new = str_replace( '�' , '=ma0;' , $sql_new);
			$sql_new = str_replace( '�' , '=me0;' , $sql_new);
			$sql_new = str_replace( '�' , '=mi0;' , $sql_new);
			$sql_new = str_replace( '�' , '=mo0;' , $sql_new);
			$sql_new = str_replace( '�' , '=mu0;' , $sql_new);
		
			$sql_new = str_replace( '�' , '=ma1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=ma2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=ma3;' , $sql_new); 
			$sql_new = str_replace( '�' , '=ma4;' , $sql_new); 
			$sql_new = str_replace( '�' , '=ma5;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=a1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=a2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=a3;' , $sql_new); 
			$sql_new = str_replace( '�' , '=a4;' , $sql_new); 
			$sql_new = str_replace( '�' , '=a5;' , $sql_new); 
			
			$sql_new = str_replace( '�' , '=me1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=me2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=me3;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=e1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=e2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=e3;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=mi1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mi2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mi3;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=i1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=i2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=i3;' , $sql_new); 
			
			$sql_new = str_replace( '�' , '=mo1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mo2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mo3;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mo4;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=o1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=o2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=o3;' , $sql_new); 
			$sql_new = str_replace( '�' , '=o4;' , $sql_new); 
			
			$sql_new = str_replace( '�' , '=mu1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mu2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=mu3;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=u1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=u2;' , $sql_new); 
			$sql_new = str_replace( '�' , '=u3;' , $sql_new); 
			
			$sql_new = str_replace( '�' , '=mc;' , $sql_new); 
			$sql_new = str_replace( '�' , '=c;' , $sql_new); 
		
			$sql_new = str_replace( '�' , '=y1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=y2;' , $sql_new); 

			$sql_new = str_replace( '�' , '=?1;' , $sql_new); 
			$sql_new = str_replace( '�' , '=!1;' , $sql_new); 

		
			$bExecParsing = 0;
			
			#if ($sql_new != $sql){
			#	$bExecParsing = 1;
			#	$sql_new = ereg_replace( "'", "''", $sql_new );
			
			#	return "EXECUTE sp_ejecutar_query '" . $sql_new . "' , " . $bExecParsing;
			#}
			#else
			#{
				return 	$sql;
			#}
		}

		#---------------------------------------------------------------------
		function execute( $sql ) {
			if( $this->connectionID == 0 ) {
				$this->open();
			}

#			if( $fp = fopen( "/tmp/odbc.trace", "a" )) {
#				fputs( $fp, date( "d-m-Y H:i:s " ));
#				fputs( $fp, $sql . "\n" );
#				fclose( $fp );
#			}
			
#			Parsing de query
			$sql = $this->parsingSql( $sql );

			return new ODBCResult( $this, $sql );
		}
		
		#---------------------------------------------------------------------
		function beginTransaction() {
			return TRUE;

			if( $this->connectionID == 0 ) {
				$this->open();
			}
			
			if( odbc_autocommit( $this->connectionID )) {
				return odbc_autocommit( $this->connectionID, TRUE );
			}
			return FALSE;
		}
		
		#---------------------------------------------------------------------
		function commit() {
			return TRUE;

			if( $this->connectionID 
			&& ! odbc_autocommit( $this->connectionID ) ) {
				$result = odbc_commit( $this->connectionID );
				odbc_autocommit( $this->connectionID, FALSE );
				return $result;
			}
			return FALSE;
		}
		#---------------------------------------------------------------------
		function rollback() {
			if( $this->connectionID 
			&& ! odbc_autocommit( $this->connectionID ) ) {
				$result = odbc_rollback( $this->connectionID );
				odbc_autocommit( $this->connectionID, FALSE );
				return $result;
			}
			return FALSE;
		}
		
		#---------------------------------------------------------------------
		function isInTransaction() {
			return( $this->connectionID
					&& ! odbc_autocommit( $this->connectionID ));
		}
		
		#---------------------------------------------------------------------
		function lastIdentity() {
			switch( $this->dbType ) {
			case 'JET' :
			case 'Access' :
			case 'SQLServer' :
				# Don't know if Access97 supports it... Access2000 does
				$query = $this->execute( "SELECT @@IDENTITY AS LastID" );
				break;
			}
			
			if( $query ) {
				if( $query->next() ) {
					$lastid = $query->value( "LastID" );
				}
				
				$query->close();
			}
	
			return $lastid;
		}
	
		#---------------------------------------------------------------------
		function odbcConnectionID() { 
			return $this->connectionID;
		}
	
		#---------------------------------------------------------------------
		function quoteDate( $date ) {
			if( is_integer( $date )) {
				$date = $this->formatDate( $date );
			}
			
			switch( $this->dbType ) {
			case 'JET' :
			case 'Access' :
				return "#$date#";
				break;
			case 'SQLServer' :
			default:
				return "'$date'";
				break;
			}
		}
	
		#---------------------------------------------------------------------
		function escapeString( $str ) {
			switch( $this->dbType ) {
			case 'JET' :
			case 'Access' :
				$str = ereg_replace( "'", "''", $str );
				$str = ereg_replace( "\n", "'+CHR(13)+'", $str );
				$str = ereg_replace( "\r", "'+CHR(10)+'", $str );
				$str = ereg_replace( "\t", "'+CHR(9)+'", $str );
				break;
			case 'SQLServer' :
			case 'MSSQL' :
				$str = ereg_replace( "'", "''", $str );
			#	$str = ereg_replace( "�", "'+CHAR(225)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(233)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(237)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(243)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(250)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(193)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(201)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(205)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(211)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(218)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(241)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(209)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(161)+'", $str );
			#	$str = ereg_replace( "�", "'+CHAR(191)+'", $str );
			#	$str = ereg_replace( "\n", "'+CHAR(13)+'", $str );
			#	$str = ereg_replace( "\r", "'+CHAR(10)+'", $str );
			#	$str = ereg_replace( "\t", "'+CHAR(9)+'", $str );

				$str = ereg_replace( "\+\'\'\+", "+", $str );
				break;
			default:
				$str = ereg_replace( "'", "\\'", $str );
				$str = ereg_replace( "\n", "\\n", $str );
				$str = ereg_replace( "\r", "\\r", $str );
				$str = ereg_replace( "\t", "\\t", $str );
				break;
				break;
			}
			return $str;
		}
		
	}
	
	#=========================================================================
	# ODBCResult : Database query results using odbc functions
	#=========================================================================
	class ODBCResult extends BaseDBResult {
		var $connection;
		var $resultID;
		var $query;
		var $_eof;
		var $_pos;
		
		#---------------------------------------------------------------------
		function ODBCResult( $connection, $sql ) {
			$this->connection = $connection;
			$this->query = $sql;
			$this->resultID = odbc_exec( $connection->odbcConnectionID(),$sql );

			unset( $this->_eof );
			$this->_pos = -1;
		}
		
		#---------------------------------------------------------------------
		function longReadLen( $long ) {
			# para aumentar el tama�o de los campos a traer
			odbc_longreadlen( $this->resultID, $long );
		}
		
		#---------------------------------------------------------------------
		function close() {
			if( $this->resultID ) {
				odbc_free_result( $this->resultID );
			}
			$this->resultID = 0;
		}
		
		#---------------------------------------------------------------------
		function value( $field, $isLong = 0 ) {
			if( is_integer( $field )) {
				$field = $field + 1; # to make it 0-based
			}
			
			if( !isset( $this->_fieldTypes )) {
				$this->_fieldTypes = array();
			}
			
			if( !isset( $this->_fieldTypes[ $field ] )) {
				if( is_integer( $field )) {
					$this->_fieldTypes[ $field ] 
						= odbc_field_type( $this->resultID, $field );
				}
				else {
					$aux = odbc_field_num( $this->resultID, $field );
					$this->_fieldTypes[ $field ] 
						= odbc_field_type( $this->resultID, $aux );
				}
			}
			
			if( $isLong || ( $this->_fieldTypes[ $field ] == "text" )) {
				$longField = "";
				while( $segment = odbc_result( $this->resultID, $field ))
					$longField .= $segment;
				return $longField;
			}
			else {
				return odbc_result( $this->resultID, $field );
			}
		}
		
		#---------------------------------------------------------------------
		function setVars( $prefix = 'db_' ) {
			$fieldCount = odbc_num_fields( $this->resultID );
			
			for( $i = 1; $i <= $fieldCount; $i++ ) {
				$aux = $prefix . odbc_field_name( $this->resultID, $i );
				$GLOBALS[ $aux ] = odbc_result( $this->resultID, $i );
			}
		}
	
		#---------------------------------------------------------------------
		function next() {
			if( !isset( $this->_eof )) {
				$this->_eof = !( odbc_num_fields( $this->resultID ) > 0);
			}

			if( ! $this->_eof ) {
				if( ! isset( $this->_prefetched	)) {
					$this->_eof = ! odbc_fetch_row( $this->resultID );
					if( ! $this->_eof ) {
						if( $this->_pos == -1 )
							$this->_pos = 0;
						else
							$this->_pos++;
					}
					else {
						if( $this->_pos == -1 )
							$this->_count = 0;
					}
				}
				else {
					unset( $this->_prefetched );
				}
			}

			return ! $this->_eof;
		}
		
		#---------------------------------------------------------------------
		function fieldsInfo() {
			$count = odbc_num_fields( $this->resultID );
			if( $count ) {
				$fields = array();
				$i = 0;
				while( $i < $count ) {
					$fields[] = array(
						'name' => odbc_field_name( $this->resultID, $i + 1 ),
						'type' => odbc_field_type( $this->resultID, $i + 1 ),
						'size' => odbc_field_type( $this->resultID, $i + 1 ));
					$i++;
				}
			}
			
			return $fields;
		}
		
		#---------------------------------------------------------------------
		function moveTo( $pos ) {
			if( odbc_num_fields( $this->resultID ) > 0) {
				$this->_eof = ! odbc_fetch_row( $this->resultID, $pos + 1 );
				# adding one to $pos to make it 0-based
				if( ! $this->_eof )
					$this->_pos = $pos;
			}

			return ! $this->_eof;
		}
		
		#---------------------------------------------------------------------
		function moveToFirst() {
			if( odbc_num_fields( $this->resultID ) > 0) {
				$this->_eof = ! odbc_fetch_row( $this->resultID, 1 );
				if( ! $this->_eof )
					$this->_pos = 1;
			}

			return ! $this->_eof;
		}

		#---------------------------------------------------------------------
		function count() {
			if( ! isset( $this->_count )) {
				if( odbc_num_fields( $this->resultID ) > 0 ) {
					$this->_count = odbc_num_rows( $this->resultID );
				}
				else {
					$this->_count = 0;
				}
				
				if( $this->_count == -1 ) {
					if( $this->_pos == -1 ) {
						$this->next();
						$this->_prefetched = TRUE;
					}
				}
			}
			return $this->_count;
		}
	
		#---------------------------------------------------------------------
		function realCount() {
			if( ! isset( $this->_realCount )) {
				if( odbc_num_fields( $this->resultID ) > 0 ) {
					$this->_realCount = odbc_num_rows( $this->resultID );
				}
				else {
					$this->_realCount = 0;
				}
				
				if( $this->_realCount == -1 ) {
					$count = 0;
					if( odbc_fetch_row( $this->resultID, 1 )) {
						$count = 1;
						while( odbc_fetch_row( $this->resultID )) {
							$count++;
						}
						
						odbc_fetch_row( $this->resultID, 1 );
						$this->_prefetched = TRUE;
					}
					$this->_realCount = $count;
				}
			}
			
			return $this->_realCount;
		}
		
		#---------------------------------------------------------------------
		function eof() {
			if( isset( $this->_eof )) {
				return $this->_eof;
			}
			else {
				return !( odbc_num_fields( $this->resultID ) > 0);
			}
		}
	}
}

#=============================================================================
?>
