<?php

/************************************************************************/
/*  File manager  v3.oo                                                 */
/************************************************************************/


if (!isset($body))  die ('error: this is a pluggable module and cannot be used standalone.');

$gfle = getn('fle');
$gpnt = getn('p');






  // --------------------------------------- список -------------------------------------------- //

if (!$act) {
  db_open('sqlite:thumb.sqlite3');

  b('<script>');
  b("\r\n");
  b('var parent = '.$gpnt.';');
  b("\r\n");
  $file = 'm/'.$mod.'/file.js';
  b(fread (fopen ($file, 'rb'), filesize ($file) ));
  b("\r\n");
  b('</script>');
  b("\r\n");


  $file = db_read(array(
    'table' => 'file',
    'col' => array('id', 'url', 'name', 'desc', 'size', 'width', 'height', 'mime', 'dtu', 'owner'),  // , 'namei'
    'where' => '`p` = '.$gpnt,
    'order' => 'namei',
    'key' => 'id',
    ));


  if ($gpnt) {
    $parent_folder = db_read(array(
      'table' => 'file',
      'col' => 'p',
      'where' => '`id` = '.$gpnt,
      ));
    }


    // ---- submenu ---- //
  if ($gpnt)  $submenu['Вверх;navigation-090-button'] = '?p='.$parent_folder;
  if (p('edit'))  $submenu['#Создать папку;folder--plus'] = 'var a = $.id(\'create_folder\');  if (a.style.display == \'none\') {a.style.display=\'block\'; $.id(\'create_folder_input\').focus();}  else a.style.display=\'none\'';
  if (p('edit'))  $submenu['#Загрузить файлы;document-text--plus'] = '" id="file_upload';
  submenu();
  b('<div id="create_folder" style="display: none;  background-color: #eee;  padding: 4px 8px;  width: 204px;">');
  //b('<input id="create_folder_input" type="text" style="border: 1px solid #ccc;  width: 200px;">');  // placeholder="..."
  b('<input id="create_folder_input" type="text" style="border: 1px solid #ccc;  width: 200px;" onkeypress="var x;  if(window.event) {x=event.keyCode;}  else if(event.which) {x=event.which;}  if(x == 13) create_folder();">');
  b('</div>');
    // ---- end: submenu ---- //




    // ---------------- output ---------------- //

  b('<style>');
  b("\r\n");
  $file2 = 'm/'.$mod.'/file.css';
  b(fread (fopen ($file2, 'rb'), filesize ($file2) ));
  b("\r\n");
  b('</style>');
  b("\r\n");


    // -------- large photo viewer -------- //

  b('<div id="foto_large" style="display: none;  position: fixed;  text-align: center;  width: 1280px;  height: 600px;  left: 0;  top: 0;  z-index: 9999;" onclick="this.style.display = \'none\';">');

  b('<div id="foto_large_back" style="position: absolute;  width: 1280px;  height: 600px;  background-color: #444;  opacity: 0.9; z-index: -1;">');
  b('</div>');


  b('<div id="foto_large_aligner" style="display: inline-block;  vertical-align: middle;  width: 0px;  height: 1px;"></div>');

  b('<div id="foto_large_frame" style="display: inline-block;  margin: 0 auto;  vertical-align: middle;  padding: 20px;  background-color: #eee;  border: 1px solid #ccc;">');
  b('<div id="foto_large_viewport" style="overflow: hidden;  text-align: center;  line-height: 0;  border: 1px solid #ccc;  background-color: #222;">');  // width: 100px;  height: 100px;
  //b('<img id="photo_viewer_photo" src="i.php?4" style="max-width: 640px; max-height: 480px;">');
  b('<img id="foto_large_foto" src="" style="max-width: 640px; max-height: 480px;">');
  b('</div>');
  b('</div>');

  b('</div>');
    // -------- end: large photo viewer -------- //


    // -------- file list -------- //

  b('<div id="file_list" class="file_list">');


  if ($file) {
    $iconaf = array();
    if (p('edit'))  $iconaf[] = 'rainbow';  // folder--pencil
    if (p('edit'))  $iconaf[] = 'holly';  // folder--cross
    icona($iconaf,'f');

    $icona = array();
    if (p('edit'))  $icona[] = 'pencil-button';
    $icona[] = 'navigation-270-button-white';
    if (p('edit'))  $icona[] = 'cross-button';
    icona($icona);


      // -------- folders -------- //

    foreach ($file as $k=>$v) {
      if ($v['mime'])  continue;

      b('<div class="folder_frame">');

      b('<a class="folder_frame" href="?p='.$k.'">');  //  onclick="file_large('.$k.');"

      b('<div class="folder_img">');
      //if ($v['width'])  $thumb = db_read(array('db'=>'thumb.sqlite3', 'table'=>'t', 'col'=>'d', 'where'=>'`id` = '.$k));
      b('<div class="folder_imga"></div>');
      //if ($v['width'])  b('<img class="file_img" src="data:image/jpeg;base64,'.base64_encode($thumb).'">');
      b('</div>');

      b('<div class="folder_row">');
      b($v['name']);
      b('</div>');

      b('<div class="folder_row">');
      b(dateh($v['dtu']));
      b('</div>');

      b('</a>');

      b('<div class="folder_row">');
      if (p('edit'))  b(icona('/'.$mod.'/fle/?fle='.$k,'f'));
      if (p('edit'))  b(icona('#if (confirm(\'Подтвердите удаление\')) {file_delete('.$k.')}','f'));
      b('</div>');

      b('</div>');
      }


      // -------- files -------- //

    foreach ($file as $k=>$v) {
      if (!$v['mime'])  continue;

      b('<div class="file_frame">');
      b('<div class="file_img"');
      if ($v['width'])  b(' onclick="foto_large('.$k.');"');
      b('>');
      if ($v['width'])  $thumb = db_read(array('db'=>'thumb.sqlite3', 'table'=>'t', 'col'=>'d', 'where'=>'`id` = '.$k));
      b('<div class="file_imga"></div>');
      if ($v['width'])  b('<img class="file_img" src="data:image/jpeg;base64,'.base64_encode($thumb).'">');
      b('</div>');


      if ($v['url']) {
        b('<div class="file_row_url">');
        b($v['url']);
        b('</div>');
        }

      b('<div class="file_row">');
      b($v['name']);
      b('</div>');

      if ($v['desc']) {
        b('<div class="file_row_desc">');
        b($v['desc']);
        b('</div>');
        }

      if ($v['width']) {
        b('<div class="file_row">');
        b($v['width'].' x '.$v['height']);
        b('</div>');
        }

      b('<div class="file_row">');
      $size = number_format($v['size'], 0, ',', ' ');
      b(substr($size,0,-3).'<span style="color:#888;">'.substr($size,-3,3).'</span>');
      b('</div>');

      b('<div class="file_row">');
      b(dateh($v['dtu']));
      b('</div>');

      b('<div class="file_row">');
      //b('<input name="s" id="s'.$k.'" type="checkbox"');
      //if (isset($files_sel) && $files_sel && in_array($k, $files_sel))  b(' checked');
      //b('>');

      if (p('edit'))  b(icona('/'.$mod.'/fle/?fle='.$k));
      b(icona('/f/'.$k));
      if (p('edit'))  b(icona('#if (confirm(\'Подтвердите удаление\')) {file_delete('.$k.')}'));
      b('</div>');

      b('</div>');
      }

    }

  //else {
  //  b('<p class="p">Здесь пусто.');
  //  }

  b('</div>');  // file_list
  }




  // ------------------------------------------- add / edit ------------------------------------------- //

