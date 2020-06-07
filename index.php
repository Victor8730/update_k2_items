<?php
header('Content-Type: text/html; charset=utf-8');
require('class.php');  // connect all class and config
if($_GET['update'] == '1'){//check command
    $db = new Db($db_prefix, $db_user, $db_pass, $db_host, $db_name);
    $connect  = $db->connect();
    /*$dataFromDb = $db->load_data($connect, 'k2_items');
    print_r($dataFromDb);*/
     $row = 1;
     $arrayField = array('title','title_head','video','image_caption','metadesc','metakey','add_name');
     if (($handle = fopen("file/k2_items.csv", "r")) !== FALSE) {
         while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
             $id = $data[0];
             array_shift($data);//delete id from data
             $data[2] = ($data[2]=='NULL') ? NULL : $data[2];//if exist text NULL, delete it
             if($row>1){
                 $db->update_data($connect,'k2_items', $arrayField, $data,$id);
             }
             $row++;
         }
         fclose($handle);
     }

}else{
    $tpl = new Parser($titleSite,$h1Site,$content); //enter default data
    $tpl->get_tpl('index.tpl');// read data from the template
    //replace the variables from the template with the received data if necessary
    $tpl->set_tpl('{TITLE-SITE}', $titleSite);
    $tpl->set_tpl('{H1-SITE}', $h1Site);
    $tpl->set_tpl('{CONTENT-SITE}', $content);
    $tpl->parse_tpl(); // parse the page
    echo $tpl->template;
}
