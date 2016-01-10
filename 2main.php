<?php

/************************************************************************/
/*  public main  v2.oo                                                  */
/************************************************************************/



  // -------------------------------- init -------------------------------- //

$title = '';
$body = '';

include 'l/lib.php';
//include 'lib_mini.php';
db_open();



  // ---------------- parse request URI ---------------- //

$uri_e = explode('?', $_SERVER['REQUEST_URI']);
//$uri_q = (isset($uri_e[1]) ? $uri_e[1] : '');
$uri_e = explode('/', $uri_e[0]);

$act = '';
$url = '';
$id = 1;
//if (count($uri_e) == 2 && $uri_e[1]) {
if (count($uri_e) == 2) {
  $url = filter_url($uri_e[1]) || 'main';
  }
elseif (count($uri_e) == 3) {
  $act = filter_ln($uri_e[1]);
  $id = filter_n($uri_e[2]);
  }




  // ---------------- determine parameters ---------------- //

$language = FALSE;
if (getb('language')) {
  $language = gets('language');
  setcookie('language', $language, 2000000000, '/');
  }
elseif (isset($_COOKIE['language'])) {
  $language = filter_ln($_COOKIE['language']);
  }
elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
  $accept_language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  $accept_language = explode(';', $accept_language[0]);
  $accept_language = explode('-', $accept_language[0]);
  $language = strtolower($accept_language[0]);
  }

if ($language != 'ru')  $language = 'en';
//$language = 'en';


if (isset($_SERVER['REMOTE_ADDR'])) {
  $remote_addr = $_SERVER['REMOTE_ADDR'];
  }






  // -------------------------------------------------------------------------------------------------------------------------------------- //
  // ---------------------------------------------------------------- PAGE ---------------------------------------------------------------- //
  // -------------------------------------------------------------------------------------------------------------------------------------- //