if ($act == 'fle' && p('edit') ) {

  $file = array(
    'p' => 0,
    'url' => '',
    'name' => '',
    'namei' => '',
    'desc' => '',
    'width' => 0,
    'height' => 0,
    'size' => 0,
    'mime' => 0,
    'dtu' => $curr['datetime'],
    'owner' => '',
    );

  if ($gfle) {
    $col = array();
    foreach ($file as $k=>$v)  $col[] = $k;

    $file = db_read(array(
      'table' => 'file',
      'col' => $col,
      'where' => '`id` = '.$gfle,
      ));
    }


    // ---- submenu ---- //
  if (p() && $gfle)  $submenu['?Удалить;minus-button'] = array('#Подтвердить;tick-button' => form_sbd('/'.$mod.'/flu/?fle='.$gfle));
  submenu();
    // ---- end: submenu ---- //




  b('<p class="h1">');
  if (!$gfle)  b('Добавление');
  else         b('Редактирование');
  b('</p>');
  b();
  b();


  b(form('file', '/'.$mod.'/flu/', array(
    $gfle ? 'fle='.$gfle : ''
    )));

  b('<table class="edt">');


  b('<tr><td>');
  b('URL:');
  b('<td>');
  b(form_t('f_file_url', $file['url'], 200));


  b('<tr><td>');
  b('Имя:');
  b('<td>');
  b(form_t('f_file_name', $file['name'], 500));


  //b('<tr><td>');
  //b('namei:');
  //b('<td>');
  //b(form_t('f_file_namei', $file['namei'], 300));


  b('<tr><td>');
  b('Описание:');
  b('<td>');
  b(form_t('f_file_desc', $file['desc'], 500));


  //b('<tr><td>');
  //b('size:');
  //b('<td>');
  //b(form_t('f_file_size', $file['size'], 300));


  //b('<tr><td>');
  //b('width, height:');
  //b('<td>');
  //b(form_t('f_file_width', $file['width'], 300));
  //b(form_t('f_file_height', $file['height'], 300));


  //b('<tr><td>');
  //b('mime:');
  //b('<td>');
  //b(form_t('f_file_mime', $file['mime'], 50));


  //b('<tr><td>');
  //b('Дата, время загрузки:');
  //b('<td>');
  //b(form_dt(array('f_file_dtu_y;2000', 'f_file_dtu_m', 'f_file_dtu_d', 'f_file_dtu_h', 'f_file_dtu_i', 'f_file_dtu_s'),  $file['dtu'] ));


  b('</table>');


  b(form_sb());

  b('</form>');
  }




  // ------------------------------------------- ajax: update ------------------------------------------------ //

