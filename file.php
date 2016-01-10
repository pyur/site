<?php

/************************************************************************/
/*  file  v1.oo                                                         */
/************************************************************************/



  // -------------------------------- init -------------------------------- //

$body = '';
$redirect = '';

include 'l/lib.php';
db_open();



  // ---------------- parse request URI ---------------- //

$uri_e = explode('?', $_SERVER['REQUEST_URI']);
//$uri_q = (isset($uri_e[1]) ? $uri_e[1] : '');
$uri_e = explode('/', $uri_e[0]);

$act = '';
$id = 0;
if (count($uri_e) == 2) {
  $act = filter_ln($uri_e[1]);
  }
elseif (count($uri_e) == 3) {
  $act = filter_ln($uri_e[1]);
  $id = filter_n($uri_e[2]);
  }



  // ---------------- authorization ---------------- //

//include 'auth.php';






  // -------------------------------- file -------------------------------- //

if ($act == 'i' || $act == 'f') {
  //if ($act == 'foto')  $table = 'foto';
  //if ($act == 'catfoto')  $table = 'cat';
  
  //$id = filter_n($uri_e[2]);

  $qn = filter_n($uri_e[2]);
  $qt = filter_url($uri_e[2]);

  if ($qn != $qt) {
    $id = db_read(array(
      'table' => 'file',
      'col' => 'id',
      'where' => '`url` = \''.$qt.'\'',
      ));
    }

  else {
    $id = $qn;
    }

  if ($id) {
    $file = db_read(array(
      'table' => 'file',
      'col' => array('name','mime'),
      'where' => '`id` = '.$id,
      ));
    $mime = db_read(array(
      'table' => 'mime',
      'col' => 'mimed',
      'where' => '`id` = '.$file['mime'],
      ));

    $data = img_get_fdb('file', $id);

    if (ob_get_level())  { ob_end_clean(); }
    header('Content-Type: '.$mime);
    header('Content-Length: '.strlen($data['data']));
    //header('Content-Disposition: '.(($act == 'f')?'attachment; ':'').'filename="'.mb_convert_encoding($fname, 'Windows-1251', 'UTF-8').'"');
    if ($act == 'f')  header('Content-Disposition: attachment; filename="'.mb_convert_encoding($file['name'], 'Windows-1251', 'UTF-8').'"');

    $headers = apache_request_headers();
    //if (isset($headers['Content-MD5'])  &&  base64_encode(md5($data['data'], TRUE)) == $headers['Content-MD5'])  http_response_code(304);
    //else  echo $data['data'];

    echo $data['data'];
    }
  }


?>