if (!$act) {
  $page = db_read(array(
    'table' => 'page',
    'col' => array('id', 'title'),
    'where' => '`url` = \''.$url.'\'',
    ));

  if ($page) {
    $id = $page['id'];
    $title = $page['title'];
    }
  else {
    $id = 1;
    $title = db_read(array(
      'table' => 'page',
      'col' => 'title',
      'where' => '`id` = '.$id,
      ));
    }


  function  get_page($id) {
    global $language;
    global $remote_addr;
    global $title;

    $page = db_read(array(
      'table' => 'page',
      'col' => 'content',
      'where' => '`id` = '.$id,
      ));



      // ---------------- {remove} {/remove} ---------------- //

    while (($posb = strpos($page,'{remove}')) !== FALSE) {
      $pose = $posb + 8;

      if (($endb = strpos($page, '{/remove}', $pose)) !== FALSE) {
        $ende = $endb + 9;
        $page = substr($page,0,$posb) . '' . substr($page,$ende);
        }

      else {
        $page = substr($page,0,$posb) . '{not closed remove}' . substr($page,$pose);
        }

      }



      // ---------------- {language:en} {/language} ---------------- //

    while (($posb = strpos($page,'{language:')) !== FALSE) {
      $posc = $posb + 10;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $lang = filter_ln(substr($page, $posc, $pose-$posc));
        $pose++;

        if (($endb = strpos($page, '{/language}', $pose)) !== FALSE) {
          $ende = $endb + 11;

          if ($language == $lang) {
            $page = substr($page,0,$endb) . '' . substr($page,$ende);
            $page = substr($page,0,$posb) . '' . substr($page,$pose);
            }

          else {
            $page = substr($page,0,$posb) . '' . substr($page,$ende);
            }

          }

        else {
          $page = substr($page,0,$posb) . '{not closed language}' . substr($page,$pose);
          }


        }
      else {
        $page = substr($page,0,$posb) . '{broken language}' . substr($page,$posc);
        }

      }



      // ---------------- {ip:192.168.0.1} {/ip} ---------------- //

    while (($posb = strpos($page,'{ip:')) !== FALSE) {
      $posc = $posb + 4;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $ip = substr($page, $posc, $pose-$posc);
        $ip = filter($ip, array('0','1','2','3','4','5','6','7','8','9','.',':', '-',','));
        $pose++;

        if (($endb = strpos($page, '{/ip}', $pose)) !== FALSE) {
          $ende = $endb + 5;

          $ip = explode(',', $ip);
          $match = FALSE;
          $remote_addrn = inet_aton($remote_addr);

          foreach ($ip as $v) {
            $range = explode('-', $v);
            if (!isset($range[1]))  $range[1] = $range[0];
            if (inet_aton($range[0]) <= $remote_addrn && $remote_addrn <= inet_aton($range[1]))  {$match = TRUE;  break;}
            }

          if ($match) {
            $page = substr($page,0,$endb) . '' . substr($page,$ende);
            $page = substr($page,0,$posb) . '' . substr($page,$pose);
            }

          else {
            $page = substr($page,0,$posb) . '' . substr($page,$ende);
            }

          }

        else {
          $page = substr($page,0,$posb) . '{not closed ip}' . substr($page,$pose);
          }


        }
      else {
        $page = substr($page,0,$posb) . '{broken ip}' . substr($page,$posc);
        }

      }



      // ---------------- {eip:192.168.0.1} {/ip} ---------------- //

    while (($posb = strpos($page,'{eip:')) !== FALSE) {
      $posc = $posb + 5;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $ip = substr($page, $posc, $pose-$posc);
        $ip = filter($ip, array('0','1','2','3','4','5','6','7','8','9','.',':', '-',','));
        $pose++;

        if (($endb = strpos($page, '{/eip}', $pose)) !== FALSE) {
          $ende = $endb + 6;

          $ip = explode(',', $ip);
          $match = FALSE;
          $remote_addrn = inet_aton($remote_addr);

          foreach ($ip as $v) {
            $range = explode('-', $v);
            if (!isset($range[1]))  $range[1] = $range[0];
            if (inet_aton($range[0]) <= $remote_addrn && $remote_addrn <= inet_aton($range[1]))  {$match = TRUE;  break;}
            }

          if ($match) {
            $page = substr($page,0,$posb) . '' . substr($page,$ende);
            }

          else {
            $page = substr($page,0,$endb) . '' . substr($page,$ende);
            $page = substr($page,0,$posb) . '' . substr($page,$pose);
            }

          }

        else {
          $page = substr($page,0,$posb) . '{not closed eip}' . substr($page,$pose);
          }


        }
      else {
        $page = substr($page,0,$posb) . '{broken eip}' . substr($page,$posc);
        }

      }



      // ---------------- {title} {/title} ---------------- //

    while (($posb = strpos($page,'{title}')) !== FALSE) {
      $pose = $posb + 7;

      if (($endb = strpos($page, '{/title}', $pose)) !== FALSE) {
        $ende = $endb + 8;

        $title = substr($page, $pose, $endb-$pose);

        $page = substr($page,0,$posb) . '' . substr($page,$ende);
        }

      else {
        $page = substr($page,0,$posb) . '{not closed title}' . substr($page,$pose);
        }

      }



      // ---------------- {include:0} ---------------- //

    while (($posb = strpos($page,'{include:')) !== FALSE) {
      $posc = $posb + 9;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $n = filter_n(substr($page, $posc, $pose-$posc));
        $include = get_page($n);
        if (!$include)  $include = '{wrong include}';
        $pose++;
        }
      else {
        $include = '{broken include}';
        $pose = $posc;
        }

      $page = substr($page,0,$posb) . $include . substr($page,$pose);
      }



      // ---------------- {template:0} {content} ---------------- //

    while (($posb = strpos($page,'{template:')) !== FALSE) {
      $posc = $posb + 10;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $n = filter_n(substr($page, $posc, $pose-$posc));
        $template = get_page($n);
        if (!$template)  $template = '{wrong template}';
        $pose++;
        }
      else {
        $template = '{broken template}';
        $pose = $posc;
        }

      $page = substr($page,0,$posb) . '' . substr($page,$pose);

      if (($conb = strpos($template, '{content}')) !== FALSE) {
        $cone = $conb + 9;
        }
      else {
        $conb = $cone = strlen($template);
        }

      $page = substr($template,0,$conb) . $page . substr($template,$cone);

      break;
      }



      // ---------------- {i:0} ---------------- //

    while (($posb = strpos($page,'{i:')) !== FALSE) {
      $posc = $posb + 3;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $n = filter_n(substr($page, $posc, $pose-$posc));

        $file = db_read(array(
          'table' => 'file',
          'col' => array('desc', 'width', 'height'),
          'where' => '`id` = '.$n,
          ));

        if ($file && $file['width']) {
          $image = '<img src="/i/'.$n.'" style="width: '.$file['width'].'px; height: '.$file['height'].'px;"'.($file['desc'] ? ' alt="'.$file['desc'].'"' : '').'>';
          }
        else { $image = '{wrong image}'; }
        $pose++;
        }
      else {
        $image = '{broken image}';
        $pose = $posc;
        }

      $page = substr($page,0,$posb) . $image . substr($page,$pose);
      }



      // ---------------- {f:0} ---------------- //

    while (($posb = strpos($page,'{f:')) !== FALSE) {
      $posc = $posb + 3;

      if (($pose = strpos($page, '}', $posc)) !== FALSE) {
        $n = filter_n(substr($page, $posc, $pose-$posc));

        $file = db_read(array(
          'table' => 'file',
          'col' => array('name', 'desc'),
          'where' => '`id` = '.$n,
          ));

        if ($file) {
          $image = '<a href="/f/'.$n.'">'.$file['name'].'</a>';  // maybe implement `desc` as abbr
          }
        else { $image = '{wrong file}'; }
        $pose++;
        }
      else {
        $image = '{broken file}';
        $pose = $posc;
        }

      $page = substr($page,0,$posb) . $image . substr($page,$pose);
      }


    return  $page;
    }


  b(get_page($id));





    // ---- echoeing output --------------------------------------------------------------------------------- //

  echo '<!DOCTYPE html>'."\r\n".'<html><head>';

  echo '<title>';
  if ($title)  echo ($title);
  echo '</title>';

  echo '<meta charset="UTF-8">';
  //echo '<meta name="robots" content="none">';
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">';
  echo '<link rel="StyleSheet" type="text/css" href="/s.css">';
  //echo '<script type="text/javascript" src="/j.js"></script>';
  //echo '<script>var mod = "'.$mod.'";</script>';

  echo '</head><body>';


  echo $body;


  echo '</body></html>';
  }


?>