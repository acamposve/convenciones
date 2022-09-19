<? 
#=============================================================================
#=============================================================================
if( ! defined( '_LIB_SETTINGS' )) 
	{
		define( '_LIB_SETTINGS', '1.0' );
		class LocalSiteSettings 
			{
				var $db = array();
				var $dbString = array();
				#---------------------------------------------------------------------
				function openDBConn( $database = 0 ) 
					{
						if( ! isset($this->db[$database]) ) 
							{
								ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
								include( 'lib/bd/base.php' );
								$this->db[$database] = NewDBConnection($this->dbString[$database]);
							}		
						return($this->db[$database]);
					}
				#---------------------------------------------------------------------
				function closeDBConn() 
					{
						reset( $this->db );
						while( list($key, $value) = each( $this->db )) 
							{
								$value->close();
							}	
						return( TRUE );
					}			
			}
	}	
#--------------------------------------------------------------
if( ! isset( $LibSite )) 
	{
		$GLOBALS[ 'LibSite' ] = new LocalSiteSettings;
		$LibSite =& $GLOBALS[ 'LibSite' ];
	}
#- DB Connection -------------------------------------------------------------
$LibSite->dbString[0] 	= 	"mysql://root:"   
							. rawurlencode( "ases+rh" )
							. "@localhost/pv014_cccol?type=MySQL";
$LibSite->dbString[1] 	= 	"mysql://root:"
							. rawurlencode( "vertrigo" )
							. "@localhost/teniendounbebe?type=MySQL";
$LibSite->dbString[2] 	= 	"odbc://sa:"
							. rawurlencode( "T3ue9+b3" )
							. "@DSN/teneru?type=Jet";
$LibSite->dbString[3] = "mysql://root:"
			   		   . rawurlencode( "T3ue9+b3" )
					   . "@localhost/teneru?type=MySQL";	


#- User Tools ----------------------------------------------------------------
$LibSite->strings = array(
	'labellogin' => 'login',
	'promptlogin' => 'entrar',
	'labelpassword' => 'password',
	'labellogout' => 'salir',
	'promptlogout' => 'salir',
	'labelregister' => 'registro',
	'promptregister' => 'regístrate!',
	'labeluseredit' => 'tu registro',
	'labeluserrecoverpassword' => '¿olvidaste tu password?'
);

#- Months ----------------------------------------------------------------
$LibSite->months = array(
	'Enero',
	'Febrero',
	'Marzo',
	'Abril',
	'Mayo',
	'Junio',
	'Julio',
	'Agosto',
	'Septiembre',
	'Octubre',
	'Noviembre',
	'Diciembre'
);

# Paths-----------------------------------------------------------------------
$doc_root = $_SERVER["DOCUMENT_ROOT"];
$LibSite->doc_root 					= $doc_root;
$LibSite->homeURL 					= "/";
$LibSite->loginURL 					= "/user/login.php";
$LibSite->updateURL 				= "/user/mantenimiento.php";
$LibSite->logoutURL 				= "/user/logout.php";
$LibSite->regURL 					= "/user/register.php";
$LibSite->adminHomeURL				= "/admin/";
$LibSite->adminEmisorasURL			= "/adminemisoras/";
$LibSite->adminLoginURL				= "/admin/user/login.php";
$LibSite->adminLogoutURL			= "/admin/user/logout.php";
$LibSite->adminRegURL 				= "mailto:admin@conatel.com";
$LibSite->userAdminURL				= "/admin/user/index.php";
$LibSite->adminChangePasswordURL	= "/admin/user/changepass.php";
#--	Search Parameters ------------------------------------
$LibSite->searchTable = "search";
$LibSite->searchFieldQuery = array( "title", "description", "keywords" );
$LibSite->otherFields = array("id","link");
#---------------------------------------------------------
?>