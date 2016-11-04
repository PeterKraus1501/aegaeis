<?php

class database
{

    public function connectdatabase($world)
    {
        $con = mysql_connect("redmarble.online", "aegaeis", "")
        or die ("No database connection.");

        mysql_select_db("$world")
        or die ("Die Datenbank existiert nicht.");

        return $con;
    }

    public function closedatabase($con)
    {
        $con = mysql_close($con);

    }

    public function executeSelect($sql)
    {
       $return = mysql_query($sql);
       if (!$return)
       {
          echo "ERROR:\n";
          echo "$sql\n";
          echo mysql_error();
       }
       return mysql_fetch_object ($return);
    }

    public function executeSQL($sql)
    {
       $return = mysql_query($sql);
       if (!$return)
       {
          echo "ERROR:";
          echo $sql;
          echo mysql_error();
       }
       return $return;
    }

}

?>
