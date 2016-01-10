<?php

/************************************************************************/
/*  page  v1.oo                                                         */
/************************************************************************/


if (!isset($body))  die ('error: this is a pluggable module and cannot be used standalone.');



$gpg = getn('pg');






  // -------------------------------- pages ------------------------------------ //

if (!$act) {

  $page = db_read(array(
    'table' => 'page',
    'col' => array('id', 'url', 'title'),
    //'order' => '',
    'key' => 'id',
    ));

    // ---- submenu ---- //
  $submenu['Добавить;plus-button'] = '/'.$mod.'/pge/';
  submenu();
    // ---- end: submenu ---- //




  b('<p class="h1">Страницы</p>');

  if ($page) {

    $icona = array();
    $icona[] = 'pencil-button';
    //$icona[] = 'table';
    $icw = count($icona) *18;
    //$icv = count($icona) *18;

    css_table(array(40, 100, 250, $icw));
    icona($icona);


    b('<table class="lst">');
    b('<tr>');
    b('<td>id');
    b('<td>url');
    b('<td>title');
    b('<td>Д.');

    foreach ($page as $k=>$v) {
      b('<tr');
      if (!$v['url'])  b(' style="color: #888;"');
      b('>');

      b('<td>');
      b($k);


      b('<td>');
      b($v['url']);


      b('<td>');
      b($v['title']);


      b('<td>');
      b(icona('/'.$mod.'/pge/?pg='.$k));
      }

    b('</table>');
    }

  else {
    b('<p class="p">Ошибка: данные отсутствуют.');
    }

  }




  // -------------------------- add / edit -------------------------- //

if ($act == 'pge') {

  $page = array('url' => '',
                'title' => '',
                'content' => '',
                );

  if ($gpg) {
    $col = array();
    foreach ($page as $k=>$v)  $col[] = $k;

    $page = db_read(array('table' => 'page',
                          'col' => $col,
                          'where' => '`id` = '.$gpg,
                          ));
    }


    // ---- submenu ---- //
  if (p() && $gpg)  $submenu['?Удалить;minus-button'] = array('#Подтвердить;tick-button' => form_sbd('/'.$mod.'/pgu/?pg='.$gpg));
  submenu();
    // ---- end: submenu ---- //




  b('<p class="h1">');
  if (!$gpg)  b('Добавление');
  else        b('Редактирование');
  b('</p>');
  b();


  b(form('page', '/'.$mod.'/pgu/?'
    .($gpg ? '&pg='.$gpg : '')
    //.($gppl ? '&ppl='.$gppl : '')
    ));

  b('<table class="edt">');


  b('<tr><td>');
  b('Заголовок:');
  b('<td>');
  b(form_t('f_page_title', $page['title'], 1000));


  b('<tr><td>');
  b('Url:');
  b('<td>');
  b(form_t('f_page_url', $page['url'], 200));


  b('<tr><td>');
  b('Контент:');
  b('<td>');
  b('</table>');

  //b(form_t('f_page_desc', $page['desc'], 300));
  b('<textarea name="f_page_content" style="width: 1340px; height: 380px; resize: none;" autofocus>'.htmlspecialchars($page['content']).'</textarea>');


  b(form_sb());
  b('</form>');
  }




  // ------------------------------------------- ajax: update ------------------------------------------------ //

if ($act == 'pgu') {
  $ajax = TRUE;
  //http_response_code(418);

  $post = postb('f_page_title');

  $table = 'page';
  $where = '`id` = '.$gpg;


  if ($post) {
    $set = array();
    $set['url'] = post('f_page_url');
    $set['title'] = post('f_page_title');
    $set['content'] = postr('f_page_content');

    if ($gpg) {
      db_write(array('table'=>$table, 'set'=>$set, 'where'=>$where));
      }

    else {
      db_write(array('table'=>$table, 'set'=>$set));
      }

    b('/'.$mod.'/');
    }


    // ---- deletion ---- //
  if (!$post && $gpg && p()) {
    $result = db_write(array('table'=>$table, 'where'=>$where));

    b('/'.$mod.'/');
    }  // end: delete

  }


?>