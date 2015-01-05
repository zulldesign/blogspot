<?php
$dbtype = 'MySQL';

function dbhold($t = '') {
	return '';
}
class ResultSet {
	var $result;
	var $total_rows;
	var $fetched_rows;

	function set_result( $res ) {
		$this->result = $res;
	}

	function get_result() {
		return $this->result;
	}

	function set_total_rows( $rows ) {
		$this->total_rows = $rows;
	}

	function get_total_rows() {
		return $this->total_rows;
	}

	function set_fetched_rows( $rows ) {
		$this->fetched_rows = $rows;
	}

	function get_fetched_rows() {
		return $this->fetched_rows;
	}

	function increment_fetched_rows() {
		$this->fetched_rows = $this->fetched_rows + 1;
	}
}

// custom
function sql_insert($att, $table_name, $arg = 0) {
	// insert
	foreach ($att as $key=>$val) {
		if ($key == 'nid') $ned = 1;
		$keys .= $keys?','.$key:$key;
		$vals .= $vals?",'$val'":"'$val'";
	}
	$q = "insert into `$table_name`($keys) values($vals)";
	return sql_query($q);
}

function sql_update($att, $table_name, $arg = 0) {
	// update
	foreach ($att as $key=>$val) {
		if ($key != 'id') {
			$sets .= $sets?",$key='$val'":"$key='$val'";
		}
	}
	if (is_array($arg)) {
		foreach ($arg as $key=>$val) {
			$wheres .= $wheres ? " and $key='$val'" : "$key='$val'";
		}
	}
	if ($att['id']) {
		$wheres = " id='{$att['id']}'";
	}
	if (!$wheres) {
		return false;
	}
	$q = "update `$table_name` set $sets where $wheres";
	return sql_query($q);
}


function sql_connect($host, $user, $password, $db, $creat_db = 0)
{
global $dbtype;
switch ($dbtype) {

    case "MySQL":
        $dbi=@mysql_connect($host, $user, $password);
		if (!$dbi)
			die("cannot connect to mysql server");
        if (!mysql_select_db($db)) {
			if ($creat_db) {
				$creat_db = mysql_query("CREATE DATABASE $db");
				if (!$creat_db)
					die("Cannot creat db : $db");
				mysql_select_db($db);
			}
			else
				die("cannot select database");
		}
	    //mysql_query("CREATE DATABASE $db");
	    //mysql_select_db($db);
	    //include("includes/install.php");
        return $dbi;
    break;

    case "mSQL":
         $dbi=msql_connect($host);
         if (!msql_select_db($db)) {
	    msql_query("CREATE DATABASE $db");
	    msql_select_db($db);
	    include("includes/install.php");
	    die();
         }
	 return $dbi;
    break;;


    case "PostgreSQL":
         $dbi=@pg_connect("host=$host user=$user password=$password port=5432 dbname=$db");
         return $dbi;
    break;;

    case "PostgreSQL_local":
         $dbi=@pg_connect("user=$user password=$password dbname=$db");
         return $dbi;
    break;;

    case "ODBC":
         $dbi=@odbc_connect($db,$user,$password);
         return $dbi;
    break;;

    case "ODBC_Adabas":
         $dbi=@odbc_connect($host.":".$db,$user,$password);
	 return $dbi;
    break;;

    case "Interbase":
         $dbi=@ibase_connect($host.":".$db,$user,$password);
         return $dbi;
    break;;

    case "Sybase":
        $dbi=@sybase_connect($host, $user, $password);
	if (!sybase_select_db($db,$dbi)) {
	    sybase_query("CREATE DATABASE $db",$dbi);
    	    sybase_select_db($db,$dbi);
    	    include("includes/install.php");
	    die();
	}
	return $dbi;
    break;;

    default:
    break;;
    }

}

function sql_logout($id)
{
global $dbtype;
switch ($dbtype) {

    case "MySQL":
        $dbi=@mysql_close($id);
        return $dbi;
    break;;

    case "mSQL":
         $dbi=@msql_close($id);
         return $dbi;
    break;;

    case "PostgreSQL":
    case "PostgreSQL_local":
         $dbi=@pg_close($id);
         return $dbi;
    break;;
  
    case "ODBC":
    case "ODBC_Adabas":
         $dbi=@odbc_close($id);
         return $dbi;  
    break;;

    case "Interbase":
         $dbi=@ibase_close($id);
         return $dbi;
    break;;

    case "Sybase":
        $dbi=@sybase_close($id);
        return $dbi;
    break;;

    default:
    break;;
    }
}



function insert_id()
{
	return mysql_insert_id();
}