if ($act == 'flu' && p('edit') ) {
  $ajax = TRUE;
  //http_response_code(418);

  $post = postb('f_file_name');

  $table = 'file';
  $where = '`id` = '.$gfle;


  if ($post) {
    $set = array();
    $set['url'] = post('f_file_url');
    $set['name'] = post('f_file_name');
    //$set['namei'] = post('f_file_namei');
    $set['namei'] = mb_convert_case($set['name'], MB_CASE_LOWER);
    $set['desc'] = post('f_file_desc');
    //$set['mime'] = postn('f_file_mime');
    //$set['size'] = postn('f_file_size');
    //$set['width'] = postn('f_file_width');
    //$set['height'] = postn('f_file_height');
    //$set['dtu'] = datesql(postn('f_file_dtu_y'), postn('f_file_dtu_m'), postn('f_file_dtu_d'), postn('f_file_dtu_h'), postn('f_file_dtu_i'), postn('f_file_dtu_s'));
    $set['owner'] = post('f_file_owner');

    if ($gfle) {
      $parent = db_read(array('table'=>$table, 'col'=>'p', 'where'=>$where));
      db_write(array('table'=>$table, 'set'=>$set, 'where'=>$where));
      b('/'.$mod.'/?p='.$parent);
      }

    else {
      //$gfle = db_write(array('table'=>$table, 'set'=>$set));
      //b('/'.$mod.'/jne/?id='.$gid.'&fle='.$gfle);
      b('/'.$mod.'/');
      }

    }


    // ---- deletion ---- //
  elseif (!$post && $gfle) {
    $parent = db_read(array('table'=>$table, 'col'=>'p', 'where'=>$where));

    $has_children = db_read(array(
      'table' => $table,
      'col' => 'id',
      'where' => '`p` = '.$gfle,
      ));

    if (!$has_children) {
      $result = db_write(array('table'=>$table, 'where'=>$where));

      img_delete_fdb($table, $gfle);
      db_open('sqlite:thumb.sqlite3');
      db_write(array('db'=>'thumb.sqlite3', 'table'=>'t', 'where'=>'id = '.$gfle));
      }

    b('/'.$mod.'/?p='.$parent);
    }  // end: delete

  }






  // -------------------------------------------------------------------------------------------------------------------- //
  // --------------------------------------------------- AJAX ----------------------------------------------------------- //
  // -------------------------------------------------------------------------------------------------------------------- //


  // -------------------------------- file upload -------------------------------- //

