<?php
require_once "../lib/nusoap.php";
require_once "db.php";

$server = new soap_server();
$server->configureWSDL("aegaeisServer", "urn:http://redmarble.online/aegaeis/server/");
$soap->wsdl->schemaTargetNamespace = 'http://redmarble.online/aegaeis/xsd/';

#######################################################################################################################
#
# New class user
#

class user 
{

    ###################################################################################################################
    #
    # New Function user.getName
    #
    # Last Change: 17.09.2016 | Initial creation | pkr
    public function getName($mail)
    {

        $db = new database();
        $con=$db->connectdatabase("aegaeis");

        $return=$db->executeSQL("SELECT id, email FROM user where email = '$mail'");
        $row = mysql_fetch_object ($return);

        if ($row)
        {
            return array(
                        'id' => $row->id,
                        'email' => "$row->email"
                        );
            #return "E-Mail already exists";
        }
        else
        {
            return array(
                        'id' => 0,
                        'email' => "Ein user mit einer solchen E-Mail adresse existiert nicht"
                        );
            #return "Ein user mit einer solchen E-Mail adresse existirt nicht";
        }
    } // End of method getName
    
    ###################################################################################################################
    #
    # New Function user.create
    #
    # Last Change: 17.09.2016 | Initial creation | pkr
    public function create($email, $email2, $password, $termOfUse)
    {

        $db = new database();
        $con=$db->connectdatabase("aegaeis");

        if($termOfUse<>"Y")
        {
            return "Terms of use not accepted";
        }

        if(strlen($password)<8)
        {
            return "Password to short";
        }
    
        if($email<>$email2)
        {
            return "e-mail name not the same";
        }

        $return=$db->executeSQL("SELECT id FROM user where email = '$email'");
        $row = mysql_fetch_object ($return);

        if ($row)
        {
            return "e-mail already exists";
        }


        $encryptedPassword=md5($password);
        $return=$db->executeSQL("insert into user(email, password, encryptedPassword) 
                                             values ('$email', '$password', '$encryptedPassword')");
        $id=mysql_insert_id();

        return "$id";
    } // End of method 'createUser''

} // End of class 'user'

#
# Add a complex type
#
$server->wsdl->addComplexType(
    'userName',
    'complextType',
    'struct',
    'sequence',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:integer'),
        'email' => array('name' => 'email', 'type' => 'xsd:string')
    )
);

#
# This we need for each function
#
$server->register("user.getName",
    array("mail" => "xsd:string"),
    #array("return" => "xsd:string"),
    array("return" => "tns:userName"),
    "urn:aegaeisServerTrial",
    "urn:aegaeisServerTrial#user.getName",
    "rpc",
    "encoded",
    "Get a user name");

#
# This we need for each function
#
$server->register("user.create",
    array("email" => "xsd:string", "email2" => "xsd:string", "password" => "xsd:string", "termOfUse" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:aegaeisServer",
    "urn:aegaeisServer#user.create",
    "rpc",
    "encoded",
    "Create a user");

#
# End Of type definition for class user
#

#
#######################################################################################################################################################################################################


#######################################################################################################################################################################################################

#
# New Function getUserName
#
function getUserName($mail)
{

    $db = new database();
    $con=$db->connectdatabase("aegaeis");

    $return=$db->executeSQL("SELECT id FROM user where email = '$mail'");
    $row = mysql_fetch_object ($return);

    if ($row)
    {
        return "E-Mail already exists";
    }
    else
    {
        return "OK";
    }
}


#
# This we need for each function
#
$server->register("getUserName",
    array("mail" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:aegaeisServer",
    "urn:aegaeisServer#getUserName",
    "rpc",
    "encoded",
    "Get a user name");



#
#
#######################################################################################################################################################################################################


#######################################################################################################################################################################################################
#
# New Function loginUser
#
function loginUser($email, $password)
{

    $db = new database();
    $con=$db->connectdatabase("aegaeis");

    $encryptedPassword=md5($password);

    $return=$db->executeSelect("SELECT id FROM user where email = '$email' and encryptedPassword='$encryptedPassword'");
    return "$return->id";
}

#
# This we need for each function
#
$server->register("loginUser",
    array("email" => "xsd:string", "password" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:aegaeisServer",
    "urn:aegaeisServer#loginUser",
    "rpc",
    "encoded",
    "Login a user");

#######################################################################################################################################################################################################

#$server->wsdl->addComplexType(
#  'list',
#  'complexType',
# 'array',
#  'sequence',
#  '',
#  array(
#    'itemName' => array(
#      'name' => 'itemName',
#      'type' => 'xsd:string',
#      'minOccurs' => '0',
#      'maxOccurs' => 'unbounded'
#    )
#  )
#);

#
# New Function listUsers -- Versuch
#
#function listUsers()
#{
#
#    $db = new database();
#    $con=$db->connectdatabase("aegaeis");
#
#    $list = array("foo", "bar", "hello", "world");
#    $list = array('a','b','c');
#
#    $i=0;
    #$response = array();

#    $result=$db->executeSQL("SELECT name FROM user");
    #while($row=mysql_fetch_assoc($result))
    #{
    #    array_push($list, $row);
    #    $list[$i] = $row->name;
    #    $i++;
    #}
 #   return $list;
    #eturn
#}
#else
#{
#    return new soap_fault('Client','','Error.');
#}


#
# This we need for each function ???
#

#$server->register("listUsers",
#    #array("mail" => "xsd:string"),
#    array("return" => "tns:list"),
#    "urn:aegaeisServer",
#    "urn:aegaeisServer#listUsers",
#    "rpc",
#    "encoded",
#    "List users");

#
#
#######################################################################################################################################################################################################

#
# This is only needed once
#
$server->service($HTTP_RAW_POST_DATA);

?>
