var file_queue = [];
var file_queue_busy = false;
var tags_sel = {};


function  file_button (id, url) {
  var dz = $.id(id);

  var cz = document.createElement('INPUT');  // new File();
  cz.type = 'file';
  //cz.style.width = (dz.offsetWidth-2) + 'px';
  //cz.style.height = (dz.offsetHeight-2) + 'px';
//    cz.style.margin = '-3px 0 0 -5px';
  cz.style.fontSize = '50px';
  cz.style.opacity = '0';
//    cz.style.position = 'absolute';
  cz.style.cursor = 'pointer';
  cz.multiple = true;
  //dz.insertBefore(cz, dz.firstChild);

//    var clone = dz.cloneNode(true);
  //var width = (dz.offsetWidth) + 'px';
  //var height = (dz.offsetHeight) + 'px';
  //dz.innerHTML = '';
  //dz.className = '';
  //dz.style.width = width;
  //dz.style.height = height;
//    dz.style.backgroundColor = '#f00';
  //dz.style.overflow = 'hidden';

//    var cz = document.createElement('DIV');
//    cz.style.width = '100px';
//    cz.style.height = '100px';
//    cz.style.backgroundColor = '#f00';
//    cz.style.float = 'right';

  var container = document.createElement('DIV');
  //container.style.width = '20px';
  //container.style.height = '20px';
  container.style.width = (dz.offsetWidth-2) + 'px';
  container.style.height = (dz.offsetHeight-2) + 'px';
  container.style.margin = '-2px 0 0 -4px';
//    container.style.backgroundColor = '#f00';
  container.style.position = 'absolute';
  container.style.overflow = 'hidden';
  //container.style.cursor = 'pointer';

  container.appendChild(cz);
  dz.insertBefore(container, dz.firstChild);
//    dz.insertBefore(cz, dz.firstChild);

  cz.addEventListener('change', function(ce) {
    //console.log(url);
    $.note('file(s) choosed');

    var files = ce.target.files;

    for (var i = 0; i < files.length; i++) {
      var f = files[i];
      //$.note(f.name + ' - ' + f.size + ' bytes, (' + f.type + ')');

      f.durl = url;
      //f.tags = tags_sel;
      //f.parent = parent;
      file_queue.push(f);
      }

    file_queue_send();
    //this.this.this.file_queue_send();
    }, false);
  }




function file_queue_send () {
  if (!this.file_queue_busy && this.file_queue.length) {
    this.file_queue_busy = true;

    $.note('file_queue_send started');

    var f = this.file_queue.shift();

    var ar = new XMLHttpRequest();

    ar.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $.note(this.responseText);
        file_queue_busy = false;
        file_queue_send();
        }
      }

    ar.open('POST', f.durl, true);
    //ar.setRequestHeader('Content-Type', 'application/octet-stream');
    //ar.withCredentials = true; // allow cookies to be sent
    var arfd = new FormData();
    arfd.append('data', f);
    //arfd.append('dtm', f.lastModifiedDate.getTime());

    //var tags = [];
    //for (var t in f.tags) { tags.push(t.substr(1)); }
    //arfd.append('tags', tags.toString());
    arfd.append('parent', parent);

    ar.send(arfd);
    }

  else {
    $.note('file_queue empty || file_queue_send busy');
    // refresh file list after upload
    //reload_file_list();

    location.reload(true);
    }
  }



/*
function  tag_selected (node) {

  if (node.className == 'file_cat_item') {
    node.className = 'file_cat_item fci_selected';
    tags_sel[node.id] = true;
    }

  else {
    node.className = 'file_cat_item';
    //tags_sel[node.id] = undefined;
    delete tags_sel[node.id];
    }

  //console.log(tags_sel);

  reload_file_list();
  }
*/


/*
function  reload_file_list () {

  var post = {};

  if ($.id("search_name").value) {
    post.search = $.id("search_name").value;
    }

  if (Object.keys(tags_sel).length)  {
    //console.log(JSON.stringify(tags_sel));
    //post.tags = JSON.stringify(tags_sel);
    var tags = [];
    for (var t in tags_sel) {
      tags.push(t.substr(1));
      }
    //console.log(tags.toString());
    post.tags = tags.toString();
    }

  var files = [];
  var checkboxes = document.getElementsByName('s');
  if (checkboxes) {
    for (var ch in checkboxes) {
      if (checkboxes[ch].checked) {
        files.push(checkboxes[ch].id.substr(1));
        }
      }
    //console.log(files.toString());
    post.files = files.toString();
    }

  $.ajax('/'+mod+'/a/', function(r) {$.id("file_list").innerHTML = r}, {post: post} );
  }
*/



function  foto_large (foto_id) {
  var il = $.id('foto_large');
  var ilb = $.id('foto_large_back');

  var cw = document.documentElement.clientWidth;
  var ch = document.documentElement.clientHeight;

  il.style.width = cw + 'px';
  il.style.height = ch + 'px';
  ilb.style.width = cw + 'px';
  ilb.style.height = ch + 'px';

  il.style.display = 'block';


  var ila = $.id('foto_large_aligner');
  ila.style.height = ch + 'px';


  var ilv = $.id('foto_large_viewport');
  //ilv.style.width = (cw - 64) + 'px';
  //ilv.style.height = (ch - 64) + 'px';


  var ili = $.id('foto_large_foto');
  ili.style.maxWidth = (cw - 64) + 'px';
  ili.style.maxHeight = (ch - 64) + 'px';
  ili.src = '/i/' + foto_id;
  }




function  file_delete (file_id) {
  //$.ajax('/'+mod+'/fdf/?fle='+file_id, function(r) {if (r == 'ok') {location.reload(true);} else $.note(r); } );
  $.ajax('/'+mod+'/flu/?fle='+file_id, function(r) {location.reload(true);} );
  }


function  create_folder () {
  var name = $.id('create_folder_input').value;
  $.ajax('/'+mod+'/cf/', function(r) {if (r == 'ok') {location.reload(true);} else $.note(r); }, {post: {name: name, parent: parent}} );
  }




window.onload = function() {
  //file_button('create_folder', '/'+mod+'/cf/');
  file_button('file_upload', '/'+mod+'/fup/');

  //$.event("search_name", "keyup", $.delay(function() { reload_file_list() }, 0.5) );
  }