if ($act == 'fup') {
  $ajax = TRUE;
  //http_response_code(418);

  //          [name] => Sol9pbjQC4M.jpg
  //          [type] => image/jpeg
  //          [tmp_name] => C:\Windows\Temp\php5BC3.tmp
  //          [error] => 0
  //          [size] => 56308

  if (!isset($_FILES['data']))  die('error: no data received.');
  $parent = postn('parent');

  $file = $_FILES['data'];
  $receive = fread (fopen ($file['tmp_name'], 'rb'), filesize ($file['tmp_name']) );


  $table = 'file';

  $name = mb_substr($file['name'],0,255);


  $mime = db_read(array(
    'table' => 'mime',
    'col' => 'id',
    'where' => '`mimed` = \''.$file['type'].'\'',
    ));

  if (!$mime) {
    $mime = db_write(array(
      'table' => 'mime',
      'set' => array('mimed'=>$file['type']),
      ));
    }


  $image = FALSE;
  $width = 0;
  $height = 0;
  if (substr($receive, 0,3) == chr(255).chr(216).chr(255))  $image = 'jpg';
  elseif (substr($receive, 0,4) == chr(137).'PNG')          $image = 'png';
  elseif (substr($receive, 0,3) == 'GIF')                   $image = 'gif';

  if ($image) {
    $image = imagecreatefromstring($receive);
    $width = imagesx($image);
    $height = imagesy($image);
    }


  $set = array();
  $set['p'] = $parent;
  $set['url'] = '';
  $set['name'] = $name;
  $set['namei'] = mb_convert_case($name, MB_CASE_LOWER);
  $set['desc'] = '';
  $set['size'] = $file['size'];
  $set['width'] = $width;
  $set['height'] = $height;
  $set['mime'] = $mime;
  $set['dtu'] = $curr['time'];
  $set['owner'] = 0;  // $curr['userx'];
  $gid = db_write(array('table'=>$table, 'set'=>$set));

  img_upload_fdb ($table, $gid, $receive);


    // -------- generate thumb -------- //
  if ($image) {
    $thumb = shrink_image($image, 128, 96);

    ob_start();
    imagejpeg($thumb, NULL, 50);
    $thumb = ob_get_clean();

    $db = new SQLite3('d/thumb.sqlite3'); 
    $query  = 'INSERT INTO `t` (`id`, `d`) VALUES (?, ?)';
    $prepare = $db->prepare($query);
    $prepare->bindParam(1, $gid);
    $prepare->bindParam(2, $thumb, SQLITE3_BLOB);
    $prepare->execute();
    }


  b('uploaded.');
  }




  // -------------------------------- create folder -------------------------------- //

if ($act == 'cf') {
  $ajax = TRUE;
  //http_response_code(418);

  $name = post('name');
  $parent = postn('parent');


  $table = 'file';

  $name = mb_substr($name,0,255);


  $set = array();
  $set['p'] = $parent;
  $set['url'] = '';
  $set['name'] = $name;
  $set['namei'] = mb_convert_case($name, MB_CASE_LOWER);
  $set['desc'] = '';
  $set['size'] = 0;
  $set['width'] = 0;
  $set['height'] = 0;
  $set['mime'] = 0;  // 0 - for folder
  $set['dtu'] = $curr['time'];
  $set['owner'] = 0;  // $curr['userx'];
  $gid = db_write(array('table'=>$table, 'set'=>$set));


  b('ok');
  }


?>