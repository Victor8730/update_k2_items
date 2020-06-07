<?php

/**
 * Class for working with a database
 * */
class Db
{
    private $db_prefix;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $db_name;


    function __construct($prefix, $user, $pass, $host, $name) {
        $this->db_prefix = $prefix;
        $this->db_user = $user;
        $this->db_pass = $pass;
        $this->db_host = $host;
        $this->db_name = $name;
    }

    //connect to server
    public function connect()
    {
        try {
            $db_connect = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_user, $this->db_pass);
            $db_connect->exec("set names utf8");
        } catch (PDOException $e) {
            echo "<span style='color:red;'>Error connecting to database</span>";//$e->getMessage();
        }
        if (isset($db_connect)) {
            return $db_connect;
        }
    }

    //get data from db
    public function load_data($db_connect, $table, $name_field = '', $id = '', $param = '')
    {
        $table = $this->db_prefix . $table;
        try {
            if (isset($db_connect)) {
                if ($id != "") {
                    if ($param != "") {
                        $dbs = $db_connect->prepare("SELECT * FROM $table WHERE $name_field='$id' ORDER BY $param");
                    } else {
                        $dbs = $db_connect->prepare("SELECT * FROM $table WHERE $name_field='$id'");
                    }
                } else {
                    if ($param != "") {
                        $dbs = $db_connect->prepare("SELECT * FROM $table ORDER BY $param");
                    } else {
                        $dbs = $db_connect->prepare("SELECT * FROM $table");
                    }
                }
                $dbs->setFetchMode(PDO::FETCH_ASSOC);
                $dbs->execute();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if (isset($dbs)) {
            $db_arr = $dbs->fetchall();
            return $db_arr;
        }
    }
    //* update data
    public function update_data($db_connect,$table,$update_arr_field,$update_arr_data,$id){
        $table	=	$this->db_prefix.$table;
        if(count($update_arr_field)>1){
            $placeholder	  ='';
            foreach($update_arr_field as $value){
                $placeholder .= $value.'=?,';
            }
            $placeholder = substr($placeholder,0,-1);//delete last comma in placeholder
        }
        else{
            $update_arr_data = explode('-+=+-',$update_arr_data);//convert to an array with one element, because unique separator
            $placeholder 	 = $update_arr_field."=?";//single element placeholder
        }
        try{
            $dbs = $db_connect->prepare("UPDATE $table SET $placeholder WHERE id='$id'");
            $dbs->execute($update_arr_data);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}

?>