function sql_query($query)
{

global $dbtype,$conn;
global $sql_debug;
$sql_debug = 0;
if($sql_debug) echo "SQL query: ".str_replace(",",", ",$query)."<BR>";
switch ($dbtype) {

    case "MySQL":
		if (!$res = mysql_query($query,$conn)) {
			echo"SQL query error!<br><b>Error infomation:</b><hr>[SQL]<br>".htmlspecialchars($query)."<br><br>[Error]<br><font color=red>".mysql_error()."</font><br><br><hr>You can get help by sending this infomation to us: http://jcow.net";exit();
		}
        return $res;
    break;

    case "mSQL":
        $res=@msql_query($query, $conn);
        return $res;
    break;

    case "PostgreSQL":
    case "PostgreSQL_local":
	$res=pg_exec($id,$query);
	$result_set = new ResultSet;
	$result_set->set_result( $res );
	$result_set->set_total_rows( sql_num_rows( $result_set ) );
	$result_set->set_fetched_rows( 0 );
        return $result_set;
    break;;

    case "ODBC":
    case "ODBC_Adabas":
        $res=@odbc_exec($id,$query);
        return $res;
    break;;

    case "Interbase":
        $res=@ibase_query($id,$query);
        return $res;
    break;;

    case "Sybase":
        $res=@sybase_query($query, $id);
        return $res;
    break;;

    default:
    break;;

    }
}

/*
 * sql_num_rows($res)
 * given a result identifier, returns the number of affected rows
 */

function sql_counts($res)
{
global $dbtype;
switch ($dbtype) {
 
    case "MySQL":
        $rows=@mysql_num_rows($res);
        return $rows;
    break;;

    case "mSQL":  
        $rows=msql_num_rows($res);
        return $rows;
    break;;

    case "PostgreSQL":
    case "PostgreSQL_local":
        $rows=pg_numrows( $res->get_result() );
        return $rows;
    break;;
        
    case "ODBC":
    case "ODBC_Adabas":
        $rows=odbc_num_rows($res);
        return $rows; 
    break;;
        
    case "Interbase":
	echo "<BR>Error! PHP dosen't support ibase_numrows!<BR>";
        return $rows; 
    break;;

    case "Sybase":
        $rows=sybase_num_rows($res);
        return $rows; 
    break;;

    default:
    break;;
    }
}


function sql_fetch_row(&$res, $nr=0)
{
global $dbtype,$q;
switch ($dbtype) {

    case "MySQL":
        $row = mysql_fetch_row($res);
        return $row;
    break;;

    case "mSQL":
        $row = msql_fetch_row($res);
        return $row;
    break;;

    case "PostgreSQL":
    case "PostgreSQL_local":
	if ( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_row($res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		return $row;
	} else {
		return false;
	}
    break;;

    case "ODBC":
    case "ODBC_Adabas":
        $row = array();
        $cols = odbc_fetch_into($res, $nr, $row);
        return $row;
    break;;

    case "Interbase":
        $row = ibase_fetch_row($res);
        return $row;
    break;;

    case "Sybase":
        $row = sybase_fetch_row($res);
        return $row;
    break;;

    default:
    break;;
    }
}


function sql_fetch_array(&$res, $nr=0)
{
global $dbtype;
switch ($dbtype) {
    case "MySQL":
        $row = mysql_fetch_array($res, MYSQL_ASSOC);
        return $row;
    break;;
    }
}

function sql_fetch_object(&$res, $nr=0)
{
global $dbtype;
switch ($dbtype)
    {
    case "MySQL":
        $row = mysql_fetch_object($res);
	if($row) return $row;
	else return false;
    break;;

    case "mSQL":
        $row = msql_fetch_object($res);
	if($row) return $row;
	else return false;
    break;;

    case "PostgreSQL":
    case "PostgreSQL_local":
	if( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_object( $res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		if($row) return $row;
		else return false;
	} else {
		return false;
	}
    break;;

    case "ODBC":
        $result = odbc_fetch_row($res, $nr);
	if(!$result) return false;
	$nf = odbc_num_fields($res); /* Field numbering starts at 1 */
        for($count=1; $count < $nf+1; $count++)
	{
            $field_name = odbc_field_name($res, $count);
            $field_value = odbc_result($res, $field_name);
            $row->$field_name = $field_value;
        }
        return $row;
    break;;

    case "ODBC_Adabas":
        $result = odbc_fetch_row($res, $nr);
	if(!$result) return false;

        $nf = count($result)+2; /* Field numbering starts at 1 */
	for($count=1; $count < $nf; $count++) {
	    $field_name = odbc_field_name($res, $count);
	    $field_value = odbc_result($res, $field_name);
	    $row->$field_name = $field_value;
	}
        return $row;
    break;;

    case "Interbase":
        $orow = ibase_fetch_object($res);
	if($orow)
	{
	    $arow=get_object_vars($orow);
	    while(list($name,$key)=each($arow))
	    {
		$name=strtolower($name);
		$row->$name=$key;
	    }
    	    return $row;
	}else return false;
    break;;

    case "Sybase":
        $row = sybase_fetch_object($res);
        return $row;
    break;;

    }
}

function sql_free_result($res) {
global $dbtype;
switch ($dbtype) {

    case "MySQL":
        $row = mysql_free_result($res);
        return $row;
    break;;

	   case "mSQL":
        $row = msql_free_result($res);
        return $row;
    break;;


	    case "PostgreSQL":
    case "PostgreSQL_local":
        $rows=pg_FreeResult( $res->get_result() );
        return $rows;
    break;;

    case "ODBC":
    case "ODBC_Adabas":
        $rows=odbc_free_result($res);
        return $rows;
    break;;

    case "Interbase":
	echo "<BR>Error! PHP dosen't support ibase_numrows!<BR>";
        return $rows;
    break;;

    case "Sybase":
        $rows=sybase_free_result($res);
        return $rows;
    break;;
	}
}

?>