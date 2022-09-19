<?php
#=============================================================================
# /lib/db/drivers/mysql.php
#		MySQL Classes for /lib/db
#
#		Version: 1.0
#		Author: Alberto G�mez <andakuf@cantv.net>
#		Modify: Luis Miguel Romero <luismiguelromero@cantv.net>
#		Date: 2003-05-19
#		License: this file is distributed under the BMW License
#		         Blame Microsoft for a While and you can use it.
#=============================================================================

if( ! defined( '_LIB_DB_DRIVERS_MYSQL' ))
	{
		define( '_LIB_DB_DRIVERS_MYSQL', '1.0' );
		#=========================================================================
		# MYSQLConnection : Database connections using mysql native functions
		#=========================================================================
		class MYSQLConnection extends BaseDBConnection
			{
				var $connectionType = "MYSQL";
				var $host = "localhost";
				var $dataSource = "";
				var $user = "";
				var $password = "";
				var $cursorType = "";
				var $dbType = "";
				var $actualDateFunc = "now()";
				var $connectionID = 0;
				var $transparentQuoting = true;
				#---------------------------------------------------------------------
				function setup( $url )
					{
						$urldata = parse_url( $url );
						$this->dataSource = substr( $urldata[ 'path' ], 1 );
						$this->user = $urldata[ 'user' ];
						$this->password = rawurldecode( $urldata[ 'pass' ] );
						//$this->cursorType = SQL_CUR_DEFAULT;
						$this->dbType = 'Jet';
						$this->connectionID = 0;
						parse_str( $urldata[ 'query' ] );
						if( isset( $type ))
						$this->dbType = $type;
					}
				#---------------------------------------------------------------------
				function open()
					{
						if( $this->connectionID == 0 )
							{
								$this->connectionID = mysql_connect( $this->host,
								$this->user,
								$this->password /*,
								$this->cursorTy */) or die("Could not connect");
					    		mysql_select_db("$this->dataSource") or die("Could not select database");
							}
						return $this->connectionID != 0;
					}

				#---------------------------------------------------------------------
				function close()
					{
						if( $this->connectionID )
							{
								@mysql_close( $this->connectionID );
							}
						$this->connectionID = 0;
					}

				#---------------------------------------------------------------------
				function execute( $sql )
					{
						if( $this->connectionID == 0 )
							{
								$this->open();
							}
				#			if( $fp = fopen( "/tmp/odbc.trace", "a" )) {
				#				fputs( $fp, date( "d-m-Y H:i:s " ));
				#				fputs( $fp, $sql . "\n" );
				#				fclose( $fp );
				#			}
				#			Parsing de query
				#$sql = $this->parsingSql( $sql );
						Return new MYSQLResult( $this, $sql );
					}
				#---------------------------------------------------------------------
				function beginTransaction()
					{
						return TRUE;
					}

				#---------------------------------------------------------------------
				function commit()
					{
						return TRUE;
					}

				#---------------------------------------------------------------------
				function rollback()
					{
						return TRUE;
					}

				#---------------------------------------------------------------------
				function isInTransaction()
					{
						return TRUE;
					}

				#---------------------------------------------------------------------
				function lastIdentity()
					{
						# Returns the value of the 'sequence' field on the last record
						# inserted. Some databases don't support this feature
						if($this->connectionID)
							{
								$this->connectionID;
								if(!$rs = mysql_query( "SELECT LAST_INSERT_ID()", $this->connectionID ))
								return 0;
								if(!$row = mysql_fetch_row($rs))
								return 0;
								return $row[0];
							}
						else
							{
								return 0;
							}
					}
				#---------------------------------------------------------------------
				function mysqlConnectionID()
					{
						return $this->connectionID;
					}

				#---------------------------------------------------------------------
				function quoteDate( $date )
					{
						if( is_integer( $date ))
							{
								$date = $this->formatDate( $date );
							}
						switch( $this->dbType )
							{
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
				function escapeString( $str )
					{
						switch( $this->dbType )
							{
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
		# MySQLResult : Database query results using mysql native functions
		#=========================================================================
		class MYSQLResult extends BaseDBResult
			{
				var $connection;
				var $resultID;
				var $query;
				var $_eof;
				var $_pos;
				var $values = array();
				var $matrix = array();
				#---------------------------------------------------------------------
				function MYSQLResult( $connection, $sql )
					{
						$this->connection = $connection;
						$this->query = $sql;
			//			$this->resultID = mysql_db_query (  $connection->dataSource, $sql, $connection->mysqlConnectionID()  );
						$this->resultID = mysql_query (  $sql, $connection->mysqlConnectionID()  );
						unset( $this->_eof );
						$this->_pos = -1;
						#$this->_prefetched = FALSE;
					}

				#---------------------------------------------------------------------
				function longReadLen( $long )
					{
						# para aumentar el tama�o de los campos a traer
					}
				#---------------------------------------------------------------------
				function close()
					{
						if( isset($this->resultID) )
							{
								if( $this->resultID )
								@mysql_free_result( $this->resultID );
							}
						$this->resultID = 0;
						return TRUE;
					}
				#---------------------------------------------------------------------
				function value( $field, $isLong = 0 )
					{
						if( ! is_array($this->matrix[$this->_pos]) )
							{
								if( !isset( $this->_eof ))
									{
										$this->_eof = !( mysql_num_fields( $this->resultID ) > 0);
										$this->values = mysql_fetch_array( $this->resultID , MYSQL_BOTH);
									}
								$row = $this->values[$field];
								$this->matrix[$this->_pos] = $this->values;
							}
						else
							{
								$row = $this->matrix[$this->_pos][$field];
							}
						return $row;
					}
				#---------------------------------------------------------------------
				function setVars( $prefix = 'db_' )
					{
						$r_row = mysql_fetch_array($this->resultID, MYSQL_ASSOC);
						while( list( $r_key,$r_value ) = each( $r_row ))
							{
								$GLOBALS[ $prefix . $r_key ] = $r_value;
							}
					}
				#---------------------------------------------------------------------
				function getArray( $prefix = 'db_' )
					{
						$r_row = mysql_fetch_array($this->resultID, MYSQL_ASSOC);
						return $r_row;
					}
				#---------------------------------------------------------------------
				function next()
					{
						if( !isset( $this->_eof ))
							{
								@$this->_eof = !( mysql_num_fields( $this->resultID ) > 0);
							}
						if( ! $this->_eof )
							{
								if( ! isset( $this->_prefetched	))
									{
										$this->values = mysql_fetch_array( $this->resultID , MYSQL_BOTH);
										$this->_eof = ( ! $this->values? 1:0);
										if( ! $this->_eof )
											{
												if( $this->_pos == -1 )
													{
														$this->_pos = 0;
													}
												else
													{
														$this->_pos++;
													}
											}
										else
											{
												if( $this->_pos == -1 )
												$this->_count = 0;
											}
									}
								else
									{
										unset( $this->_prefetched );
									}
							}
						return ! $this->_eof;
					}
				#---------------------------------------------------------------------
				function fieldsInfo()
					{
						#$count = mysql_num_fields( $this->resultID );
						#$names = mysql_fetch_assoc($this->resultID);
						#$names = mysql_fetch_array($this->resultID, MYSQL_ASSOC))
						#$lenght = mysql_fetch_lengths($this->resultID);
						$fields = array();
						$i = 0;
						while ($i < mysql_num_fields($this->resultID))
							{
								$meta = mysql_fetch_field($this->resultID, $i);
								$fields[] = array(
								'name' => $meta->name,
								'type' => $meta->type,
								'size' => $meta->max_length);
								$i++;
							}
						return $fields;
					}
				#---------------------------------------------------------------------
				function moveTo( $pos )
					{
						if( mysql_num_rows( $this->resultID ) > 0)
							{
								$this->_eof = ! mysql_data_seek( $this->resultID, $pos );
								# adding one to $pos to make it 0-based
								if( ! $this->_eof )
									{
										$this->_pos = $pos;
										unset( $this->_eof );
									}
							}
						return ! $this->_eof;
					}
				#---------------------------------------------------------------------
				function moveToFirst()
					{
						if( mysql_num_rows( $this->resultID ) > 0)
							{
								$this->_eof = ! mysql_data_seek( $this->resultID, 0 );
								if( ! $this->_eof )
									{
										$this->_pos = 0;
										unset( $this->_eof );
									}
							}
						return ! $this->_eof;
					}
				#---------------------------------------------------------------------
				function count()
					{
						if( ! isset( $this->_count ))
							{
								if( mysql_num_fields( $this->resultID ) > 0 )
									{
										$this->_count = mysql_num_rows( $this->resultID );
									}
								else
									{
										$this->_count = 0;
									}
								if( $this->_count == -1 )
									{
										if( $this->_pos == -1 )
											{
												$this->next();
												$this->_prefetched = TRUE;
											}
									}
							}
						return $this->_count;
					}
				#---------------------------------------------------------------------
				function realCount()
					{
						if( ! isset( $this->_realCount ))
							{
								@$this->_realCount = mysql_num_rows( $this->resultID );
							}
						return $this->_realCount;
					}
				#---------------------------------------------------------------------
				function eof()
					{
						if( isset( $this->_eof ))
							{
								return $this->_eof;
							}
						else
							{
								return !( mysql_num_fields( $this->resultID ) > 0);
							}
					}
				#---------------------------------------------------------------------
				function inserted_id()
					{
						#$aux = mysql_insert_id ( $this->resultID );
						$aux = mysql_insert_id ();
						return $aux;
					}
				#---------------------------------------------------------------------
			}
	}
#=============================================================================
?>
