/*
###################################################################################
  FLOW Shopssoftware Entwicklungsstand: 05.10.2021 FLOW III Version 16.0

  RoyalArt - Agentur für Softwaregestaltung
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

  c Copyright by Dipl. Des. FH Sven Scholz - RoyalArt Agentur


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Dipl. Des. FH Sven Scholz, RoyalArt Agentur.
  Diese Software/Website ist eine Einzelplatzlizenz und für den Betrieb auf einem Speicherplatz 1 Installation berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.
  Diese Script darf nicht veroeffentlicht oder weitergeben werden. Es gilt das Urheberrecht.
  Diese Software darf nur mit schritflicher Genehmigung modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden. Der Kaufpreises wird nicht erstattet.
  Wer gegen die Lizenzbedingungen verstoesst insbesondere bei illegalem Vertrieb oder Mehrfachnutzung des Scriptes  muss mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/
/* global admin_url, admin_url_idx, template_url, parseInt, max_file_size, footer_link, site_width, tinymce, Livedesigner, langs, sel_lang, cronjob, NaN, edId */

var test       = 'test';
var cb         = '';
var editor_pos = 0;

// Ersatzt für alert()
function alertbox(msg, title, timer, single) {
   var no_animation = false;
console.log(single);
   if (single !== undefined) {
      Multibox.close('sofort');
      no_animation = true;
   }

   Multibox.content(msg);
   Multibox.width(350);

   if (title !== undefined && title !== '') {
      Multibox.title(title);
   }

   if (timer !== undefined && parseInt(timer) > 0) {
      Multibox.bg_close = true;
      Multibox.timer    = timer;
      Multibox.button();
   }

   else {
      Multibox.close_btn = true;
      Multibox.bg_close  = true;
   }

   if (single == undefined) {
      if ($('#multibox3').length) {
         Multibox.multibox4 = true;
      }

      else if ($('#multibox2').length) {
         Multibox.multibox3 = true;
      }

      else if ($('#multibox').length) {
         Multibox.multibox2 = true;
      }
   }

   if (no_animation) {
      Multibox.show(1);
   }

   else {
      Multibox.show();
   }

}

// Hintergrund kurzzeitig auf grün, dann wieder Original-Hintergrund
function showFeedback(el) {
   var color = $(el).css('background-color');

//   $(el).animate( { 'background-color' : 'rgb(187, 255, 0)' }, 250, function() {
   $(el).animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 200, function() {
      $(this).animate({'background-color' : color }, 100);
   });
}

var Confirmbox = {
   yes_function : '',
   yes_button   : 'Ja',
   no_button    : 'Nein',
   head         : 'Löschen',
   title        : 'Sind Sie sich sicher?',
   html         : '',
   center       : false,

   show: function() {
      // Confirmbox schließen, falls geöffnet
      if ($('html').hasClass('confirmbox_open')) {
         $('#confirmbox').remove();
         $('#confirmbox_bg').remove();
      }

      // BG und Box erstellen
      $('html').addClass('confirmbox_open');
      $('body').append('<div id="confirmbox_bg" style="display:none;"></div>');

      $('<div id="confirmbox"></div>').appendTo('body')
         .html(Confirmbox.html)
         .dialog({
            modal: false,
            title: Confirmbox.head,
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            buttons: [{
                  text : Confirmbox.yes_button,
                  id   : 'btnOk',
                  class: 'button_ci',
                  click : function() { Confirmbox.yesFunction(); }
               }
            ],
            close: function (event, ui) {
               Confirmbox.close();
            },
            closeText: "Schließen",
            closeOnEscape : true
         });

      $('#confirmbox_bg').css('opacity', 0).show().animate({opacity: 0.3}, 300);
      $('#confirmbox_bg').on('click', function() { Confirmbox.close(); });

      if (Confirmbox.center) {
         $('.ui-dialog-titlebar').addClass('center');
      }

      $('.ui-dialog-titlebar span').addClass('txt_tit');
      $('.ui-dialog').append('<div id="confirmbox_close" onclick="Confirmbox.close();"></div>').show().animate({opacity: 1}, 300);
   },

   close: function() {
      // $('#multibox').animate({opacity: 0}, 300, function() { $(this).remove(); });
      $('.ui-dialog').animate({opacity: 0}, 300, function() { $(this).remove(); });
      $('#confirmbox_bg').animate({opacity: 0}, 300, function() { $(this).remove(); });
      $('#confirmbox').animate({opacity: 0}, 300, function() { $(this).remove(); });
      Confirmbox.center       = false;
      Confirmbox.yes_function = '';
      Confirmbox.html         = '';
   },

   yesFunction: function() {
      var func = Confirmbox.yes_function;

      Confirmbox.yes_function = 'start';
      $('.ui-dialog').animate({opacity: 0}, 300, function() { $(this).remove(); });
      $('#confirmbox_bg').animate({opacity: 0}, 300, function() { $(this).remove(); });
      $('#confirmbox').animate({opacity: 0}, 300, function() { $(this).remove(); });
      eval('(' + func + ')');
   }
};

var Spinner = {
   on: function() {
      $('body').append('<div id="spinner" style="position:fixed; top:calc(50vh - 25px); left:calc(50vw - 25px); width:50px; height:50px; z-index:100000; text-align:center;"><i class="fas fa-spinner fa-spin" style="font-size:50px; width:100px;"></i></div>');
   },

   off: function() {
      $('#spinner').remove();
   }
};

// Allgemeiner Fileupload
// file_input    -> <input type="file" />
// target_url    -> URL, die den upload verarbeitet
// file_types    -> Liste von zulässigen extensions, z.B. 'png,jpg'
//
// param1        -> zusätzlicher Parameter 1 (optional)
// param2        -> zusätzlicher Parameter 2 (optional)
// param3        -> zusätzlicher Parameter 3 (optional)
// param4        -> zusätzlicher Parameter 4 (optional)
// param5        -> zusätzlicher Parameter 5 (optional)
// param6        -> zusätzlicher Parameter 6 (optional)
// lang          -> (optional)
// show_image    -> true: Hochgeladenes Bild anzeigen (optional)
// upload_target -> Ziel für hochgeladenes Bild (optional, nicht wenn show_image == true)
// callback      -> Callback-Funktion (optional)
//
// Kategorien / Kat-Import
// PDF-Katalog (module)
// Seiten (mit optional)
// Tools
function fileUpload(file_input, target_url, file_types, param1, param2, param3, param4, param5, param6, show_image, upload_target, callback) {
// console.log(target_url, file_types, param1, param2, param3, param4, param5, param6, show_image, upload_target, callback);
   cb            = null;
   var file      = $(file_input)[0].files[0];
   var file_name = file.name;
   var filesize  = file.size;
   var file_ext  = file_name.split('.').pop().toLowerCase();
//   cb            = (callback !== undefined ? window.callback : '');
//console.log(cb, typeof(window.cb), window.callback, callback !== undefined, callback);

   // Dateigröße mit max_upload_size vergleichen
   if (filesize > max_file_size) {
      alertbox('Dateigröße über '+Math.round(max_file_size / 1024 / 1024)+' MB');
      return;
   }

   if (file_types.includes('jpg')) {
      file_types += ',jpeg';
   }

   // Erw´eiterung überprüfen
   if (file_types !== '*' && !file_types.includes(file_ext)) {
      if (file_types.includes('jpg')) {
         if (param1 == 'startbild_video'){
            //starbild video alert message
            alertbox('Dateityp ist fasch, bitte jpg/mp4/mov hochladen.');
         }else{
            alertbox('Dateityp ist falsch, bitte jpg hochladen.');
         }
      }

      else {
         alertbox('Dateityp ist falsch.');
      }

      return false;
   }

   // Formulardaten für Upload erstellen
   var formData = new FormData();

   if (param1 !== undefined) { formData.append('param1', param1); }
   if (param2 !== undefined) { formData.append('param2', param2); }
   if (param3 !== undefined) { formData.append('param3', param3); }
   if (param4 !== undefined) { formData.append('param4', param4); }
   if (param5 !== undefined) { formData.append('param5', param5); }
   if (param6 !== undefined) { formData.append('param6', param6); }

   formData.append('file', file, file_name);

   // Upload-Element wieder entfernen
   $('#file_upload').remove();

   // Upload-Box erstellen und anzeigen
   var progress_box = '<div id="box_progress">\
                          <div id="show_progress">\
                             <div id="progress" class="ci_background" style="width:0%;" value="0"></div>\
                          </div>\
                        </div>';
   $('body').append(progress_box);
   $('#box_progress').animate({'opacity' : 1}, 100);

   // Daten übertragen
   $.ajax({
      async       : true,
      type        : 'post',
      url         : target_url,
      data        : formData,
      dataType    : 'json',
      processData : false,
      contentType : false,
       success: function (data) {



         $('#box_progress').animate({'opacity' : 0}, 1000, function() {
            $('#progress_box').remove();
         });

           if (data.status === 'ok') {

            $('#box_progress').remove();

            // Bild ersetzen
            if (show_image === true && upload_target !== undefined) {
               // img ist upload_target
                if (data.target === 'img_src') {


                  if ($('#'+upload_target).attr('src') !== undefined) {
                     $('#'+upload_target).attr('src', data.html);

                     // Social-Icons-Popup
                     if ($('#multibox .'+upload_target).length) {
                        $('#multibox .'+upload_target).attr('src', data.html);
                     }


                     if (param1 === 'slide') {
                        Design.slideshowCallback(param1, param2, data.img_normal, data.img_left, data.img_fullscreen, data.html);
                        return;
                     }

                     if (param1 === 'socialicon' && $('#multibox2').length) {
                        Livedesigner.socialIconCallback(data.html);
                        return;
                     }

                      if (param1 === 'artikelgrafik') {
                        Artikel.artikelgrafikCallback(data.html, param3);
                        return;
                     }

                      if (param1 === 'energyefficiency_image') {
                          Artikel.energyefficiency_imageCallback(data.html, param3);
                          return;
                      }

                     if (param1 === 'startbild_hover') {
                        $('#startbild_img').attr('data-hover', data.html);
                        return;
                     }

                     if (param1 === 'startbild') {
                        $('#startbild_img').attr('data-original', data.html);
                        $('#startbild_img').attr('data-src', data.html.replace('/pictures/', '/pictures/original/').replace('_tn.jpg', '.jpg'));
                        return;
                     }

                     if ($('#multibox').length) {
                        setTimeout(function() { Multibox.resize(); }, 500);
                     }


                  // img unterhalb upload_target
                  else {
                     $('img', $('#'+upload_target)).attr('src', data.html);
                  }
               }

               //
               else if (data.target === 'src') {
                  $('#'+upload_target).attr('src', data.html);
               }

               // Modul Musikplayer
               else if (data.target === 'musikplayer') {
                  Musikplayer.callbackMusikplayer(data.audio_id, data.html);
               }

               // Download-Artikel
               else if (data.target === 'download_article') {
                  Artikel.callbackUploadArticle(data.x_status, data.html, data.msg);
               }

               else {
                  $('#'+upload_target).html(data.html);
               }

               // Mega-Konfigurator Bild-Upload
               if (param1 === 'configurator_wert') {
                  Megakonfigurator.wertImgCallback(data);
               }

               // Mega-Konfigurator Bild-Upload
               if (param1 === 'werte_image') {
                  Artikel.wertImageUploadCallback(data);
               }

               return data.html;
            }
            }

            // Download-Artikel
            else if (data.target === 'download_article') {
               Artikel.callbackUploadArticle(data.x_status, data.html, data.msg);
            }

            // Download-Artikel
            else if (param1 === 'matrix_import') {
               Matrix.popup(Matrix.matrix_button, Matrix.matrix_id);
               return;
            }

            else if ((data.return === true || data.callback === true) && typeof(cb) === 'function') {
               cb(data.html, data);
            }

            else {
               $('#box_progress').remove();
               alertbox(data.msg, '', 3);
            }

            return data;
         }

         else if (data.status === 'error') {
            $('#box_progress').remove();
            alertbox(data.msg);
         }
      },
      error : function() {
         $('#box_progress').remove();
         alertbox('Fehler bei Übertragung');
      },

      // Upload-Fortschritt
      xhr: function() {
         var xhr = $.ajaxSettings.xhr();

         xhr.upload.onprogress = function(e) {
            var done  = e.position || e.loaded;
            var total = e.totalSize || e.total;

            if (total === 0) {
               total = 1;
            }

            var present = Math.floor(done/total*100);

            $('#progress').val(present);
            $('#progress').html(present+'%');
            $('#progress').css('width', present+'%');
         };

         xhr.upload.onload = function() {
            $('#progress_box').remove();
         };

        return xhr;
      }
   });
}

// Hilfsfunktionen zur Berechnung brutto / netto
function runden(value, stellen) {
   if (stellen === 1) {
      return (Math.round(value * 10) / 10).toFixed(stellen);
   }

   if (stellen === 3) {
      return (Math.round(value * 1000) / 1000).toFixed(stellen);
   }

   if (stellen === 4) {
      return (Math.round(value * 10000) / 10000).toFixed(stellen);
   }

   if (stellen === 5) {
      return (Math.round(value * 10000) / 10000).toFixed(stellen);
   }

   // Default 2 Stellen
   return (Math.round(value * 100) / 100).toFixed(stellen);
}

function komma2point(value) {
   if (value === '' || value === NaN || value === undefined) {
      value = 0;
   }

   var mystring = value.toString();
   return mystring.replace(',', '.');
}

function brutto2netto(el_b, el_n, tax) {
   var brutto = parseFloat(komma2point((el_b).val()));
   var steuer = parseFloat(tax);

   $(el_b).val(point2komma(runden(brutto, 2)));
   $(el_n).val(point2komma(brutto / (1 + steuer / 100)));
   $('#calc_new').show();
}

function netto2brutto(el_n, el_b, tax) {
   var netto  = parseFloat(komma2point((el_n).val()));
   var steuer = parseFloat(tax);

   $(el_n).val(point2komma(runden(netto, 2)));
   $(el_b).val(point2komma(runden(netto * (1 + steuer / 100), 2)));
   $('#calc_new').show();
}

function point2komma(value) {
   var mystring = value.toString();
   return mystring.replace('.', ',');
}
// Vermerken, ob Strg/Ctrl bei Click gedrückt
var cntrl_pressed = false;

function rgb2color(rgb) {
   var color = '#';
   var rgb_arr = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

   color += ("0" + parseInt(rgb_arr[1],10).toString(16)).slice(-2);
   color += ("0" + parseInt(rgb_arr[2],10).toString(16)).slice(-2);
   color += ("0" + parseInt(rgb_arr[3],10).toString(16)).slice(-2);

   return color;
}

function color2rgba(color, opacity) {
   var c;

   if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(color)){
      c= color.substring(1).split('');

      if(c.length === 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }

      c= '0x'+c.join('');
      return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+opacity+')';
    }

}

$(document).keydown(function(event){
   if (event.which === '17') {
      cntrl_pressed = true;
   }
});

$(document).keyup(function(){
    cntrl_pressed = false;
});

// Bestellung-Details, Kunden-Deteils
function checkStaat(id) {
   if (id === 0) {
      if ($('#staat option:selected').val() === '10') {
         $('#no_eu').show();
      }
      else {
         $('#no_eu').hide();
      }
   }

   if (id === 1) {
      if ($('#lf_staat option:selected').val() === '10') {
         $('#lf_noeu').show();
      }
      else {
         $('#lf_noeu').hide();
      }
   }
}

// Inhalt content in Container el einblenden
// 13.04.2019
function dataFadeIn (el, content) {
   $(el).animate( {'opacity' : 0 }, 250, function() {
      $(this).html(content).animate( {'opacity' : 1 }, 250);
   });
}


// Login / Forgotten
// 05.04.2019
function passForgotten() {
   $('#login_error').html('Überprüfung dauert bis zu 10s');

   $.post(admin_url_idx+'/ajax/shopinhaber/forgotten', {
      username : $('#username').val()
   }, function(data) {
      if (data.status === 'ok') {
         $('#login_error').html('');
         $('#login_msg').html(data.msg);
      }

      else {
         $('#login_error').html(data.msg);
      }

   }, 'json');
}

function getX(elem) {
   x = elem.offsetLeft;

   if (!elem.offsetParent) {
      return x;
   }
   else {
      return (x + getX(elem.offsetParent));
   }
}

function getY (elem) {
   y = elem.offsetTop;

   if (!elem.offsetParent) {
      return y;
   }

   else {
      return (y + getY(elem.offsetParent));
   }
}

// Helpdiv in DOM einfügen
// 16.05.2015
function initHelpdiv() {
   var helpdiv = document.createElement('div');
   helpdiv.id = 'helpdiv';
   document.body.appendChild(helpdiv);
}

// Helpdiv anzeigen (mouseover)
// 16.05.2019
function helptipOn(elem, helptext) {
   if(helptext.length) {
      var helpdiv = document.getElementById('helpdiv');
      helpdiv.innerHTML = helptext;

      helpdiv.style.display = 'block';

      var pageX = (document.all) ? document.body.offsetWidth : window.innerWidth;
      var pageY = (document.all) ? document.body.offsetHeight : window.innerHeight;

      var x1 = getX(elem);
      var y1 = getY(elem);

      var x2 = helpdiv.offsetWidth;
      var y2 = helpdiv.offsetHeight;

      helpdiv.style.top  = (y1 + 25) + 'px';
      helpdiv.style.left = (x1 + 25) + 'px';
   }
}

// Helpdiv ausblenden (mouseout)
// 16.05.2019
function helptipOff(){
   document.getElementById('helpdiv').style.display = 'none';
}

initHelpdiv();

// Jquery-UI / Tooltip aktivieren
// 16.05.2019
$( function() {
   if (typeof($(document).tooltip) === 'function') {
      $(document).tooltip({
         track : true,
         open : function(e, ui) {
            if ($('#multibox').length) {
               $('.ui-tooltip').css('background-color', '#ffffff');
            }
         }
      });
   }

   else {
      console.log('jQury-UI nicht geladen');
   }
});

// Minicolors
// 16.05.2019
$(function() {
//   if ($('#design_farben').length || $('#design_startseite').length) {
      $('input.minicolors').each( function() {
         $(this).minicolors({
            control: $(this).attr('data-control') || 'wheel',
            opacity: $(this).attr('data-opacity'),
            swatchPosition: $(this).attr('data-position') || 'right',
            change: function(hex, opacity) {
               if( opacity ) {
                  $(this).parent().parent().find('.opacity').val(opacity);
                  $(this).prop('title', 'Deckkraft: '+parseInt(opacity * 100)+'%');
               }
            }
         });
      });
//   }
});

function showImage(img, parent_id) {
   if (img === '') {
      return;
   }

   Multibox.content('<div class="img_wrapper" style="min-width:100px; min-height:50px;"><img class="multibox_image" src="'+img+'" onclick="Multibox.close();" /><div id="image_parent_id" class="button"></div></div>');
   Multibox.bg_close = true;
   Multibox.close_btn = true;
   Multibox.width('auto');
   Multibox.style = 'display:none;';
   Multibox.show();

   if (parent_id !== undefined) {
      $('#image_parent_id').html(parent_id);
   }

   $('#multibox').animate({'opacity' : 0.5}, 250, function() {
      Multibox.resize();
      $(this).animate({'opacity' : 1}, 250);
   });

}

// Fehler beim Laden von Header / admin_button4 !!! funktioniert nicht
$(function() {
   $('#admin_button4_img').on('error', function() {
     $('#admin_button4').css('display', 'none');
   });
});

// Bilder nachladen: data-src -> src
$(window).on('load', function() {
   $('.load_image').each(function() {
      $(this).attr('src', $(this).attr('data-src'));
   });
});

$(function() {
   if ($('#iframe').length) {
      $.get(footer_link,
      function(data) {
         $('#iframe').html(data);
      }, 'html' );
   }
});

function editorStart() {
   editor_pos = window.scrollY;

   if (editor_pos) {
      window.scroll(0, 0);
   }
}

function editorEnd() {
   if (editor_pos) {
      window.scroll(0, editor_pos);
      editor_pos = 0;
   }
}

$(function() {
   if ($('#zahlungsart').length || $('#module_amazonorders').length) {
      $('.pw_auge').each( function() {
         $(this).parent().append('<span class="pw_auge_show far fa-eye"></span>');
         $('.pw_auge_show', $(this).parent()).on('click', function() {
            // Anzeigen
            if ($('input', $(this).parent()).attr('type') === 'password') {
               $('input', $(this).parent()).attr('type', 'text');
               $('.pw_auge_show', $(this).parent()).removeClass('fa-eye').addClass('fa-eye-slash');
            }

            else {
               $('input', $(this).parent()).attr('type', 'password');
               $('.pw_auge_show', $(this).parent()).removeClass('fa-eye-slash').addClass('fa-eye');
            }
         });
      });
   }
});// Mehrzweckbox
// 05.05.2019
var Multibox = {
//   status    : false,
   bg_close  : false,
   close_btn : false,
   f_width   : '500px',
   f_title   : '',
   f_content : '',
   f_button  : '',
   style     : '',
   timer     : 0,
   interval  : null,
   multibox2 : false,
   multibox3 : false,
   multibox4 : false,

   // Title ohen Close-Button
   title : function(title) {
      this.f_title = '<h1 class="txt_tit">'+title+'</h1>';
   },

   content : function(content) {
      this.f_content = '<div id="multibox_content">'+content+'</div>';
   },

   // Für alertbox / Timer
   button : function() {
      this.f_button = '<br /><br /><div id="multibox_button"><div class="button" onclick="Multibox.close();">schließen<span id="multibox_timer"></span></div></div>';
   },

   width : function(width) {
      this.f_width = (width === 'auto' ? width : width+'px');
   },

   show : function(no_animation) {
console.log(Multibox.multibox2, Multibox.multibox3, Multibox.multibox4)
      // Multibox schließen, falls geöffnet
      if (!Multibox.multibox2 && $('body').hasClass('multibox_open')) {
         $(window).off('resize', Multibox.resize);
         $('#multibox').remove();
         $('#multibox_bg').remove();
      }

      // Multibox (1)
      if (!Multibox.multibox2 && !Multibox.multibox3 && !Multibox.multibox4) {
         $('body').append('<div id="multibox_bg" style="display:none;"'+(this.bg_close ? ' onclick="Multibox.close();"' : '')+'></div>');

         var rand = window.innerWidth - document.body.clientWidth;

         if (rand > 0) {
            $('body').addClass('multibox_margin');
         }

         $('body').addClass('multibox_open');
      }

      // Multibox2
      else if (Multibox.multibox2 && !Multibox.multibox3 && !Multibox.multibox4) {
         $('body').append('<div id="multibox2_bg" style="display:none;"'+(this.bg_close ? ' onclick="Multibox.close();"' : '')+'></div>');
      }

      // Multibox3
      else if(Multibox.multibox2 && Multibox.multibox3 && !Multibox.multibox4) {
         $('body').append('<div id="multibox3_bg" style="display:none;"'+(this.bg_close ? ' onclick="Multibox.close();"' : '')+'></div>');
      }

      // Multibox4
      else {
         $('body').append('<div id="multibox4_bg" style="display:none;"'+(this.bg_close ? ' onclick="Multibox.close();"' : '')+'></div>');
      }

      $('body').append('<div id="multibox'+(Multibox.multibox4 ? 4 : (Multibox.multibox3 ? 3 : (Multibox.multibox2 ? 2 : '')))+'" style="width:'+this.f_width+';'+(this.style !== '' ? '+this.style+' : '')+'">'+
                          (this.close_btn ? '<div id="multibox_close" onclick="Multibox.close();"></div>' : '') +
                          '<div class="multibox_inner">'+
                             '<div id="multibox_content_wrapper">'+
                                // this.h_image+(this.h_image !== '' ? '<div class="close" onclick="Multibox.close();"></div>' : '')+
                                this.f_title+
                                this.f_content+
                                this.f_button+
                             '</div>'+
                          '</div>'+
                       '</div>');

      if (this.f_title !== '') {
         // $('#multibox').css('padding-top', '54px');
      }

      if (this.f_button !== '') {
         // $('#multibox').css('padding-bottom', '54px');
      }

      if (this.timer !== 0) {
         $('#multibox_timer').html(' ('+Multibox.timer+')');
         Multibox.interval = setInterval(Multibox.checkTimer, 1000);
      }

      // Event für resize hinzufügen
      $(window).on('resize', function() { Multibox.resize(); });
      Multibox.resize();

      // Multibox anzeigen
      if (!Multibox.multibox2 && !Multibox.multibox3 && !Multibox.multibox4) {
         if (no_animation === undefined) {
            $('#multibox_bg').css('opacity', 0).show().animate({opacity: 0.3}, 300);
            $('#multibox').show().animate({opacity: 1}, 300);
         }

         else {
            $('#multibox_bg').css('opacity', 0.3).show();
            $('#multibox').show().css('opacity', 1);
         }
      }

      // 2.Popup anzeigen
      else if (!Multibox.multibox3 && !Multibox.multibox4) {
         $('#multibox2_bg').css('opacity', 0).show().animate({opacity: 0.3}, 300);
         $('#multibox2').show().animate({opacity: 1}, 300);
      }

      // 3.Popup anzeigen
      else if (!Multibox.multibox4) {
         $('#multibox3_bg').css('opacity', 0).show().animate({opacity: 0.3}, 300);
         $('#multibox3').show().animate({opacity: 1}, 300);
      }

      // 4.Popup anzeigen
      else {
         $('#multibox').animate({opacity: 0.3,  transform: 'rotate3d(20, 10, 0, 75deg)'}, 300);
         $('#multibox4_bg').css('opacity', 0).show().animate({opacity: 0.3}, 300);
         $('#multibox4').show().animate({opacity: 1}, 300);
      }
   },

   close: function(sofort) {
      // Multibox entfernen , Status auf false;
      if ($('body').hasClass('multibox_open')) {
         // Multibox schließen
         if (!Multibox.multibox2 && !Multibox.multibox3 && !Multibox.multibox4) {
            $(window).off('resize', Multibox.resize);

            if (sofort === undefined) {
               $('#multibox').animate({opacity: 0}, 300, function() { $(this).remove(); });
               $('#multibox_bg').animate({opacity: 0}, 300, function() { $(this).remove(); });
               $('body').removeClass('multibox_open');
               $('body').removeClass('multibox_padding');
            }

            else {
               $('#multibox').remove();
               $('#multibox_bg').remove();
               $('body').removeClass('multibox_open');
               $('body').removeClass('multibox_padding');
            }

            Multibox.bg_close  = false;
            Multibox.close_btn = false;
            Multibox.f_width   = '500px';
            Multibox.f_title   = '';
            Multibox.f_content = '';
            Multibox.f_button  = '';
            Multibox.timer     = 0;
         }

         // 4. Popup schließen
         else if ($('#multibox4').length) {
            $('#multibox3').animate({opacity: 1, transform: 'rotate3d(0)'}, 300);
            $('#multibox4').animate({opacity: 0}, 300, function() { $(this).remove(); });
            $('#multibox4_bg').remove();
            Multibox.multibox4 = false;
         }

         // 3. Popup schließen
         else if ($('#multibox3').length) {
            $('#multibox2').animate({opacity: 1, transform: 'rotate3d(0)'}, 300);
            $('#multibox3').animate({opacity: 0}, 300, function() { $(this).remove(); });
            $('#multibox3_bg').remove();
            Multibox.multibox3 = false;
         }

         // 2. Popup schließen
         else {
            $('#multibox').animate({opacity: 1, transform: 'rotate3d(0)'}, 300);
            $('#multibox2').animate({opacity: 0}, 300, function() { $(this).remove(); });
            $('#multibox2_bg').remove();
            Multibox.multibox2 = false;
         }

         editorEnd();
      }
   },

   resize: function() {
      $('#multibox_content').css('height', 'auto');
      $('#multibox').css('bottom', 'unset');
      var $width     = $(window).width();
      var $height    = $(window).height();
      var box_width  = $('#multibox').outerWidth();
      var box_height = $('#multibox').outerHeight();
      var height     = Math.floor(($height - box_height) / 2);
      var scrollbar  = $('#multibox_inner').scrollWidth > $('#multibox_inner').clientWidth;

      // Bei horiz. Scrollbalken
      if ($('#multibox_content')[0] !== 'undefined' && $('#multibox_content')[0].length && $('#multibox_content')[0].scrollWidth > $('#multibox_content')[0].clientWidth) {
         box_width += ($('#multibox_content')[0].scrollWidth - $('#multibox_content')[0].clientWidth);
         $('#multibox').css('width', box_width+'px');
      }

   if (height < 0 ) {
         height = 10;
      }

      if (height <= 10) {
         $('#multibox').css('bottom', 10);
      }

      else {
         $('#multibox').css('bottom', 'unset');
      }

      $('#multibox').css('top', height);
      $('#multibox').css('left', Math.floor(($width - box_width) / 2));

      if (Multibox.multibox2) {
         $('#multibox2_content').css('height', 'auto');
         $('#multibox2').css('bottom', 'unset');
         var box_width  = $('#multibox2').outerWidth();
         var box_height = $('#multibox2').outerHeight();
         var height     = Math.floor(($height - box_height) / 2);

         if (height < 0 ) {
            height = 10;
         }

         if (height <= 10) {
            $('#multibox2').css('bottom', 10);
         }

         else {
            $('#multibox2').css('bottom', 'unset');
         }

         $('#multibox2').css('top', height);
         $('#multibox2').css('left', Math.floor(($width - box_width) / 2));
      }

      if (Multibox.multibox3) {
         $('#multibox3_content').css('height', 'auto');
         $('#multibox3').css('bottom', 'unset');
         var box_width  = $('#multibox3').outerWidth();
         var box_height = $('#multibox3').outerHeight();
         var height     = Math.floor(($height - box_height) / 2);

         if (height < 0 ) {
            height = 10;
         }

         if (height <= 10) {
            $('#multibox3').css('bottom', 10);
         }

         else {
            $('#multibox3').css('bottom', 'unset');
         }

         $('#multibox3').css('top', height);
         $('#multibox3').css('left', Math.floor(($width - box_width) / 2));
      }

      if (Multibox.multibox4) {
         $('#multibox4_content').css('height', 'auto');
         $('#multibox4').css('bottom', 'unset');
         var box_width  = $('#multibox4').outerWidth();
         var box_height = $('#multibox4').outerHeight();
         var height     = Math.floor(($height - box_height) / 2);

         if (height < 0 ) {
            height = 10;
         }

         if (height <= 10) {
            $('#multibox4').css('bottom', 10);
         }

         else {
            $('#multibox4').css('bottom', 'unset');
         }

         $('#multibox4').css('top', height);
         $('#multibox4').css('left', Math.floor(($width - box_width) / 2));
      }
   },

   showLoading: function() {

   },

   hideLoading: function() {
      Multibox.close();
   },

   checkTimer: function() {
      Multibox.timer--;

      if (Multibox.timer < 1) {
         clearInterval(Multibox.interval);
         Multibox.close();
      }

      else {
         $('#multibox_timer').html(' ('+Multibox.timer+')');
      }
   }
};


var Bestellungen = {
   // Bestellungen-Liste Inhalt / Pager neu anzeigen
   // 05.12.2018
   liste: function() {
//      $.post(admin_url_idx+'/ajax/artikel/addBestellung', {
      $.post(admin_url_idx+'/ajax/bestellungen/liste', {
      },
      function(data) {
         if (data.status === 'ok') {
            $('#bestell_liste').html(data.inhalt);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);

            $('#find_reset').hide();
            $('#suche').val('');
         }
      }, 'json' );
   },

   // Bestellungen pro Seite setzen und Ajax-Reload
   // 05.12.2018
   count: function(count) {
      $.post(admin_url_idx + '/ajax/bestellungen/count', {
         count: count
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Bestellungen.liste();
         }

         else {
            console.log('error Bestellungen::count');
         }
      }, 'json' );
   },

   // Angezeigte Seite ändern und AJAX-Reload
   // 29.12.2018
   seite: function(seite) {
      $.post(admin_url_idx + '/ajax/bestellungen/seite', {
         seite: seite
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Bestellungen.liste();
         }

         else {
            console.log('error Bestellungen::seite');
         }
      }, 'json' );
   },

   // Bestellung suchen
   // 05.12.2018
   find : function(search, all) {
      $.post(admin_url_idx+'/ajax/bestellungen/find', {
         search : search,
         all : all
      },
      function(data) {
         if (data.status === 'ok') {
            $('#bestell_liste').html(data.inhalt);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);

            $('#find_reset').show();
         }

         else {
            console.log('error Bestellungen::find');
         }
      }, 'json' );
   },

   // Sortierung anzeigen und Seite neu laden
   // 05.12.2018
   sort: function (id, user_id) {
      var haendler_id = 0;

      // Portal
      if (user_id === 0) {
         if ($('#haendler_id').length) {
            haendler_id = $('#haendler_id').val();
         }
      }

      var asc = '';

      // Pfeile in Titelzeile setztn und Sortierrichtung herausfinden
      var el = $('#art_sort'+id+'_symbol');

      if (el.hasClass('fa-sort-up')) {
         asc = 'desc';
         el.attr('class', 'list_icon sort-desc fas fa-sort-down');
      }

      else {
         asc = 'asc';
         el.attr('class', 'list_icon sort-asc fas fa-sort-up');
      }

      for (var i = 1; i < 8; i++) {
         if ( i !== id) {
            $('#art_sort'+i+'_symbol').attr('class', 'list_icon sort-no fas fa-sort');
         }
      }

      $.post(admin_url_idx + '/ajax/bestellungen/sort', {
         dir         : asc,
         sort        : id,
         user_id     : user_id,
         haendler_id : haendler_id
      },
      function(data) {
         if (data.status === 'ok') {
            dataFadeIn($('#bestell_liste'), data.inhalt);
//            $('#bestell_liste').html(data.inhalt);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);
         }

         else {
            console.log('error Bestellungen::sort');
         }
      }, 'json');
   },

   // Bestellung löschen
   // 25.12.2018
   delete: function(id) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Bestellung Löschen?';
         Confirmbox.yes_function = 'Bestellungen.delete('+id+')';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/bestellungen/delete', {
         id: id
      },
      function(data) {
         if (data.status === 'ok') {
// ??? Portal           if (haendler_id > 0) {
//               $('#form_admin').submit();
//            }

            // Liste und Pager neu laden
            Bestellungen.liste();
         }

         else {
            console.log('error Bestellungen::delete');
         }
      }, 'json' );
   },

   // Bestellungen bei Ebay abholen
   // 28.02.2019
   getEbayBest: function() {
      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.content('Bestellungen werden von Ebay abgeholt</div><br /><br /><div>Dies kann mehrere Minuten dauern.');
      Multibox.width(400);
      Multibox.show();

      $.post(admin_url_idx+'/ajax/bestellungen/getEbayBest', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.msg);
            Multibox.show();
         }

         else {
            Multibox.content(data.msg);
            Multibox.show();
         }
      }, 'json');
   },

   // Bestellungen bei Dawanda abholen
// TESTEN
   DELgetDawandaBest: function() {
      alertbox('Bestellungen werden von Dawanda abgeholt</div><br /><br /><div>Dies kann mehrere Minuten dauern.');
      $.post(admin_url_idx+'/ajax/bestellungen/getDawandaBest', {
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Bestellungen bei Amazon abholen
// TESTEN
   getAmazonBest: function() {
      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.content('Bestellungen werden von Amazon abgeholt</div><br /><br /><div>Dies kann mehrere Minuten dauern.');
      Multibox.width(400);
      Multibox.show();

      $.post(admin_url_idx+'/ajax/bestellungen/getAmazonBest', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.msg);
            Multibox.show();
            Bestellungen.liste();
         }
         else {
            Multibox.content(data.msg);
            Multibox.show();
         }
      }, 'json');
   },

   // Bestellungen abholen bei Billbee anstoßen
   // In der Doku vorgesehen - funktioniert noch nicht
   billbeeSync: function() {
      $.post(admin_url_idx + '/ajax/bestellungen/billbeeSync', {
      },
      function(data) {
         if (data.status === 'ok') {
            alertbox(data.html);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Modul Bestellzusammenfassung - Bündeln
// TESTEN
   bestellungCollector: function(id) {
      var myForm = document.createElement('form');
      myForm.action = admin_url_idx + "/bestellungen/collector";
      myForm.method = 'post';
      myForm.id = 'myform';

      var myInput = document.createElement('input');
      myInput.name = 'id';
      myInput.type = 'hidden';
      myInput.value = id;

      myForm.appendChild(myInput);
      document.body.appendChild(myForm);
      document.getElementById('myform').submit();
      document.body.removeChild(myForm);
      return;
   },


   save: function() {
      $('input').attr('disabled', false);
      $('select').attr('disabled', false);
      $('#mode').val('speichern');
      $('#form_best_details').submit();
   },

   // Popup Status
   // 15.07.2019
   popup: function(id) {
      $.post(admin_url_idx + '/ajax/bestellungen/popup', {
         id : id
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(350);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      },'json' );
   },

   // Rabatt neu berechnen
   // 28.02.2019
   rabatt: function (mode, ust) {
      var summe          = parseFloat(komma2point($('#summe').val()));
      var rabatt_prozent = parseFloat(komma2point($('#rabatt_prozent').val()));
      var rabatt_betrag  = parseFloat(komma2point($('#rabatt_betrag').val()));

      // Prozent geändert
      if (mode === 0) {
         rabatt_betrag = summe * (rabatt_prozent / 100);
      }

      if (mode === 1) {
         rabatt_prozent = (rabatt_betrag / summe * 100);
      }

      $('#rabatt_prozent').val(point2komma(runden(rabatt_prozent, 4)));
      $('#rabatt_betrag').val(point2komma(runden(rabatt_betrag, 2)));
      $('#rabatt_betrag_brutto').val(point2komma(runden(rabatt_betrag * (1 + ust / 100), 2)));
   },

   // Popup Artikel hinzufügen laden
   // 19.02.2019
   addArticle: function() {
      if ($('#bearbeiten').val() === 'y') {
         $.post(admin_url_idx + '/artikel/listePopup',
         {
            listmode : 'bestellungen'
         },
         function(data) {
            if (data.status === 'ok') {
               Multibox.content(data.html);
               Multibox.width(site_width + 14);
               Multibox.bg_close  = true;
               Multibox.close_btn = true;
               Multibox.show();
            }
         },'json' );
      }

      else {
         alertbox('Bearbeitung erst nach Freigabe möglich. Schließen Sie diese Meldung und klicken Sie oben links auf &bdquo;bearbeiten&ldquo;.');
      }
   },

   // Artikel zur Bestellung hinzufügen (aus Popup)
   // 28.02.2019
   bestellungAdd: function(art_id) {
      $.post(admin_url_idx + '/bestellungen/bestellungAdd', {
         re_id       : $('#id').val(),
         art_id      : art_id,
         porto       : $('#porto').val(),
         zahlart_add : $('#zahlart_add').val()
      },
      function(data) {
         Multibox.close();

         if (data.status === 'ok') {
            $('#artikel_list').html(data.html);
            Bestellungen.bearbeiten();
         }

         else if (data.status === 'vorhanden') {
            //alert('Artikel bereits in der Rechnung. Bitte Menge ändern');
            $('#artikel_list').animate({'opacity': 1}, 500 , function() {
               alertbox(data.msg);
            });
         }

         else {
//            Multibox.multibox2 = true;
            $('#artikel_list').animate({'opacity': 1}, 500 , function() {
               alertbox('Fehler bei der Verarbeitung');
            });
         }
      },'json' );
   },

   // Bestellung zum Bearbeiten freigeben (Button Bearbeiten)
   // 28.02.2019
   bearbeiten: function() {
      var is_storno = $('#is_storno').val();

      if (is_storno !== 'y') {
         $('input[type="text"]').each(function() {
            if ($(this).prop('readonly')) {
               $(this).prop('readonly', false);
            }
         });

         $('select').each(function() {
            if ($(this).prop('disabled')) {
               $(this).prop('disabled', false);
            }
         });

         $('input[type="radio"]').each(function() {
            if ($(this).prop('disabled')) {
               $(this).prop('disabled', false);
            }
         });

         $('input[type="text"]').each(function() {
            if ($(this).prop('readonly')) {
               $(this).prop('readonly', false);
            }
         });


         $('#artikel_list input[type="checkbox"]').each(function() {
            if ($(this).prop('disabled')) {
               $(this).prop('disabled', false);
            }
         });

         $('.muell').show();
         $('#article_add').show();
         $('textarea').prop('readonly', false);
         $('#button_bearbeiten').css('visibility', 'hidden');
         $('#bearbeiten').val('y');
      }
   },

   // Rechner / Menge berechnen
   // 28.02.2019
   checkMenge: function(id) {
      var komma = parseInt($('#rechner_komma_'+id).val());
      var breite = 1;
      var hoehe  = 1;
      var tiefe  = 1;

      if ($('#breite_'+id).val()) {
         breite = parseFloat(komma2point($('#breite_'+id).val()));
      }

      if ($('#hoehe_'+id).val() !== '') {
         hoehe = parseFloat(komma2point($('#hoehe_'+id).val()));
      }

      var tiefe = $('#tiefe_'+id).val();
      if ($('#tiefe_'+id).val() !== '') {
         tiefe = parseFloat(komma2point($('#tiefe_'+id).val()));
      }

      var sum = Math.round(breite * hoehe * tiefe * Math.pow(10, komma)) / Math.pow(10, komma);
      var summe = sum.toFixed(komma);
      $('#menge_'+id).val(point2komma(summe));
   },

   // Popup Printconfig anzeigen
   // 20.02.2019
   printconfig: function() {
      $.post(admin_url_idx + '/bestellungen/getPrinterconfig', {
         },
         function(data) {
            if (data.status === 'ok') {
               Multibox.bg_close  = true;
               Multibox.close_btn = true;

               Multibox.content(data.html);
               Multibox.width(776);
               Multibox.show();
               Bestellungen.printPreview();
            }
         }, 'json'
      );

      //
      return false;
   },

   // Popup Printconfig soeichern
   // 20.02.2019
   savePrintconfig: function() {
      $.post(admin_url_idx + '/bestellungen/savePrinterconfig', {
         print_dhl_left        : $('#print_dhl_left').val(),
         print_dhl_top         : $('#print_dhl_top').val(),

         print_hermes_left     : $('#print_hermes_left').val(),
         print_hermes_top      : $('#print_hermes_top').val(),

         print_dpd_left        : $('#print_dpd_left').val(),
         print_dpd_top         : $('#print_dpd_top').val(),
         print_dpd_land        : $('#print_dpd_land').val(),
         print_dpd_klasse      : $('#print_dpd_klasse option:selected').val(),

         print_gls_left        : $('#print_gls_left').val(),
         print_gls_top         : $('#print_gls_top').val(),
         print_gls_inhalt      : $('#print_gls_inhalt').val(),
         print_gls_klasse      : $('#print_gls_klasse option:selected').val(),

         print_etikett_left    : $('#print_etikett_left').val(),
         print_etikett_top     : $('#print_etikett_top').val(),
         print_etikett_x       : $('#print_etikett_x').val(),
         print_etikett_y       : $('#print_etikett_y').val(),
         print_etikett_offsetx : $('#print_etikett_offsetx').val(),
         print_etikett_offsety : $('#print_etikett_offsety').val(),
         print_etikett_spalten : $('#print_etikett_spalten').val(),
         print_etikett_zeilen  : $('#print_etikett_zeilen').val(),
         print_etikett_dirup   : ($('#print_etikett_dirup').is(':checked') ? 'on' : 'off')
         },
         function(data) {
            Multibox.close();
         }, 'json'
      );
   },

   // Popup Printconfig Etiketten anzeigen
   // 20.02.2019
   printPreview: function() {
      var zeilen  = parseInt($('#print_etikett_zeilen').val());

      if (zeilen > 10) {
         zeilen = 10;
         $('#print_etikett_zeilen').val(10);
      }

      if (zeilen < 2) {
         zeilen = 2;
         $('#print_etikett_zeilen').val(2);
      }

      var spalten = parseInt($('#print_etikett_spalten').val());

      if (spalten > 4) {
         spalten = 4;
         $('#print_etikett_spalten').val(4);
      }

      if (spalten < 2) {
         spalten = 2;
         $('#print_etikett_spalten').val(2);
      }

      var dir = ($('#print_etikett_dirup').is(':checked') ? true : false);

      var pos_x   = parseInt($('#print_etikett_x').val());
      var pos_y   = parseInt($('#print_etikett_y').val());
      var pos     = (pos_y - 1) * spalten + pos_x;
      var akt_pos = 0;

      var breite  = Math.floor((150 - spalten - 1) / spalten );
      var hoehe   = Math.floor((227 - zeilen - 1) / zeilen );

      var html = '<table>';

      for (var z = 1; z <= zeilen; z++) {
         html += '<tr>';

         for (var s = 1; s <= spalten; s++) {
            akt_pos++;
            if (dir) {
               html += '<td data-zeile="'+z+'" data-spalte="'+s+'" style="width:'+breite+'px; height:'+hoehe+'px;"'+(akt_pos >= pos ? ' class="etikett_free"' : '')+' onclick="Bestellungen.printpos(this);">'+(akt_pos === pos ? 'Start' : '')+'</td>';
            }
            else {
               html += '<td data-zeile="'+z+'" data-spalte="'+s+'" style="width:'+breite+'px; height:'+hoehe+'px;"'+(akt_pos <= pos ? ' class="etikett_free"' : '')+' onclick="Bestellungen.printpos(this);">'+(akt_pos === pos ? 'Start' : '')+'</td>';
            }
         }

         html += '<tr>';
      }

      html += '</table>';
      $('#print_preview').html(html);
   },


   // Popup Printconfig Etiketten Startposition festlegen
   // 20.02.2019
   printpos: function(el) {
      $('#print_etikett_x').val($(el).data('spalte'));
      $('#print_etikett_y').val($(el).data('zeile'));
      Bestellungen.printPreview();
   },

   // Drucken (bei Click)
   // 19.02.2019
   drucken: function(re_id) {
      var mode = parseInt($('#printconfig option:selected').val());

      // Wenn nicht Adressaufkleber sammeln
      if (mode !== 12) {
         Bestellungen.lieferschein(re_id, mode);
         return;
      }

      $.post(admin_url_idx+'/bestellungen/drucken', {
         mode  : mode,
         test  : 'test',
         re_id : re_id
      },
      function(data) {
         if (data.status === 'ok') {
            $('#printconfig option[name="refresh"]').text(data.option);
            alert(data.msg);
         }
      },'json' );
   },

   // Lieferschein drucken (PDF), Formular dafür erstellen
   // 19.02.2019
   lieferschein: function(re_id, mode) {
      // Wenn Lieferdatum leer, aktuelles Datum setzen
      if ($('#lieferdatum').val() === '') {
         var z = new Date();
         $('lieferdatum').val((z.getDate() < 10 ? "0"+ z.getDate() : z.getDate()) +'.'+(z.getMonth() < 9 ? "0"+ (z.getMonth()+1) : (z.getMonth()+1)) +'.'+z.getFullYear());
      }
      var myForm    = document.createElement('form');
      myForm.action = admin_url_idx + "/bestellungen/drucken";
      myForm.method = 'post';
      myForm.id     = 'myform';

      var myInput   = document.createElement('input');
      myInput.name  = 're_id';
      myInput.type  = 'hidden';
      myInput.value = re_id;
      myForm.appendChild(myInput);

      var myInput   = document.createElement('input');
      myInput.name  = 'mode';
      myInput.type  = 'hidden';
      myInput.value = mode;
      myForm.appendChild(myInput);

      var myInput   = document.createElement('input');
      myInput.name  = 'lieferdatum';
      myInput.type  = 'hidden';
      myInput.value = document.getElementById('lieferdatum').value;
      myForm.appendChild(myInput);

      document.body.appendChild(myForm);
      document.getElementById('myform').submit();
      document.body.removeChild(myForm);
   },

   // Staat2 anzeigen, wenn Außerhalb EU
   checkStaat: function(id) {
      if (id === 0) {
         if ($('#staat option:selected').val() === '10') {
            $('#no_eu').show();
         }

         else {
            $('#no_eu').hide();
         }
      }

      if (id === 1) {
         if ($('#lf_staat option:selected').val() === '10') {
            $('#lf_no_eu').show();
         }

         else {
            $('#lf_no_eu').hide();
         }
      }
   },

   dummy: function() {

   }
};

// 'Autostart' bei Seitenaufruf
$(function() {
   // Bestellung Liste
   if ($('#bestellung_liste').length) {
      $('.has_pdf').each(function () {
         $(this).mousedown(function(event) {
            switch (event.which) {
               case 1:
                  $(this).closest('form').find('input[name="mode"]').val('admin');
                  // $(this).click();
                  break;
               case 3:
                  $(this).closest('form').find('input[name="mode"]').val('kunde');
                  $(this).click();
                  break;
               default:
                  return;
            }
          });
      });
   }

   // Bestellung Details
   if ($('#bestellung_detail').length) {
      $('#staat').change(function() { Bestellungen.checkStaat(0); });
      $('#lf_staat').change(function() { Bestellungen.checkStaat(1); });

      if ($('#staat option:selected').val() === '10') {
         $('#no_eu').show();
      }

      else {
         $('#no_eu').hide();
      }

      if ($('#lf_staat option:selected').val() === '10') {
         $('#lf_no_eu').show();
      }

      else {
         $('#lf_no_eu').hide();
      }
   }
});



var Kunden = {
   // Liste -> Details: Direktaufruf

   // Kundenliste laden
   // 25.12.2018
   liste: function() {
      $.post(admin_url_idx + '/ajax/kunden/liste', {
         }, function(data) {
            if (data.status === 'ok') {
               dataFadeIn($('#kundenListe'), data.inhalt);
               $('#pager_oben').html('<div class="pager">'+data.pager+'</div>');
               $('#pager_unten').html('<div class="pager">'+data.pager+'</div>');
            }

         else {
            console.log('error Kunden::liste');
         }
         }, 'json'
      );
   },

   // Bestellung suchen
   // 25.12.2018
   find : function(search, all) {
      $.post(admin_url_idx+'/ajax/kunden/find', {
         search : search,
         all : all
      },
      function(data) {
         if (data.status === 'ok') {
            $('#kundenListe').html(data.inhalt);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);

            $('#find_reset').show();
         }

         else {
            console.log('error Kunden::find');
         }
      }, 'json' );
   },

   // Kunden pro Seite setzen und Ajax-Reload
   // 25.12.2018
   count: function(count) {
      $.post(admin_url_idx + '/ajax/kunden/count', {
         count: count
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Kunden.liste();
         }

         else {
            console.log('error Kunden::count');
         }
      }, 'json' );
   },

   // Angezeigte Seite ändern und AJAX-Reload
   // 25.12.2018
   seite: function(seite) {
      $.post(admin_url_idx + '/ajax/kunden/seite', {
         seite : seite
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Kunden.liste();
         }

         else {
            console.log('error Kunden::seite');
         }
      }, 'json' );
   },

   // Sortierung anzeigen und Seite neu laden
   // 25.12.2018
   sort: function (id) {
      var asc = '';

      // Pfeile in Titelzeile setztn und Sortierrichtung herausfinden
      var el = $('#art_sort'+id+'_symbol');

      if (el.hasClass('fa-sort-up')) {
         asc = 'desc';
         el.attr('class', 'list_icon sort-desc fas fa-sort-down');
      }

      else {
         asc = 'asc';
         el.attr('class', 'list_icon sort-asc fas fa-sort-up');
      }

      for (var i = 1; i < 8; i++) {
         if ( i !== id) {
            $('#art_sort'+i+'_symbol').attr('class', 'list_icon sort-no fas fa-sort');
         }
      }

      $.post(admin_url_idx + '/ajax/kunden/sort', {
         dir         : asc,
         sort        : id
      },
      function(data) {
         if (data.status === 'ok') {
            dataFadeIn($('#kundenListe'), data.inhalt);
//            $('#kundenListe').html(data.inhalt);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);
         }

         else {
            console.log('error Kunden::sort');
         }
      }, 'json');
   },

   // Bestellung löschen
   // 25.12.2018
   delete: function(id) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Kunde löschen?';
         Confirmbox.yes_function = 'Kunden.delete('+id+')';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/kunden/delete', {
         user_id: id
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Kunden.liste();
         }

         else {
            console.log('error Kunden::delete');
         }
      }, 'json' );
   },



   // Kunde sperren + Symbole ändern
   // 25.12.2018
   freischalten: function(el, id) {
      var gesperrt = 'y';

      // Wenn gesperrt -> freigeben
      if ($(el).hasClass('fa-times')) {
         gesperrt = 'n';
      }

      $.post(admin_url_idx+'/ajax/kunden/gesperrt', {
         user_id : id,
         gesperrt : gesperrt
      }, function(data) {
         if (data.status === 'ok') {
            if (data.gesperrt === 'y') {
               $(el).removeClass('fa-check').addClass('fa-times');
               $(el).attr('title', 'Kunde sperren');
            }

            else {
               $(el).removeClass('fa-times').addClass('fa-check');
               $(el).attr('title', 'Kunde freischalten');
            }
         }
      }, 'json'
      );


      return;
   },


   // Email darf nicht leer sein
   // 02.03.2019
   checkForm: function() {
      $.post(admin_url_idx + '/ajax/kunden/checkMail', {
         user_id : $('#user_id').val(),
         email   : $('#email').val()
      }, function(data) {
         if (data.status === 'ok') {
            $('#form_kunden').submit();
         }

         else {
            alertbox('E-Mail wird bereits verwendet.');
         }
      }, 'json' );
   },

   // Email bei Änderung testen (gültig oder vorhanden)
   // 02.03.2019
   checkEmail: function() {
      $.post(admin_url_idx + '/ajax/kunden/checkMail', {
         user_id : $('#user_id').val(),
         email   : $('#email').val()
      }, function(data) {
         if (data.status !== 'ok') {
            alertbox('Email kann nicht verwendet werden.');
         }
      }, 'json' );
   },

   // Passwort senden
   // 24.02.2019
   forgotten: function(el, user_id) {
      $.post(admin_url_idx + '/ajax/kunden/forgotten', {
         user_id : user_id
      }, function(data) {
         if (data.status === 'ok') {
            var color = rgb2color($(el).css('color'));
            $(el).css('color', '#55ee55');

            $(el).animate( {'opacity' : 1}, 1000, function() {
               // Nur mit jquery-ui möglich
               $(this).animate( {'color' : color }, 500);
            });
         }
      }, 'json' );
   },

   // Ajax-Suche während Eingabe / nicht verwendet


   // Für Aufruf aus anderen Klassen, z.B. Bestellungen
   // 20.02.2019
   details: function(user_id) {
      location.href = admin_url_idx+'/kunden/detail/'+user_id;
      return;
   },

   // Nachricht an Kunde senden
   // 01.03.2019
   kundeMail: function(nachricht, user_id) {
      $.post(admin_url_idx+'/ajax/kunden/nachricht', {
         user_id   : user_id,
         nachricht : nachricht
      }, function(data) {
         if(data.status === 'ok') {

         }
      }, 'json' );
   },

   // Bestellungen des Kunden anzeigen
   // 03.03.2019
   bestellung: function(user_id) {
      location.href = admin_url_idx+'/bestellungen/usermode/'+user_id;
      return;
   },

   // Gutschrif-Mail an Kunde
   // 01.03.2019
   gutschrift: function(el) {
      $.post(admin_url_idx + '/kunden/sendGutschrift', {
            user_id    : $('#user_id').val(),
            email      : $('#email').val(),
            gutschrift : $('#gutschrift').val()
         },
         function(data) {
            if (data.status === 'ok') {
            var color = rgb2color($(el).css('color'));
            $(el).css('color', '#55ee55');

            $(el).animate( {'opacity' : 1}, 1000, function() {
               // Nur mit jquery-ui möglich
               $(this).animate( {'color' : color }, 500);
            });
            }
            else {
               alertbox('Gutschrift-E-Mail konnte nicht versendet werden');
            }
         }, 'json'
      );
   },

   // Staat2 anzeigen, wenn Außerhalb EU
   checkStaat: function(id) {
      if (id === 0) {
         if ($('#staat option:selected').val() === '10') {
            $('#no_eu').show();
         }

         else {
            $('#no_eu').hide();
         }
      }

      if (id === 1) {
         if ($('#lf_staat option:selected').val() === '10') {
            $('#lf_no_eu').show();
         }

         else {
            $('#lf_no_eu').hide();
         }
      }
   },

   // Gutschrif-Mail an Kunde
   // 01.03.2019
   alterCheck: function(el) {
      $.post(admin_url_idx + '/kunden/alterCheck', {
            user_id     : $('#user_id').val(),
            alter_check : ($('#alter_check').prop('checked') ? 'on' : 'off')
         },
         function(data) {
            if (data.status === 'ok') {
               var check = $('#alter_check').attr('data-alter_check');

               if ($('#alter_check').prop('checked') && (check === 'Admin' || check === '')) {
                  $('#perso_text').html('manuell gecheckt&nbsp');
               }

               else {
                  $('#perso_text').html('Perso gecheckt&nbsp');
               }
            }
         }, 'json'
      );
   },

   dummy: function() {}
};

// 'Autostart' bei Seitenaufruf
$(function() {
   // Kunden Liste

   // Kunden Details
   if ($('#kunden_detail').length) {
      $('#staat').change(function() { Bestellungen.checkStaat(0); });
      $('#lf_staat').change(function() { Bestellungen.checkStaat(1); });

      if ($('#staat option:selected').val() === '10') {
         $('#no_eu').show();
      }

      else {
         $('#no_eu').hide();
      }

      if ($('#lf_staat option:selected').val() === '10') {
         $('#lf_no_eu').show();
      }

      else {
         $('#lf_no_eu').hide();
      }
   }
});


// ************* Funktionen Artikel ********************************************
var Artikel = {
   neuMsg             : 'Neuer Artikel muss zuvor gespeichert werden',
   line_to_delete     : false,
   liste_to_delete    : false,
   popup_maincat_val  : 0,
   popup_maincat_name : '',

   // Liste Refresh
   // 30.12.2018
   liste: function(parent_id) {
      parent_id = parseInt(parent_id);
      var modul_id = $('#listcontent').attr('data-modul_id');

      var listmode    = $('#artikelList').attr('data-listmode');
      var haendler_id = $('#artikelList').attr('data-haendler_id');

      $.post(admin_url_idx + '/ajax/artikel/liste', {
         listmode    : listmode,
         haendler_id : haendler_id,
         parent_id   : parent_id,
         search      : $('#suche').val(),
         modul_id    : modul_id
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste neu anzeigen
            if (parent_id === 0) {
               $('#artikelList').html(data.html);
               $('#pager_oben .pager').html(data.pager);
               $('#pager_unten .pager').html(data.pager);
            }

            // Varianten hinzufügen
            else {
               var parent = $('#parentid_'+parent_id);
               // Varianten vorhanden
               if (data.html.length > 100) {
                  $(parent).append(data.html);
                  $('#open_'+parent_id).removeClass('fa-plus').addClass('fa-minus');
               }

               // Keine Varianten vorhanden
               else {
                  $('.arttikel_sub', $(parent)).remove;
                  $('#open_'+parent_id).removeClass('fa-plus').removeClassClass('fa-minus');
               }

            }
         }

         else {
            console.log('error Artikel::liste');
         }
      }, 'json');
   },

   // Suche mit Button
   // 30.12.2018
   find: function(search, all) {
      var listmode    = $('#artikelList').attr('data-listmode');
      var haendler_id = $('#artikelList').attr('data-haendler_id');
      var modul_id = $('#listcontent').attr('data-modul_id');

      $.post(admin_url_idx+'/ajax/artikel/find', {
         listmode    : listmode,
         haendler_id : haendler_id,
         search      : search,
         all         : all,
         modul_id    : modul_id
      },
      function(data) {
         if (data.status === 'ok') {
            $('#artikelList').html(data.html);
            $('#pager_oben .pager').html(data.pager);
            $('#pager_unten .pager').html(data.pager);

            $('#find_reset').show();
         }

         else {
            console.log('error Artikel::find');
         }
      }, 'json' );
   },

   // Sortierung anzeigen und Seite neu laden
   //   sort: function (id, haendler_id) {
   sort: function (sort_id, haendler_id) {
      var sort_dir = '';
      var modul_id = $('#listcontent').attr('data-modul_id');

      // Pfeile in Titelzeile setztn und Sortierrichtung herausfinden
      var el = $('#art_sort'+sort_id+'_symbol');

      if (el.hasClass('fa-sort-up')) {
         sort_dir = 'desc';
         el.attr('class', 'list_icon sort-desc fas fa-sort-down');
      }

      else {
         sort_dir = 'asc';
         el.attr('class', 'list_icon sort-asc fas fa-sort-up');
      }

      for (var i = 1; i <= 6; i++) {
         if ( i !== sort_id) {
            $('#art_sort'+i+'_symbol').attr('class', 'list_icon sort-no fas fa-sort');
         }
      }

      $.post(admin_url_idx + '/ajax/artikel/sort', {
         sort_dir    : sort_dir,
         sort_id     : sort_id,
         haendler_id : haendler_id,
         modul_id    : modul_id
      },
      function(data) {
         if (data.status === 'ok') {
            Artikel.liste(0);
         }

         else {
            console.log('error Bestellungen::sort');
         }
      }, 'json');

   },

   // Artikel pro Seite setzen
   count: function(count) {
      var listmode    = $('#artikelList').attr('data-listmode');
      var haendler_id = $('#artikelList').attr('data-haendler_id');
      var modul_id = $('#listcontent').attr('data-modul_id');

      $('#suche').val('');
      $('#find_reset').hide();

      $.post(admin_url_idx + '/ajax/artikel/count', {
         count: count,
         listmode : listmode,
         haendler_id : haendler_id,
         modul_id    : modul_id
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Artikel.liste(0);
         }

         else {
            console.log('error Artikel::count');
         }
      }, 'json' );
   },

   // Seite neu anzeigen (Inhalt / Ajax)
   seite: function(seite) {
      var modul_id = $('#listcontent').attr('data-modul_id');
      $('#find_reset').hide();

      $.post(admin_url_idx + '/ajax/artikel/seite', {
         seite    : seite,
         modul_id : modul_id
      },
      function(data) {
         if (data.status === 'ok') {
            // Liste und Pager neu laden
            Artikel.liste(0);
         }

         else {
            console.log('error Artikel::seite');
         }
      }, 'json' );
   },

   // Listenansicht Artikel löschen mit Nachfrage, Seite neu laden, bei Hauptartikel (parent_id > 0)
   // 09.07.2019
   listeDelete: function(el, article_id, parent_id) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         // Aufrufendes Objekt speichern
         Artikel.liste_to_delete = el;
         Confirmbox.head = (parseInt(parent_id) !== 0 && $(el).closest('.list_line').attr('data-childs') === 'y' ? 'Artikel mit Unterartikeln wirklich löschen?' : 'Artikel wirklich löschen?');
         Confirmbox.yes_function = 'Artikel.listeDelete("el", '+article_id+', '+parent_id+')';
         Confirmbox.show();
         return;
      }

      // aufrufendes Objekt wieder herstellen
      el = Artikel.liste_to_delete;
      Artikel.liste_to_delete = false;
      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/artikel/listeDelete', {
//         parent_id  : parent_id,
         article_id : article_id
      },
      function(data) {
         if (data.status === 'ok') {
            if (parseInt(parent_id) !== 0) {
               // Liste und Pager neu laden
               Artikel.liste(0);
            }

            else {
               $(el).closest('.block_start').remove();
            }
         }

         else {
            console.log('error Artikel::count');
         }
      }, 'json' );
   },

   // Varianten anzeigen / laden oder verstecken
   // 30.12.2018
   varianten: function(el, parent_id) {
      var parent = $(el).closest('.block_start');
      var online = $('.list_online', parent).hasClass('fa-check');

      // Varianten geöffnet - > schließen
      if ($('#open_'+parent_id).hasClass('fa-minus')) {
         $(el).removeClass('fa-minus').addClass('fa-plus');
         $('.artikel_sub', $(el).closest('.list_line')).hide();

         if (online) {
            $('.list_online', parent).attr('title', 'alle Varianten deaktivieren');
         }

         else {
            $('.list_online', parent).attr('title', 'alle Varianten aktivieren');
         }

         return;
      }

      // Varianten bereits geladen
      if ($(el).hasClass('fa-plus') && $('.artikel_sub', $(el).closest('.list_line')).length) {
         $(el).removeClass('fa-plus').addClass('fa-minus');
         $('.artikel_sub', $(el).closest('.list_line')).show();

         if (online) {
            $('.list_online', parent).attr('title', 'alle Varianten deaktivieren');
         }

         else {
            $('.list_online', parent).attr('title', 'alle Varianten aktivieren');
         }

         return;
      }

      // Varianten laden -> öffnen
      Artikel.liste(parent_id);

      if (online) {
         $('.list_online', parent).attr('title', 'deaktivieren');
      }

      else {
         $('.list_online', parent).attr('title', 'aktivieren');
      }
   },

   // Online-Status ändern
   // 30.12.2018
   online: function(el, parent_id, article_id) {
      // Status, der gesetzt werden soll
      var online = ($(el).hasClass('fa-check') ? 'off' : 'on');
      var childs = parseInt($(el).attr('data-childs'));
      var sub = 'single';
      var parent = $(el).closest('.block_start');

      if (parent.hasClass('artikel_main') && $('.list_open', parent).hasClass('fa-plus')) {
         sub = 'all';
      }

      parent_id  = parseInt(parent_id);
      article_id = parseInt(article_id);

      $.post(admin_url_idx + '/ajax/artikel/online', {
         parent_id  : parent_id,
         article_id : article_id,
         online    : online,
         sub       : sub
      },
      function(data) {
         if (data.status === 'ok') {
            // Artikel ist online gesetzt
            if (online === 'on') {
               var title = 'deaktivieren';

               if (parent_id > 0 && sub === 'all') {
                  $('.sub', $('#parentid_'+parent_id)).each( function() {
                     $('.list_online', $(this)).removeClass('fa-times').addClass('fa-check').attr('title', 'deaktivieren');
                  });

                  title = 'alle Varianten deaktivieren';
               }

               $(el).removeClass('fa-times').addClass('fa-check').attr('title', title);
            }

            else {
               var title = 'aktivieren';

               if (parent_id > 0 && sub === 'all') {
                  $('.sub', $('#parentid_'+parent_id)).each( function() {
                     $('.list_online', $(this)).removeClass('fa-check').addClass('fa-times').attr('title', 'aktivieren');
                  });

                  title = 'alle Varianten aktivieren';
               }

               $(el).removeClass('fa-check').addClass('fa-times').attr('title', title);
            }

            showFeedback($(el).closest('.block_start'));
         }
         else {
            console.log('error Artikel::liste');
         }
      }, 'json' );
   },

   // Artikel speichern (geäderte) -> article
   saveList: function() {
      //Multibox.showLoading();
      var count = 0;

      $('.block_start', $('#artikelList')).each( function() {
         if ($(this).attr('data-changed') !== '0') {
            $(this).attr('data-changed', 0);
            count++;

            var article_id = $(this).attr('data-article_id');
            var netto      = $('.netto', $(this)).val();
            var angebot    = $('.angebot', $(this)).val();
            var check      = ($('.check', $(this)).prop('checked') ? 'on' : 'off');
            var menge      = $('.menge', $(this)).val();
            var parent     = $(this).attr('data-parent');
            var sortierung = (parent === '1' ? $('.sortirung', $(this)).val() : 0);

            $.post(admin_url_idx+'/artikel/saveList', {
               article_id : article_id,
               netto      : netto,
               angebot    : angebot,
               check      : check,
               menge      : menge,
               parent     : parent,
               sortierung : sortierung
            }, function(data) {

               if (data.status === 'ok') {

               }
            }, 'json' );
         }
      });

      if (count === 0) {
         alertbox('Keine Artikel zum Speichern', '', 3);
      }

      else {
         //alertbox(count+' Artikel gespeichert', '', 3);
         alertbox('Änderungen wurden gespeichert', '', 3);
      }
   },

   // Brutto / Netto berechnen - Liste und Details
   // 31.12.2018
   compute: function(el, mode) {
      var bild_beschreibung = 0; // 0 -> Variante; 1 -> Hauptartikel -> Bild_Beschreibung aktualisieren; 2 -> Bild_Beschreibung geändert -> Hauptartikel aktualisieren
      var is_main = false;

      // In Bilder & Beschreibung geändert
      if (el === 'bild_beschreibung' || el === 'bild_beschreibung_netto' || el === 'bild_beschreibung_brutto') {
         bild_beschreibung = 2;

         if (el === 'bild_beschreibung_brutto') {

            bild_beschreibung = 3;
         }

         el = $('.article_main .art_netto');
      }


      var parent = $(el).closest('.block_start');
      parent.attr('data-changed', 1);

      // Hauptartikel geändert
      if (bild_beschreibung === 0 && parent.hasClass('article_main')) {
         bild_beschreibung = 1;
         is_main = true;
      }

      var check        = (bild_beschreibung < 2 ? $('.check', parent).prop('checked') : $('#angebot_aktiv2').prop('checked'));
      var steuer       = 0;

      if ($('#steuer').length) {
         steuer = parseFloat(komma2point($('#steuer').val()));
      }

      else {
         steuer = parseFloat(komma2point($('.steuer', parent).val()));
      }

      var netto_show   = parseFloat(komma2point($('.netto_show', parent).val()));
      var netto        = parseFloat(komma2point($('.netto', parent).val()));
      var angebot_show = parseFloat(komma2point($('.angebot_show', parent).val()));
      var angebot      = parseFloat(komma2point($('.angebot', parent).val()));
      var brutto_show  = parseFloat(komma2point($('.brutto_show', parent).val()));

      // Bei Änderung in Bilder & Beschreibung geändert, Werte von dort verwenden
      if (bild_beschreibung >= 2) {
         netto_show   = parseFloat(komma2point($('#netto2').val()));
         netto        = netto_show;
         angebot_show = (bild_beschreibung === 2 ? parseFloat(komma2point($('#angebot_netto2').val())) : parseFloat(komma2point($('#angebot_brutto2').val()) / (1 + steuer / 100)));
         angebot      = angebot_show;
         brutto_show  = parseFloat(komma2point($('#brutto2').val()));
      }

      // Bei Änderung Checkbox Preis brutto ändern
      if (mode === 'check') {
         // Checkboxen synchroniseren - in Bilder/Beschreibung geändert
         if (bild_beschreibung >= 2) {
            $('.check', parent).prop('checked', check);
         }

         // In Hauptartikel geändert
         else if (bild_beschreibung === 1) {
            $('#angebot_aktiv2').prop('checked', check);
         }

         // Brutto aus Angebor
         if (check) {
            var preis_netto  = angebot;
            var preis_brutto = angebot * (1 + steuer / 100);
            // $('.brutto', parent).val(preis_brutto);
            $('.brutto_show', parent).val(point2komma(preis_brutto.toFixed(2)));

            if (bild_beschreibung >= 1) {
               $('#angebot_brutto').val(point2komma(preis_brutto.toFixed(2)));
            }

            $('#netto2').addClass('durchgestrichen');
            $('#brutto2').addClass('durchgestrichen');
            $('#angebot_netto2').removeClass('durchgestrichen');
            $('#angebot_brutto2').removeClass('durchgestrichen');
         }

         // Brutto aus Netto
         else {
            var preis_netto  = netto;
            var preis_brutto = netto * (1 + steuer / 100);
            // $('.brutto', parent).val(preis_brutto);
            $('.brutto_show', parent).val(point2komma(preis_brutto.toFixed(2)));

            if (bild_beschreibung >= 1) {
               $('#angebot_brutto').val(point2komma(preis_brutto.toFixed(2)));
            }

            $('#netto2').removeClass('durchgestrichen');
            $('#brutto2').removeClass('durchgestrichen');
            $('#angebot_netto2').addClass('durchgestrichen');
            $('#angebot_brutto2').addClass('durchgestrichen');
         }
      }

      // Eingabe netto
      if (mode === 'netto') {
//         var preis = parseFloat(komma2point($('.netto_show', parent).val()));
         var preis_netto = netto_show;
         var preis_brutto = preis_netto * (1 + steuer / 100);

         $('.netto', parent).val(preis_netto);
         $('.netto_show', parent).val(preis_netto.toFixed(2));
         $('.brutto', parent).val(preis_brutto);
         $('.brutto_show', parent).val(point2komma(preis_brutto.toFixed(2)));

         if (bild_beschreibung === 2) {
            $('#netto2').val(point2komma(preis_netto.toFixed(2)));
            $('#netto2_hiddene').val(preis_netto);
            $('#brutto2').val(point2komma(preis_brutto .toFixed(2)));
         }
      }

      // Eingabe brutto
      if (mode === 'brutto') {
         var preis_brutto = brutto_show;
         var preis_netto  = preis_brutto / (1 + steuer / 100);

         $('.brutto_show', parent).val(point2komma(preis_brutto.toFixed(2)));

         // Bei Hauptartikel
         if (bild_beschreibung >= 1) {
            $('#netto2').val(point2komma(preis_netto.toFixed(2)));
            $('#netto2_hidden').val(preis_netto);
            $('#brutto2').val(point2komma(preis_brutto.toFixed(2)));
         }

         // Angebot
         if (check) {
            $('.angebot', parent).val(preis_netto);
            $('.angebot_show', parent).val(point2komma(preis_netto.toFixed(2)));

            if (bild_beschreibung >= 1) {
               $('#netto2').val(point2komma(preis_netto.toFixed(2)));
               $('#netto2_hidden').val(preis_netto);
               $('#brutto2').val(point2komma(preis_brutto.toFixed(2)));
            }
         }

         // Normalpreis
         else {
            $('.netto', parent).val(preis_netto);
            $('.netto_show', parent).val(point2komma(preis_netto.toFixed(2)));

            if (bild_beschreibung >= 1) {
               $('#netto2').val(point2komma(preis_netto.toFixed(2)));
               $('#netto2_hidden').val(preis_netto);
               $('#brutto2').val(point2komma(preis_brutto.toFixed(2)));
            }
         }
      }

      // Eingabe Angebot - Brutto nur anzeigen, wenn checkbox aktiv
      if (mode === 'angebot') {
         var preis_netto  = angebot_show;
         var preis_brutto = preis_netto * (1 + steuer / 100);

         $('.angebot', parent).val(preis_netto);
         $('.angebot_show', parent).val(point2komma(preis_netto.toFixed(2)));

         if (bild_beschreibung > 0) {
            $('#angebot_netto2').val(point2komma(preis_netto.toFixed(2)));
            $('#angebot_brutto2').val(point2komma(preis_brutto.toFixed(2)));
         }

         if (check) {
            $('.brutto_show', parent).val(point2komma(preis_brutto.toFixed(2)));
         }
      }

      // Eingabe Haaendler_netto
      if (mode === 'haendler_netto') {
         var preis_netto  = parseFloat(komma2point($('.art_haendler_netto', parent).val()));
         var preis_brutto = preis_netto * (1 + steuer / 100);

         $('.art_haendler_netto', parent).val(preis_netto.toFixed(2));
         $('.art_haendler_netto_real', parent).val(preis_netto);
         $('.art_haendler_brutto', parent).val(point2komma(preis_brutto.toFixed(2)));
      }

      // Eingabe haendler_brutto
      if (mode === 'haendler_brutto') {
         var preis_brutto = parseFloat($('.art_haendler_brutto', parent).val());
         var preis_netto  = preis_brutto / (1 + steuer / 100);

         $('.art_haendler_netto', parent).val(preis_netto.toFixed(2));
         $('.art_haendler_netto_real', parent).val(preis_netto);
         $('.art_haendler_brutto', parent).val(point2komma(preis_brutto.toFixed(2)));
      }

      if ($('#grundeinheit').length) {
         Artikel.checkGewicht();
      }
   },

   // GE-Menge / geändert, Brutto dazu berechnen / als Titel bei Grundpreis anzeigen
   checkGewicht: function() {
      var ge      = $('#grundeinheit').val();
      var ge_show = ge;
      var parent  = $('#parent_id').val();
      var brutto  = 0;
      var netto   = 0;
      var steuer  = 1;

      if ($('#show_netto').val() === 'n') {
         steuer = (1 + parseFloat($('#steuer').val()) / 100);
      }
      //
      // Alle Varianten durchgehen
      $('.ean_line').each(function() {
         var menge  = parseFloat(komma2point($('.ge_menge_show', $(this)).val()));
         var netto  = parseFloat(komma2point($('.art_netto', $(this).closest('.block_start')).val()));

         // Normal-Preis oder Angebot
         if ($('.art_check_angebot', $(this).closest('.block_start')).prop('checked')) {
            netto = parseFloat(komma2point($('.art_angebot', $(this).closest('.block_start')).val()));
         }

         // Falls netto nicht gesetzt, netto = 0
         if (isNaN(netto)) {
            netto = 0;
         }

         var brutto    = netto * steuer;
         // In g / Kg, l / ml, usw.
         var ge_netto  = netto / menge;
         var ge_brutto = brutto / menge;

         // Korrektur 10g
         if (ge === '10g' || ge === '10ml' || ge === 'cm') {
            ge_brutto = ge_brutto * 10;
            ge_netto  = ge_netto * 10;
         }

         // Korrektur 100d
         if (ge === '100g' || ge === '100ml' || ge === 'dm') {
            ge_brutto = ge_brutto * 100;
            ge_netto  = ge_netto * 100;
         }

         // Korrektur Fläche (in cm x cm)
         if (ge === 'cm2') {
            ge_brutto = ge_brutto * 100;
            ge_netto  = ge_netto * 100;
         }

         // Korrektur Fläche (in dm x dm)
         if (ge === 'dm2') {
            ge_brutto = ge_brutto * 10000;
            ge_netto  = ge_netto * 10000;
         }

         if (ge === 'cm3') {
            ge_brutto = ge_brutto * 1000;
            ge_netto  = ge_netto * 1000;
         }

         if (ge === 'dm3') {
            ge_brutto = ge_brutto * 1000000;
            ge_netto  = ge_netto * 1000000;
         }

         // Title Grundpreis
         ge_brutto_text = point2komma(ge_brutto.toFixed(2));
         $('.ge_edit_txt', $(this)).attr('title', (ge_brutto > 0 ? 'entspricht: '+ge_brutto_text+' / '+ge_show : ''));

         // Werte zum Speichern - nicht runden!!!
         $('.art_ge_menge', $(this)).val(1 / menge);
         $('.art_ge_netto', $(this)).val(ge_netto);

         // Input Menge
         $('.ge_menge_show', $(this)).val((menge === 0 ? '' : point2komma(menge.toFixed(3))));
      });
   },

   // Tabelle Artikel neu sortieren mit Nachfrage
   // 01.03.2019
   reorg: function() {
      var answer = confirm("Zuvor bitte Datenbank-Sicherung durchführen.");
      if (!answer) {
         return;
      }

      var color = $('#reorg').css('background-color');
      $('#reorg').css('background-color', '#ffdd00');

      $.post(admin_url_idx + '/ajax/artikel/reorg', {},
         function(data) {
            if (data.status === "ok") {
               $('#reorg').animate( { 'background-color' : 'rgb(100, 240, 100)' }, 1000, function() {
                  $(this).animate({'background-color' : color }, 250);
               });

               $('#articleReorg').hide();
            }
         }, 'json'
      );
   },

   
   // Popup PDF-Katalog einstellungen
   // 15.07.2019
   pdfkatalogPopup: function() {
      $.post(admin_url_idx+'/ajax/artikel/pdfkatalogPopup',
         $('#pdfkatalog_form').serializeArray(),
         function(data) {
            if (data.status === 'ok') {
               Multibox.content(data.html);
               Multibox.width('auto');
               Multibox.close_btn = true;
               Multibox.bg_close = true;
               Multibox.show();
            }

            else {
               console.log('error Module::pdfKatalogSave');
            }
         }, 'json'
      );
   },

   // PDF-Popup Einstellungen speichern
   // 15.07.2019
   pdfkatalogSave: function() {
      $.post(admin_url_idx+'/ajax/artikel/pdfkatalogSave',
         $('#pdfkatalog_form').serializeArray(),
         function(data) {
            if (data.status === 'ok') {
               Multibox.close();
            }

            else {
               console.log('error Module::pdfKatalogSave');
            }
         }, 'json'
      );
   },

   // Titelbild PDF-Katalog hochladen
   //
   pdfkatalogUpload: function() {
      var target_url = admin_url_idx+'/ajax/artikel/pdfkatalogUpload';
      var file_types = ['png', 'jpg'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\');" />');
      $('#file_upload').click();
   },


   // Status motivUpload in Tab anzeigen
   // 24.06.2019
   motivExtra: function() {
     var text = $('#motiv_uploadt_check').prop('checked');
     var img  = $('#motiv_uploadp_check').prop('checked');

     if (text || img) {
        $('#tabs_extra_motiv').removeClass('active').addClass('active');
     }

     else {
        $('#tabs_extra_motiv').removeClass('active');
     }
   },

   // Alle Varianten inkl. Main: Änderung in data_changed vermerken
   // 24.06.2019
   articleChange: function(el) {
      $(el).closest('.line_variante').prop('data_changed', 1);
   },

   varianteNew: function() {
      $.post(admin_url_idx+'/ajax/artikel/varianteNew', {
         parent_id : $('#parent_id').val()
      },
      function(data) {
         if (data.status === 'ok') {
            $('#article_block').append(data.html);
         }
      }, 'json'
      );
   },

   // Parent speichern -> article_info
   // 25.07.2019
   saveArticle: function() {
      parent_id = parseInt($('#parent_id').val());

      // Editoren in textaria speichern
      Artikel._triggerEditors();

      // Kategorien. auf mehrere Selects verteilt
      var cat  = $('#category').val();
      var cats = [];

      // Aus Kategorien-Popup
      $('.cat_input', $('#catlist')).each(function(idx) {
         cats[idx] = $(this).val();
      });

      var params = {
         parent_id            : parent_id,
         show_object          : ($('#show_object').prop('checked') ? 'on' : 'off'),
         category             : cat,
         categories           : cats,
         fsk_check            : ($('#fsk_check').prop('checked') ? 'on' : 'off'),
         grundeinheit         : $('#grundeinheit').val(),

         // Tab1_1 / zusäzlich zu Varianten
         name                 : $('#artikelname').val(),
         merkmal1             : $('#merkmal1').val(),
         merkmal2             : $('#merkmal2').val(),
         varianten            : [],


         // Tab1_2 - Staffelpreise
         staffelung           : $('#staffelung_val').val(),

         // Tab1_3 - Rabatt
         artikelgruppe        : $('#rabattgruppe').val(),

         // Tab1_4 - Versand
         versandpreis         : $('#versand_preis').val(),
         lieferfrist          : $('#lieferfrist').val(),
         vpe                  : $('#vpe').val(),
         vpm                  : $('#vpm').val(),
         gewicht              : $('#gewicht').val(),
//         spedition            : $('input.spedition_inp[name="spedition"]:checked').val(),
         spedition            : $('#spedition').val(),

         // Tab2_1 - Megakonfigurator
         configurator_check   : ($('#configurator_check').prop('checked') ? 'on' : 'off'),
         configurator_artnr_check : ($('#configurator_artnr_check').prop('checked') ? 'on' : 'off'),
         config_einheit_check : ($('#config_einheit_check').prop('checked') ? 'on' : 'off'),
         configurator_val     : $('#configurator_val').val(),
         config_menge_check   : $('input[name=config_menge_check]:checked').val(),

         // Tab2_2 - Maßeingabe
         masse_check          : ($('#masse_check').prop('checked') ? 'on' : 'off'),
         masse_min            : $('#masse_min').val(),
         masse_komma          : $('#masse_komma').val(),
         rechner_check        : ($('#rechner_check').prop('checked') ? 'on' : 'off'),
         rechner_mode         : $('input[name=rechner_mode]:checked').val(),  // Radio
         grundeinheit_rechner : $('#grundeinheit_rechner').val(),

         // Tab2_2 - Mixer
         mixer_artikel_check  : ($('#mixer_artikel_check').prop('checked') ? 'on' : 'off'),

         // Tab2_3 - Naehrwerte
         naehrwerte_check     : ($('#naehrwerte_check').prop('checked') ? 'on' : 'off'),

         // Tab2_4 Maßeingabe

         // Tab3_5 Motiv-Upload
         motiv_uploadp_check  : ($('#motiv_uploadp_check').prop('checked') ? 'on' : 'off'),
         motiv_uploadt_check  : ($('#motiv_uploadt_check').prop('checked') ? 'on' : 'off'),

         // Tab3_1 Bilder & Beschreibung
         neu_check            : ($('#neu_check').is(':checked') ? 'on' : ''),
         ab_check             : ($('#ab_check').is(':checked') ? 'on' : ''),
         steuersatz           : $('#steuersatz').val(),
         marke                : $('#marke').val(),
         marke_aktiv          : ($('#marke_aktiv').prop('checked') ? 'on' : 'off'),
         spalten2_check       : ($('#spalten2_check2').prop('checked') ? 'on' : 'off'),
         desc                 : $('#editor_s').val(),
         desc_l               : $('#editor_l').val(),
         desc_r               : $('#editor_r').val(),
         widerruf             : $('#widerruf').val(),

         // Tab3_2 Artikeltimer
         // Save-Button

         // Tab3_3 - SEO
         seo_auto             : ($('#seo_auto').prop('checked') ? 'on' : 'off'),
         metatitle            : $('#seo_title').val(),
         metadesc             : $('#seo_desc').val(),
         metakey              : $('#seo_key').val(),

         versandfrei_check       : ($('#versandfrei_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik1_check    : ($('#artikelgrafik1_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik2_check    : ($('#artikelgrafik2_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik3_check    : ($('#artikelgrafik3_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik4_check    : ($('#artikelgrafik4_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik5_check    : ($('#artikelgrafik5_check').prop('checked') ? 'on' : 'off'),
         artikelgrafik6_check    : ($('#artikelgrafik6_check').prop('checked') ? 'on' : 'off'),

         // Tab4_1 - Text

         // Tab5_1 - Musikplayer
         // Tab5_2 - Zubehör
         // Tab5_3 - Ähnliche Artikel
         // Tab5_4 - Crosspromotion
         // Tab5_5 - Google-Shoppinng
         // Tab5_6 - Ebay
         g_cats               : $('#g_cats').val(),
         g_zustand            : $('#g_zustand').val(),
         haendler_id          : $('#haendler_id').val(),
         ean_check            : ($('#ean_check').is(':checked') ? 'on' : ''),
         gew_check: ($('#gew_check').is(':checked') ? 'on' : ''),
         energy_efficiency: $('#energy_efficiency').val(), 


      };

      // String in Array
      var is_foto = $('#is_foto').val();

      // Normaler Artikel
      if (is_foto !== 'y') {
         params.is_foto    = is_foto;
         params.foto_set   = $('#foto_set').val();
         params.foto_mode  = $('#foto_mode').val();

         var i = 0;

         $('.block_start').each( function() {
            var variante = {
               article_id     : $(this).attr('data-article_id'),
               online         : ($('.art_online', $(this)).prop('checked') ? 'on' : 'off'),
               artnr          : $('.art_artnr', $(this)).val(),
               startbild      : $('.art_startbild', $(this)).val(),
               wert1          : $('.art_wert1', $(this)).val(),
               wert2          : $('.art_wert2', $(this)).val(),
               netto          : $('.art_netto', $(this)).val(),
               haendler_netto : $('.art_haendler_netto_real', $(this)).val(),
               angebot_active : ($('.art_check_angebot', $(this)).prop('checked') ? 'y' : 'n'),
               angebot        : $('.art_angebot', $(this)).val(),
               menge          : $('.art_menge', $(this)).val(),
               gtin           : $('.art_gtin', $(this)).val(),
               mpn            : $('.art_mpn', $(this)).val(),
               ge_netto       : $('.art_ge_netto', $(this)).val(),
               ge_menge       : $('.art_ge_menge', $(this)).val()
            };

            params.varianten[i] = JSON.stringify(variante);
            i++;
         });
      }

      // Fotomodul
      else {
         params.is_foto    = is_foto;
         params.foto_set   = $('#foto_set').val();
         params.org_set   = $('#org_set').val();
//         params.art_nr     = $('#artnr').val();
         params.preis_mode = $('#preis_mode').val();
         params.old_mode   = $('#old_mode').val();
         params.art_nr     = $('#art_nr').val();
         params.netto_foto = [];

         for (i = 0; i < 7; i++) {
            params.netto_foto[i] = $('#netto_real_'+i).val();
         }

         params.menge = $('#foto_menge').val();

      }


      $.post(admin_url_idx+'/ajax/artikel/articleSave',
         params,
         function(data) {
            if (data.status === 'ok') {
               if (parseInt(data.new_id) > 0) {
                  location.href = admin_url_idx+'/artikel/detail/'+data.new_id;
               }

               else {
                  location.reload();
               }
            }
         }, 'json'
      );
   },

   // Parent erstellen, um ID zu erhalten
   // 25.07.2019
   getParentId() {
      $.ajax({
         url      : admin_url_idx+'/ajax/artikel/getParentId',
         dataType : 'json',
         async    : false,
         success  : function(data) {
                       if(data.status === 'ok') {
                          $('#parent_id').val(data.new_id);
                          history.pushState('', '', admin_url_idx+'/artikel/detail/'+data.new_id);

                          return parseInt(data.new_id);
                    }
         }
      });
   },

   // Detail-Seite Variante löschen
   // 21.06.2019
   deleteVariante: function(el) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Artikel.line_to_delete = el;
         Confirmbox.head = 'Variante Löschen?';
         Confirmbox.html = '';
         Confirmbox.yes_function = 'Artikel.deleteVariante()';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      var article_id = parseInt($(Artikel.line_to_delete).attr('data-article_id'));

      if (article_id === 0) {
         $(el).remove();
         Artikel.line_to_delete = false;
         return;
      }

      $.post(admin_url_idx + '/ajax/artikel/deleteVariante', {
         article_id : article_id
      },
      function(data) {
         if (data.status === 'ok') {
            $(Artikel.line_to_delete).remove();
            Artikel.line_to_delete = false;
         }

         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Artikel duplizieren und Anzeigen
   // 08.07.2019
   articleCopy: function() {
      var parent_id = parseInt($('#parent_id').val());

      if (parent_id === 0) {
         alertbox(Artikel.neuMsg);
         return;
      }

      alertbox('Bitte warten, bis Seite aktualisiert wurde');

      $.post(admin_url_idx+'/ajax/artikel/articleCopy', {
         parent_id : parent_id
      },
      function(data) {
         if (data.status === 'ok') {
            if (data.new_id) {
               location.href = admin_url_idx+'/artikel/detail/'+data.new_id;
            }

            else {
               alertbox("Artikel wurde dupliziert.\nDas Duplikat wurde unter einer neune ID angelegt.");
            }
         }

         else {
            msg = "Artikel konnte nicht dupliziert werden";

            if (data.msg) {
               msg = data.msg;
            }
            alertbox(msg);
         }
      }, 'json' );
   },

   // Kategorien-Popup anzeigen / Klick auf '+'
   // 02.06.2020
   catShow: function() {
      var content = $('#catlist_popup_placeholder').html();
      Artikel.popup_maincat_val = $('#category').val();
      Multibox.bg_close    = true;
      Multibox.close_btn = true;
      Multibox.content(content);
      Multibox.show();
   },

   // Kategorie-Select im Popup hinzufügen
   // 17.06.2019
   catAdd: function() {
      var copy = $('#multibox .catcopy').clone().html();
      $('.clear', $('#multibox .catlist')).remove();
      $('#multibox .catlist').append(copy+'<div class="clear"></div>');
   },

   // Unterkategorie von el/<select ... /> anzeigen
   // 02.06.2020
   catChanged: function(el) {
      var cat_box = $(el).closest('.cat_box_wrapper');
      var cat_id  = parseInt($(el).val());
      var childs  = $('option:selected', el).data('childs');
      var maincat = ($(cat_box).hasClass('maincat') ? true : false);
      var box_idx = -1;

      // <input für speichern setzen
      $('.cat_input', $(cat_box)).val(cat_id);

      // Position <select> herausfinden, anhand gewähltem Wert und <option /> auf selected
      $('select', $(cat_box)).each( function(idx) {
         if (box_idx === -1) {
            if (parseInt($(this).val()) === cat_id) {
               $('option', $(this)).attr('selected', false);
               $('option[value='+cat_id+']', $(this)).attr('selected', true);
               box_idx = idx;

               if (maincat) {
                  // wird nicht in DOM übenommen: $('#category').val(cat_id);
                  Artikel.popup_maincat_val  = cat_id;
                  Artikel.popup_maincat_name = $('option[value='+cat_id+']', $(this)).text();
               }
            }
         }
      });

      // Nachfolgende Selects löschen
      $('select', $(cat_box)).each( function(idx) {
         // Komplette Kategorieblock löschen, außer Hauptkategorie
         if (cat_id === 0 && !maincat && box_idx === 0) {
            $(cat_box).remove();
         }

         if (idx > box_idx) {
            $(this).parent().remove();

            // letzen Eintrag zum Speichern merken
            $('select', $(cat_box)).each( function(idx) {
               $('.cat_input', $(cat_box)).val($(this).val());
            });
         }
      });

      if (cat_id === 0) {
         $('select', $(cat_box)).each( function(idx) {
            var id = parseInt($(this).val());

            if (id > 0) {
               $('.cat_input', $(cat_box)).val(id);
            }
         });
      }

      // Keine weiteren Unterkategorien
      if (childs === 0 || cat_id === 0) {
         return;
      }

      // Selectbox aus Kategorien neu laden
      $.post(admin_url_idx + '/ajax/kategorien/loadCatbox', {
         cat_id: cat_id
      },
      function(data) {
         if (data.status === 'ok') {
            $(cat_box).append(data.data);
         }
      }, 'json');
   },
   // Kategorien-Popup in Template-HTML Übernehmen
   // 17.06.2019merkmaleSave

   // Kategorie-Popup übernehmen
   // 02.06.2020
   catStore: function() {
      $('#catlist_popup_placeholder .catlist_popup').remove();
      $('#catlist_popup_placeholder').html($('#multibox .catlist_popup'));
      Multibox.close();
      $('#category').val(Artikel.popup_maincat_val);

      if (Artikel.popup_maincat_name !== '') {
         $('#maincat').html(Artikel.popup_maincat_name);
      }
   },

   // Steuersatz geändert, Artikel speichern (und neu laden)
   // 21.06.2019
   changeSteuer: function(satz) {
      $('#steuersatz').val(satz);
      Artikel.saveArticle();
   },

   // Allgemeine Funktion für Uploads
   //
   imageUpload: function(typ, bild_nr, upload_target) {
      $('#file_upload').remove();

      var parent_id     = parseInt($('#parent_id').val());
      var target_url    = admin_url_idx+'/ajax/artikel/imageUpload';
      var file_types    = ['jpg'];

      if (typ === 'artikelgrafik') {
         file_types = ['png'];
      }

      if (parent_id === 0 && typ !== 'werte_image') {
         Artikel.getParentId();
         parent_id = parseInt($('#parent_id').val());
      }

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+parent_id+'\', \''+bild_nr+'\', false, false, false, \
         true, \''+upload_target+'\');" />');
      $(function() {
         $('#file_upload').click();
      });
   },

   moreImages() {
      $("#more_images").fileinput("destroy");
      $('#fileinput').html('');

      $.post(admin_url_idx+'/ajax/artikel/moreImages', {
         parent_id : $('#parent_id').val()
      },
      function(data) {
         if (data.status === 'ok') {
            $('#fileinput').html(data.html);
         }
      },
      'json');
   },


    videoDelete: function (productid, videoname) {

        if (confirm("Video wirklich löschen?")) {

            $.post(

                admin_url_idx + '/ajax/artikel/videoDelete',
                {
                    productid: productid,
                    videoname: videoname
                }, function (data) {

                    if (data.status === 'ok') {


                        jQuery(".video[data-videoname='" + videoname + "']").hide();

                    }
            }, "JSON");

        }
    },
   // Allgemein Funktion Dateien löschen
   //
   imageDelete: function(type, bild_nr, el, el2) {
      var parent_id = $('#parent_id').val();

      $.post(
         admin_url_idx+'/ajax/artikel/imageDelete',
         {
            parent_id : parent_id,
            type      : type,
            bild_nr   : bild_nr
         },
         function(data) {
            if (data.status === 'ok') {
               if (type === 'startbild') {
                  $('#startbild_img').attr('src', admin_url+'/img/nopic.png');
               }

               if (type === 'artikelgrafik') {
                  var parent = $('#'+el).closest('.artikelgrafik_block');
                  $('.delete_ag', $(parent)).addClass('ag_not_active');
                  $('#'+el).attr('src', admin_url+'/img/nopic.png');
                  $('#'+el2).removeClass('is_ag1').removeClass('is_ag2').removeClass('is_ag3').removeClass('is_ag4').removeClass('is_ag5').removeClass('is_ag6');
                  $('#img_artikelgrafik'+bild_nr).attr('src', admin_url+'/img/nopic.png');

               }

                if (type === 'energyefficiency_image') {

                    $('img.energyefficiency_preview').attr('src', admin_url + '/img/nopic.png');
                    $("#energyefficiency_preview_delete").addClass('ag_not_active');

                }

            }

         },
         'json'
      );

   },

// Editor umschalten 1-/ 2-spaltig
   // 05.06.2019
   changeEditor: function(mode) {
      if (mode === 'single') {
         var widerruf = $('#widerruf').val();

         $('#edit_single').show();
         $('#edit_multi').hide();

         $('#spalten_wider1').html($('#spalten_wider2').html());
         $('#spalten_wider2').html('');
         $('#spalten_wider1').show();
         $('#spalten_wider2').hide();
         $('#spalten2_check1').prop('checked', true);
         $('#spalten2_check2').prop('checked', false);
         $('#widerruf').val(widerruf);
      }

      else if (mode === 'multi') {
         var widerruf = $('#widerruf').val();

         $('#edit_single').hide();
         $('#edit_multi').show();

         $('#spalten_wider2').html($('#spalten_wider1').html());
         $('#spalten_wider1').html('');
         $('#spalten_wider1').hide();
         $('#spalten_wider2').show();
         $('#spalten2_check1').prop('checked', false);
         $('#spalten2_check2').prop('checked', true);
         $('#widerruf').val(widerruf);
      }
   },

   // Inhalt Editor speichern (in textareas übertragen)
   _triggerEditors: function() {
      if (typeof tinymce !== 'undefined' && typeof tinymce.triggerSave === 'function') {
         tinymce.triggerSave();
      }
   },

   // Merkmal geändert, WerteOptions aktualisieren und Merkmal-Name
   // 21.06.2019
   merkmalChange: function(pos) {
      var merkmal_id   = $('#merkmal'+pos).val();
      var merkmal_name = $('#merkmal'+pos+' option:selected').text();

      $('.xmerkmal'+pos, $('.article_variante')).each( function() {
         $(this).html(merkmal_name);
      });

      $.post(admin_url_idx+'/ajax/artikel/merkmalChanged', {
         merkmal_id : merkmal_id,
         pos        : pos
      }, function(data) {
         if (data.status === 'ok') {
            $('.xwert'+pos, $('.block_start')).each( function() {
               $(this).html(data.html);
               $(this).closest('.block_start').prop('data-changed, 1');
            });
         }
      }, 'json' );
   },

   // Popup Bearbeitung Merkmale anzeigen
   // 21.06.2019
   merkmalePopup: function() {
      $.post(admin_url_idx+'/ajax/artikel/merkmalePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.show();
         }
      }, 'json'
      );
   },

   // Merkmal-Popup neue Zeile hinzufügen
   // 14.06.2019
   merkmalNew: function() {
      //$('#merkmale_popup').css('min-width', $('#merkmale_popup').width);
      var clone = $('#neuezeile').clone();
      clone.removeAttr('id').removeAttr('style');
      $('#merkmale_block').append(clone);
      Multibox.resize();

      if (($('#multibox').width()) - $('#merkmale_popup').width() > 30) {
         $('#merkmale_popup').css('min-width', ($('#merkmale_popup').width() + 20)+'px');
         Multibox.resize();
     }
   },

   // Merkmale speichern
   // 21.06.2019
   merkmaleSave: function(configurator) {
      this.merkmal_check = true;
      this.merkmal_arr1  = [];
      this.merkmal_arr2  = [];
      var back           = [];
      var i              = 0;
      var update         = [];
      var up             = 0;
      var del            = false;

      $('.line', ('#merkmale_block')).each( function() {
         if (parseInt($(this).attr('data-changed')) === 1) {
            var langs      = [];
            var merkmal_id = parseInt($(this).attr('data-merkmal_id'));
            var check      = '';

            $('input', $(this)).each( function(idx) {
               langs[idx] = $(this).val();
               check     += $(this).val();
            });

            // Noch nicht gespeichert
            if (merkmal_id === 0 && check === '') {
               $(this).remove();
            }

            else {
               back[i++] = {merkmal_id: merkmal_id, vals: langs};

               if (merkmal_id === 0) {
                  update[up++] = $(this);
               }

               if (check === '') {
                  $(this).remove();
                  del = true;
               }
            }
         }
      });

      var merkmal1_id = $('#merkmal1').val();
      var merkmal2_id = $('#merkmal2').val();

      $.post(admin_url_idx+'/ajax/artikel/merkmaleSave', {
         merkmale    : JSON.stringify(back),
         merkmal1_id : merkmal1_id,
         merkmal2_id : merkmal2_id
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();

            if (up > 0) {
               console.log(data.update);
            }

            var merkmal_options = data.html;
            $('#merkmal1').closest('.xmerkmal1').html(data.merkmal1_html);
            $('#merkmal2').closest('.xmerkmal2').html(data.merkmal2_html);

            if (del) {
               if (merkmal1_id !== $('#merkmal1').val()) {
                  // Werte aktualisieren
               }

               if (merkmal2_id !== $('#merkmal2').val()) {
                  // Werte aktualisieren
               }
            }
         }
      }, 'json' );
   },

   // Popup Bearbeitung Werte anzeigen
   // 21.06.2019
   wertePopup: function(typ) {
      $.post(admin_url_idx+'/ajax/artikel/wertePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.show();
         }
      }, 'json'
      );
   },

   // Werte-Popup neue Zeile hinzufügen
   // 24.06.2019
   wertNew: function() {
      //$('#merkmale_popup').css('min-width', $('#merkmale_popup').width);
      var clone = $('#neuezeile').clone();
      clone.removeAttr('id').removeAttr('style');
      $('#werte_block').append(clone);
      Multibox.resize();

      if (($('#multibox').width()) - $('#merkmale_popup').width() > 30) {
         $('#merkmale_popup').css('min-width', ($('#merkmale_popup').width() + 20)+'px');
         Multibox.resize();
     }
   },

   // Werte-Bild hochladen
   // 24.06.2019
   line_el : '',
   wertImageUpload: function(el) {
      var line    = $(el).closest('.line');
      Artikel.line_el = line;
      var wert_id = parseInt($(line).attr('data-wert_id'));

      // Id für Image-Ziel setzen
      $('.line', $('#werte_block')).each( function() {
         $('img', $(this)).removeAttr('id');
      });

      $('img', $(line)).attr('id', 'werte_img_upload');

      Artikel.imageUpload('werte_image', wert_id, 'werte_img_upload');
   },

   wertImageUploadCallback: function(data) {
      wert_id = data.wert_id;

      $(Artikel.line_el).attr('data-wert_id', data.wert_id);
   },

   // Werte-Bild löschen
   // 24.06.2019
   wertImageDelete: function(el) {
      var line    = $(el).closest('.line');
      var wert_id = parseInt($(line).attr('data-wert_id'));

      $.post(admin_url_idx+'/ajax/artikel/wertImageDelete', {
         wert_id : wert_id
         },
         function(data) {
            if (data.status === 'ok') {
               $('img', $(line)).attr('src', admin_url+'/img/nopic.png');
            }
         }, 'json'
      );
   },

   // Popup-Werte speichern
   // 24.06.2019
   werteSave: function(id) {
      var back           = [];
      var i              = 0;
      var update         = [];
      var up             = 0;

      $('.line', ('#werte_block')).each( function() {
         var langs      = [];
         var merkmal_id = $('select', $(this)).val();
         var wert_id    = parseInt($(this).attr('data-wert_id'));
         var check      = '';

         $('input', $(this)).each( function(idx) {
            langs[idx] = $(this).val();
            check     += $(this).val();
         });

         // Noch nicht gespeichert
         if (wert_id === 0 && check === '') {
            $(this).remove();
         }

         else {
            back[i++] = {merkmal_id: merkmal_id, wert_id: wert_id, vals: langs};
         }
      });

      var merkmal1_id = $('#merkmal1').val();
      var merkmal2_id = $('#merkmal2').val();

      $.post(admin_url_idx+'/ajax/artikel/werteSave', {
         werte       : JSON.stringify(back),
         merkmal1_id : merkmal1_id,
         merkmal2_id : merkmal2_id
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();

            if (up > 0) {
               console.log(data.update);
            }

            var wert_options = data.html;
            var werte1       = data.werte1;
            var werte2       = data.werte2;

            // Alle Varianten durchgehen
            $('.block_start').each(function() {
               var wert1 = $('.art_wert1', $(this)).val();
               var wert2 = $('.art_wert2', $(this)).val();

               $('.xwert1', $(this)).html(werte1);
               $('.xwert2', $(this)).html(werte2);

               $('.art_wert1 option[value='+wert1+']', $(this)).prop('selected', true);
               $('.art_wert2 option[value='+wert2+']', $(this)).prop('selected', true);


            });
         }
      }, 'json' );
   },

   // Checkboxen EAN / Downloadartikel
   // 10.07.2019
   eanCheck: function(mode) {
      var status = '';

      if (mode === 'ean') {
         status = ($('#ean_check').prop('checked') ? 'on' : 'off');
      }

      else {
         status = ($('#download_check').prop('checked') ? 'on' : 'off');
      }

      $.post(admin_url_idx+'/ajax/artikel/eanCheck', {
         mode   : mode,
         status : status
      },
      function(data) {
         if (data.status === 'ok') {
            if (mode === 'ean') {
               if (status === 'on') {
                  $('.ean_line').show();
               }

               else {
                  $('.ean_line').hide();
               }
            }

            else {
               if (status === 'on') {
                  $('.block_start').addClass('download');
               }

               else {
                  $('.block_start').removeClass('download');
               }
            }
         }
      }, 'json' );
   },

   // ************* Funktionen Grundeinheiten / EAN ***************************************
   grundeinheitenPopup: function() {
      parent_id = parseInt($('#parent_id').val());

      if (parent_id === 0) {
         alertbox(neuMsg);
         return;
      }

      $.post(admin_url_idx+'/ajax/artikel/grundeinheitenPopup', {
         parent_id : parent_id
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.width(400);
            Multibox.content(data.html);
            Multibox.show();
         }
      }, 'json' );
   },

   // Bei Änderungen im Popup Grundeinheit in EAN-Zeile übernehmen
   popupGrundeinheitenChange: function(el, ge) {
      $('#popup_grundeinheit').val($(el).val());
      $('#popup_einheit').val($('.label', $(el).closest('.ge')).html());
      $('#popup_gewicht_ge').val(ge); // INFO: für Mixer
   },

   grundeinheitenSave: function() {
      var parent_id            = parseInt($('#parent_id').val());
      var popup_ge_netto_aktiv = ($('#popup_ge_netto_aktiv').prop('checked') ? 'on' : 'off');
      var popup_grundeinheit   = $('#popup_grundeinheit').val();

      $('#ge_netto_aktiv').val(popup_ge_netto_aktiv);
      $('#grundeinheit').val(popup_grundeinheit);

      $.post(admin_url_idx+'/ajax/artikel/grundeinheitenSave', {
         parent_id      : parent_id,
         ge_netto_aktiv : popup_ge_netto_aktiv,
         grundeinheit   : popup_grundeinheit
      },
      function(data) {
         if (data.status === 'ok') {
         }
      }, 'json' );

      (popup_ge_netto_aktiv === 'on' ? $('.ge_edit_hide').show() : $('.ge_edit_hide').hide());

      var g = popup_grundeinheit;
      var show_grundeinheit = popup_grundeinheit;

      if (g === 'kg' || g === '100g' || g === '10g' || g === 'g') {
         $('.ge_edit_name').html('Artikelgewicht');

         if (g !== 'kg') {
            show_grundeinheit = 'g';
         }
      }

      else if (g === 'm' || g === 'dm' || g === 'cm' || g === 'mm') {
         $('.ge_edit_name').html('Artikelgröße');
      }

      else if (g === 'm2' || g === 'dm2' || g === 'cm2' || g === 'mm2') {
         $('.ge_edit_name').html('Artikelfläche');
      }

      else if (g === 'stk') {
         $('.ge_edit_name').html('Artikelanzahl');
      }

      else {
         $('.ge_edit_name').html('Artikelvolumen');

         if (g !== 'liter') {
            show_grundeinheit = 'ml';
         }
      }

      $('.ge_edit_einheit').html(show_grundeinheit);

      Multibox.close();

   },

   // ************* Funktionen Rechner ***************************************
   rechnerPopup: function() {
      parent_id = parseInt($('#parent_id').val());

      if (parent_id === 0) {
         alertbox(neuMsg);
         return;
      }

      $.post(admin_url_idx+'/ajax/artikel/rechnerPopup', {
         parent_id : parent_id
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.width(400);
            Multibox.content(data.html);
            Multibox.show();
         }
      }, 'json' );
   },

   popupRechnerChange: function(el, ge) {
      var grundeinheit = $(el).val();
      var grundeinheit_name = $('label', $(el).closest('.ge')).html();
//      $('#popup_grundeinheit').val(grundeinheit);
//      $('#popup_einheit').val($('.label', $(el).closest('.ge')).html());
      $('#popup_grundeinheit').val(grundeinheit);
      $('#popup_grundeinheit_name').val(grundeinheit_name);
   },

   popupRechnerSave: function() {
//      Artikel.articleSave($('#parent_id').val());
      var grundeinheit       = $('#popup_grundeinheit').val();
      var grundeinheit_name  = $('#popup_grundeinheit_name').val();
      $('#grundeinheit_rechner').val(grundeinheit);
      $('#grundeinheit_rechner_name').html(grundeinheit_name);

      Multibox.close();
   },

   download_article : 0,

   downloadArticleUpload: function(el, article_id) {
      var parent_id     = parseInt($('#parent_id').val());

      if (parent_id === 0) {
         alertbox(Artikel.neuMsg);
         return;
      }

      if ($('.fa-trash-alt', $(el).parent()).hasClass('xdelete')) {
         if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
            // Aufrufendes Objekt speichern
            Artikel.download_article = $(el).parent();
//            Confirmbox.head = 'Vorhandene Datei "' + $('#download_'+id+' div.art_download_del').attr('title') + '" wird überschrieben.\nKunden-Downloads funktionieren nicht mehr,\nwenn sich der Dateiname ändert!')';
//            Confirmbox.head = 'Vorhandene Datei wird überschrieben.\nKunden-Downloads funktionieren nicht mehr,\nwenn sich der Dateiname ändert!';
            Confirmbox.head = 'Vorhandene Datei überschreiben';
            Confirmbox.html = 'Kunden-Downloads funktionieren nicht mehr,\nwenn sich der Dateiname ändert!';
            Confirmbox.yes_function = 'Artikel.downloadArticleUpload("el", '+article_id+')';
            Confirmbox.show();
            return;
         }
      }

      else {
         Artikel.download_article = $(el).parent();
      }

      Confirmbox.yes_function = '';

      var upload_target = 'wert_upload_img';
      var target_url    = admin_url_idx+'/ajax/artikel/downloadArticleUpload';
      var file_types    = ['*'];
      var typ           = 'downloadArticleUpload';

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+parent_id+'\', \''+article_id+'\', false, false, false, \
         false, false, \'Callback_Download\');" />');
      $('#file_upload').click();
   },

   // Wird von Fileupload aufgerufen
   callbackUploadArticle: function(status, html, msg) {
      if (status === 'ok') {
         $(Artikel.download_article).html(html);
      }

      else {
         alertbox(msg);
      }
   },

   downloadArticleDelete : function(el, article_id) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         // Aufrufendes Objekt speichern
         Artikel.download_article = $(el).parent();
         Confirmbox.head = 'Achtung!';
         Confirmbox.html = 'Datei wird vom Server gelöscht und steht Kunden zum Download nicht mehr zur Verfügung!';
         Confirmbox.yes_function = 'Artikel.downloadArticleDelete("el", '+article_id+')';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx+'/ajax/artikel/downloadArticleDelete', {
         article_id : article_id
      },
      function(data) {
         if (data.status === 'ok') {
            $(Artikel.download_article).html(data.html);
         }

         else {
            alertbox('Datei konnte nicht gelöscht werden');
         }
      }, 'json');
   },

   downloadArticleDownload : function(el, article_id) {
      location.href = admin_url_idx+'/ajax/artikel/downloadArticleDownload/'+article_id;
//      $.post(admin_url_idx+'/ajax/artikel/downloadArticleDownload', {
//         article_id : article_id
//      },
//      function(data) {
//         if (data.status === 'ok') {
//         }
//      }, 'json');
   },

   // ************* Funktionen Staffelung ***************************************
   // Staffelpreis hinzufügen
   // 05.07.2019
   staffelungAdd: function(id) {
      Artikel.staffelungSave();

      $.post(admin_url_idx+'/ajax/artikel/staffelungAdd', {
         parent_id  : $('#parent_id').val(),
         staffelung : $('#staffelung_val').val(),
         neu        : 1
      },
      function(data) {
         if (data.status === 'ok') {
            $('#staffelung_block').html(data.html);
            Artikel.staffelungChange();
         }
      }, 'json' );
   },

   // Eingabe / Änderung Staffelungspreise
   // 05.07.2019
   staffelungChange: function(elem, mode) {
      var el = $(elem).closest('.staffelung_line');

      if (mode === 'delete') {
         el.remove();
      }

      else {
         var steuer = parseFloat($('#steuer').val());

         if (mode === 'netto') {
            var netto  = parseFloat(komma2point($('.staffelung_netto', el).val()));
            var brutto = netto * (1 + steuer / 100);
            $('.staffelung_netto', $(el)).val(point2komma(netto.toFixed(2)));
            $('.staffelung_netto_real', $(el)).val(netto.toFixed(9));
            $('.staffelung_brutto', $(el)).val(point2komma(brutto.toFixed(2)));
         }

         if (mode === 'brutto') {
            var brutto = parseFloat(komma2point($('.staffelung_brutto', $(el)).val()));
            var netto  = brutto / (1 + steuer / 100);
            $('.staffelung_netto', $(el)).val(point2komma(netto.toFixed(2)));
            $('.staffelung_netto_real', $(el)).val(netto.toFixed(9));
            $('.staffelung_brutto', $(el)).val(point2komma(brutto.toFixed(2)));
         }

         if (mode === 'klein') {
            var netto = parseFloat(komma2point($('.staffelung_netto', $(el)).val()));
            $('.staffelung_netto', $(el)).val(point2komma(netto.toFixed(2)));
            $('.staffelung_netto_real', $(el)).val(netto.toFixed(9));
         }
      }

      Artikel.staffelungSave();
   },

   // Staffelung als String in  #staffelung_val speichern
   // 05.07.2019
   staffelungSave: function() {
      var staff_arr = [];

      $('.staffelung_line', $('#staffelung_block')).each( function(idx) {
         if (!$(this).hasClass('is_new') || $('.staffelung_online', $(this)).prop('checked')) {
            staff_arr[idx]  = ($('.staffelung_online', $(this)).prop('checked') ? 'y' : 'n');
            staff_arr[idx] += ';' + $('.staffelung_stueck', $(this)).val();
            staff_arr[idx] += ';' + $('.staffelung_netto_real', $(this)).val();
         }
      });

      var val = '';

      for (var i = 0; i < staff_arr.length; i++) {
         if (val && staff_arr[i]) {
            val += '#';
         }

         val += staff_arr[i];
      }

      if (val === '') {
         val = 'n;100;-10';
      }

      $('#staffelung_val').val(val);
   },

   // ************* Funktionen Rabatte ***************************************
   changeRabatt : function(val) {
      $('#rabatte .rabattgruppe').removeClass('active');
      $('#rabatte .gruppe'+val).addClass('active');

      $('#rabattgruppe').val(val);
      $('#tabs_extra_rabatte').html(String.fromCharCode(parseInt(val) + 65));
   },

   images  : [],
   start   : 0,
   next    : 0,
   prev    : 0,
   last    : 0,

   showImagesStart: function(el) {
      Artikel.images  = [];
      var start       = 0;
      var num         = -1;
//      Artikel.next    = 0;
//      Artikel.prev    = 0;
      Artikel.last    = -1;


      $('.show_image', $('#vorschau')).each( function(idx, e) {
         // Zoom-Cache
         if ($(this).parent().parent().hasClass('kv-zoom-thumb')) {
            return;
         }

         num ++;

         Artikel.images[num] = $(this).attr('data-src');
         Artikel.last++;

         if (e === el) {
            start = num;
         }
      });

      Artikel.showImages(start, true);
   },

   showImages: function(show, first) {
      var next = 0;
      var prev = 0;

      if (show === 0) {
         next = 1;
         prev = Artikel.last;
      }

      else if (show === Artikel.last) {
         next = 0;
         prev = Artikel.last - 1;
      }

      else {
         next = show + 1;
         prev = show - 1;
      }

      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.content('<div id="prev_img"><img onload="Artikel.zoomStart()" src="'+Artikel.images[show]+'" alt="" /><div id="prev_last"></div><div id="prev_next"></div></div>');
      Multibox.width('auto');
      Multibox.show();
      $('#multibox').css('opacity', 0);
   },

   zoomStart: function() {
      $('#multibox #prev_img').css('opacity', 0);
      Multibox.resize();

      $('#multibox #prev_img').animate({'opacity' : 1}, 250, function() {
         Multibox.resize();
         Multibox.resize();
      });
   },

   // Noch nicht getestet
   fotoMode: function(id) {
      // 1: Global
      // 2: Set
      // 3: Bild
      var org_mode = parseInt($('#org_mode').val());

      // Von Bild -> Global nicht möglich
      if (id === 1 && org_mode === 3) {
         id = 2;
      }

      if (org_mode === 3) {
         $('#foto_mode1').attr('disabled', 'disabled');
      }

      $('#preis_mode').val(id);

      // Auf globalen Preis setzen / wieder herstellen
      if (id === 1) {
         $('#foto_mode1').attr('checked', 'checked');
         $('#foto_mode2').removeAttr('checked');
         $('#foto_mode3').removeAttr('checked');

         $('.foto_input').attr('disabled', 'disabled');
         for (i = 0; i < 7; i++) {
            var netto = foto_preis[i][0];
            $('#netto_'+i).val(netto);
            Artikel.compute($(this), "netto", i);
         }
      }

      // Set-Preis setzen, wenn Global oder Set. Bei Bild Set-Preis wieder herstellen.
      if (id === 2) {
         $('#foto_mode1').removeAttr('checked');
         $('#foto_mode2').attr('checked', 'checked');
         $('#foto_mode3').removeAttr('checked');

         $('.foto_input').removeAttr('disabled');
         for (i = 0; i < 7; i++) {
            var netto = foto_preis[i][1];
            $('#netto_'+i).val(netto);
            Artikel.compute($(this), "netto");
         }
      }

      if (id === 3) {
         $('#foto_mode1').removeAttr('checked');
         $('#foto_mode2').removeAttr('checked');
         $('#foto_mode3').attr('checked', 'checked');

         $('.foto_input').removeAttr('disabled');
         for (var i = 0; i < 7; i++) {
            var netto = foto_preis[i][2];
            $('#netto_'+i).val(netto);
            Artikel.compute("netto", i);
         }
      }
//      this.articleChanged = new Array();
      $('#foto_mode').val(id);
   },

   indVersand : function(mode) {
      if (!$('#ind_versand_steuer').length) {
         var netto  = parseFloat(komma2point($('#versand_preis').val()));
         $('#versand_preis').val(point2komma(netto.toFixed(2)));

         Artikel.checkVersandfrei();
         return;
      }

      var steuer = parseFloat($('#ind_versand_steuer').val());

      if (mode === 'brutto') {
         var brutto = parseFloat(komma2point($('#versand_preis_brutto').val()));
         var netto  = brutto / steuer;

         $('#versand_preis_brutto').val(point2komma(brutto.toFixed(2)));
         $('#versand_preis_oben').val(point2komma(netto.toFixed(2)));
         $('#versand_preis').val(point2komma(netto.toFixed(2)));
      }

      else if (mode === 'netto_tab1') {
         var netto  = parseFloat(komma2point($('#versand_preis').val()));
         var brutto = netto * steuer;

         $('#versand_preis_brutto').val(point2komma(brutto.toFixed(2)));
         $('#versand_preis_oben').val(point2komma(netto.toFixed(2)));
         $('#versand_preis').val(point2komma(netto.toFixed(2)));
      }

      else if (mode === 'netto_tab3') {
         var netto  = parseFloat(komma2point($('#versand_preis_oben').val()));
         var brutto = netto * steuer;

         $('#versand_preis_brutto').val(point2komma(brutto.toFixed(2)));
         $('#versand_preis_oben').val(point2komma(netto.toFixed(2)));
         $('#versand_preis').val(point2komma(netto.toFixed(2)));
      }

      Artikel.checkVersandfrei();
   },

   checkVersandfrei: function() {
      var versand_preis = parseFloat($('#versand_preis').val());
      var individuell   = $('#versandfrei').length;

      if (individuell) {
         if ($('#versandfrei_check').prop('checked') ) {
            $('#img_versandfrei').show();
            $('#versandfrei').show();
         }

         else {
            $('#img_versandfrei').hide();
            $('#versandfrei').hide();
         }
      }

      else {
         if (versand_preis === 0) {
            $('#img_versandfrei').show();
         }

         else {
            $('#img_versandfrei').hide();
         }
      }
   },


   zoomPopup: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/zoomPopup', {
      },

      function(data) {
         if (data.status === 'ok') {
            Multibox.width(448);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.show();
         }
      }, 'json');
   },

   zoomPopupSave: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/zoomPopupSave', {
         detailbild : $('input[name=detailbild]:checked', $('#popup_zoom')).val()
      },

      function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }
      }, 'json');
   },

   artikelgrafikCallback: function(img, img_id) {
      $('#img_artikelgrafik'+img_id).attr('src', img);
      var parent = $('#artikelgrafik_preview'+img_id)[0].closest('.artikelgrafik_block');
console.log(parent);
      $('.delete_ag', $(parent)).removeClass('ag_not_active');

    },

    energyefficiency_imageCallback: function (img, img_id) {
        $('img.energyefficiency_preview').attr('src', img);
        $("#energyefficiency_preview_delete").removeClass('ag_not_active');
    
        //var parent = $('#energyefficiency_preview')[0].closest('.artikelgrafik_block');
        //console.log(parent);
        //$('.delete_ag', $(parent)).removeClass('ag_not_active');

    },


    

      dummy : function() {}
};

// Bilder groß anzeigen
$(function() {
   if ($('#vorschau').length) {
      $("#vorschau" ).on('click', '.show_image', function() {
         Artikel.showImagesStart(this);
      });
   }
});

   // ************* Funktionen Preismatrix ***************************************
var Matrix = {
   matrix_button : 0,
   matrix_id     : 0,

   popup: function(el, article_id) {
//      Multibox.close();
      Matrix.matrix_button = el;
      Matrix.matrix_id     = article_id;

      $.post(admin_url_idx + '/ajax/artikel/matrixPopup', {
         article_id: article_id
      },

      function(data) {
         if (data.status === 'ok') {
            Multibox.width('auto');
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.show();
         }
      }, 'json');
   },

   // Matrix von Hauptartikel importieren
   copy: function(article_id) {
//      Multibox.close();

      $.post(admin_url_idx + '/ajax/artikel/matrixCopy', {
         article_id : article_id
      },

      function(data) {
         if (data.status === 'ok') {
            Multibox.width('auto');
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.show();
         }

         else {
            alertbox('Matrix konnte nicht kopiert werden');
         }
      }, 'json');
   },

   save: function (art_id) {
      var pos_x   = [];
      var rows    = 0;
      var columns = 0;
      var preise  = [];

      // Oberste Spalte Werte Breite
      $('#matrix .title input').each( function(idx) {
         pos_x[idx] = $(this).val();
      });

      $('#matrix .preise').each( function(idx1) {
         var pos_y = 0;
         var preise_zeile = [];

         $('input', $(this)).each( function(idx2) {

            // Höhe lesen (1. Spalte)
            if (idx2 === 0) {
               pos_y = $(this).val();
            }

            // Weitere Spalten Preise
            else {
               // Breite : Höhe : Preis
               preise_zeile[idx2 - 1] = pos_x[idx2 - 1] + ':' + pos_y + ':' + $(this).val();
            }
         });

         rows++;
         preise[idx1] = preise_zeile;

      });

      columns = pos_x.length;

      // Button Matrix Farbe: aktiv - grün; inaktiv - grau
      if ($('#matrix_check').prop('checked')) {
         $(Matrix.matrix_button).removeClass('button-grau').addClass('button-gruen');
      }

      else {
         $(Matrix.matrix_button).removeClass('button-gruen').addClass('button-grau');
      }

      // Per AJAX senden
      $.post(admin_url_idx + '/ajax/artikel/matrixSave',
         {
            art_id: art_id,
            preise: preise,
            matrix_check   : ($('#matrix_check').prop('checked') ? 'on' : 'off'),
            matrix_breite  : $('#matrix_breite').val(),
            matrix_hoehe   : $('#matrix_hoehe').val(),
            matrix_einheit : $('#matrix_einheit').val(),
            matrix_bmin    : $('#matrix_bmin').val(),
            matrix_hmin    : $('#matrix_hmin').val(),
            matrix_komma   : $('#matrix_komma').val(),
            rows           : rows,
            columns        : columns,
            steuersatz     : $('#steuersatz').val()
         },

         function(data) {
            if (data.status === 'ok') {
               if ($('#matrix_check').prop('checked')) {
                  $(Matrix.matrix_button).removeClass('button button_border').addClass('button_ci');
               }

               else {
                  $(Matrix.matrix_button).removeClass('button_ci').addClass('button button_border');
               }

               // Matrix nach Speichern scließen
               Multibox.close();
            }
         }, 'json'
      );
   },

   check: function() {
      Matrix.checkKomma();
        var b_arr = [];
      var h_arr = [];
      var brutto = parseInt();

      $('#matrix tr').each(function(idx1, f) {
         var counter = idx1;
         var el = $(this);

         // Oberste Zeile -> Breiten
         if (idx1 === 0) {
            $('.mass', $(el)).each(function(idx) {
               var val = parseFloat(0 + $(this).val());
               b_arr[idx] = val;
            });
         }

         // Folgende Zeilen
         else {
            // 1. Spalte -> Höhen
            var el    = $('.mass', $(this));
            var hoehe = parseFloat(0 + $(el).val());

            h_arr[idx1 - 1] = hoehe;
            $(el).removeClass('too_big');

            // Preise auf Bereich testen
            $('.preis', $(this)).each(function(idx2) {
               $(this).css('visibility', 'visible');
               $(this).parent().attr('title', '');

               // 1. Preis ist Variantenpreis
               if (idx1 !== 1 || idx2 !== 0) {
                  var preis = parseFloat(komma2point($(this).val()));

                  // Breite oder Höhe nicht angegeben
                  if (b_arr[idx2] === 0 || hoehe === 0) {
                     $(this).css('visibility', 'hidden');
                     $(this).parent().attr('title', 'Zuerst Breite und Höhe eigeben');
                  }

                  // Preis im gültigen Bereich
                  else {
                     $(this).val(point2komma(preis.toFixed(2)));
                  }
               }
            });
         }
      });
   },

   checkKomma: function() {
      var komma = parseInt($('#matrix_komma').val());
      var val   = '';


      $('#matrix .mass').each(function(){
         val = parseFloat('0'+komma2point($(this).val()));

         if (val === 0) {
            val = '';
         }
         else {
            val = point2komma(val.toFixed(komma));
         }

         $(this).val(val);
      });

      val = parseFloat(komma2point($('#matrix_bmin').val()));

      if (val === NaN) {
         val = 0;
      }

      else {
         val = point2komma(val.toFixed(komma));
      }

      $('#matrix_bmin').val(val);

      val = parseFloat('0'+komma2point($('#matrix_hmin').val()));

      if (val === NaN) {
         val = 0;
      }

      else {
         val = point2komma(val.toFixed(komma));
      }

      $('#matrix_hmin').val(val);
   },

   // Spalte rechts hinzufügen
   addColumn: function () {
      var anz = $('#matrix tr').length;

      $('#matrix tr').each(function(idx) {
         if (idx === 0) {
            $(this).append('<td><input class="txt_inp mass" value="0" onchange="Matrix.check();" type="text"></td>');
         }

         else {
            $(this).append('<td><input type="text" class="txt_inp preis" value="0" onchange="Matrix.check();"></td>');
         }
      });

      Matrix.check();
      Matrix.checkKomma();
      Multibox.resize();
   },

   // Zeile unten hinzufügen
   addRow: function() {
      var anz  = $('#matrix tr:first td').length - 1;
      var adds = '';
      var add  = '<td><input type="text" class="txt_inp preis" value="" onchange="Matrix.check();"></td>';

      for (var i = 0; i < anz; i++) {
         adds += add;
      }

      $('#matrix table').append('<tr class="preise"><td><input type="text" class="txt_inp mass" value="0" onchange="Matrix.check();"></td>'+adds+'</tr>');

      Matrix.check();
      Matrix.checkKomma();
      Multibox.resize();
   },

   // letze Spalte entfernen
   delColumn: function() {
      var anz = $('#matrix tr:first td').length - 1;
      var found = anz;

      if (anz <= 10) {
         $('#matrix tr.title td').each(function(idx) {
            var test = $('input', $(this));
            if ($(test).val() !== '') {
               found = idx;
            }
         });

         // Alle Spalten durchgehen
         $('#matrix tr').each(function(idx1) {
            // Alle Zeilen durchgehen
            $('td', $(this)).each(function(idx2) {
               // Gesuchte Spalte?
               if (idx2 === found) {
                  // Zeile 0 ist Breite
                  if (idx1 === 0) {
                     $('input', $(this)).val('');
                  }

                  // sonst Preise
                  else {
                     $('input', $(this)).val('0,00');
                  }
               }
            });
         });

         Matrix.check();
         $('table', $('#matrix')).css('border-collapse', 'unset').css('border-collapse', 'collapse');
         Multibox.resize();
         return;
      }

      $('#matrix tr').each(function() {
         $('td:last', $(this)).remove();
      });

      Multibox.resize();
      Matrix.check();
      $('table', $('#matrix')).css('border-collapse', 'unset').css('border-collapse', 'collapse');
   },

   // Unterste Zeile entfernen
   delRow: function() {
      var anz = $('#matrix tr').length - 1;

      if (anz <= 10) {
         var found = 0;

         $('#matrix tr.preise').each(function(idx) {
            var test = $('input.mass', $(this));
            if ($(test).val() !== '') {
               found = idx + 1; // obertst Zeile berücksichtigt
            }
         });

         $('#matrix tr').each(function(idx) {
            if (idx === found) {
               $('td', $(this)).each(function(idx) {
                  if (idx === 0) {
                     $('input', $(this)).val('');
                  }

                  else {
                     $('input', $(this)).val('0,00');
                  }
               });
            }
         });

         Matrix.check();
         Multibox.resize();
         return;
      }

      $('#matrix tr:last').remove();
      Multibox.resize();
   },

   import: function(article_id) {
//      matrix_el = el;
//      matrix_id = article_id;

      var parent_id     = parseInt($('#parent_id').val());
      var upload_target = 'wert_upload_img';
      var target_url    = admin_url_idx+'/ajax/artikel/matrixImport';
      var file_types    = ['csv'];
      var typ           = 'matrix_import';

      if (parent_id === 0) {
         alertbox(Artikel.neuMsg);
         return;
      }

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" accept=".csv" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+parent_id+'\', \''+article_id+'\', false, false, false, \
         false, false, \'Callback_MatrixImport\');" />');
      $('#file_upload').click();
   },

   dummy: function() {}

};


var Naehrwerte = {
   save: function() {
      var params = {};

      params.parent_id        = $('#parent_id').val();
      params.naehrwerte_check = 'on';
      params.brennwert        = $('#brennwert').val();
      params.fett             = $('#fett').val();
      params.f_saeure         = $('#f_saeure').val();
      params.k_hydrate        = $('#k_hydrate').val();
      params.zucker           = $('#zucker').val();
      params.ballast          = $('#ballast').val();
      params.eiweiss          = $('#eiweiss').val();
      params.salz             = $('#salz').val();

      var lang = langs.split(';');

      for (var l = 0; l < lang.length; l++) {
         for (var i = 1; i <= 12; i++) {
            params['zutat_'+lang[l]+'_'+i] = $('#zutat_'+lang[l]+'_'+i).val();
         }

         params['zutat_'+lang[l]+'_allergiker'] = $('#zutat_'+lang[l]+'_allergiker').val();
      }

      $.post(admin_url_idx + '/ajax/artikel/saveNaehrwerte',
         params,
         function(data) {
            showFeedback($('#naehrwere_zutaten').closest('section'));
//            var color = $('#naehrwere_zutaten').closest('section').css('background-color');
//            $('#naehrwere_zutaten').closest('section').animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 100, function() { $(this).animate({ 'background-color' : color }, 250); });
         }, 'json'
      );
   },

   joule2cal: function(el) {
      // Joule -> Kalorien
      if (el === 0) {
         $('#brennwert_cal').val(Math.round(parseInt($('#brennwert').val() / 4.1868)));
      }

      // Kalorien -> Joule
      else {
         $('#brennwert').val(Math.round(parseInt($('#brennwert_cal').val() * 4.1868)));
      }
   },

   nwCheck: function(el) {
      var val = parseFloat(komma2point($(el).val()));
      $(el).val(point2komma(val.toFixed(2)));
   }
};

var Mod360grad = {
// Module 360grad
//   multiImageShow: function(pict_number) {
   load: function() {
      $.post(admin_url_idx+'/ajax/artikel/load360',
         {
            parent_id   : $('#parent_id').val()
         },

         function(data) {
            if (data.status === 'ok') {
               Multibox.content(data.html);
               Multibox.bg_close = true;
               Multibox.close_btn = true;
               Multibox.width(1442);
               Multibox.show();
            }
         },
         'json'
      );
   },

   refresh: function() {
      $.post(admin_url_idx+'/ajax/artikel/refresh360',
         {
            parent_id   : $('#parent_id').val()
         },

         function(data) {
            if (data.status === 'ok') {
               $('#file_uploader360').html(data.html);
               Multibox.resize();
            }
         },
         'json'
      );
   },

   delete: function () {
      $.post(admin_url_idx+'/ajax/artikel/delete360',
         {
            parent_id   : $('#parent_id').val()
         },

         function(data) {
            if (data.status === 'ok') {
               $("#more_images360").fileinput('destroy');
               Mod360grad.load();
            }
         },
         'json'
      );
   }
};

// Modul Artikeltimer
var Timer = {
   time_offset   : 0,
   lastsync      : -1,
   timer_running : false,

   // Timer speichern
   // 30.06.2019
   save: function() {
      $.post(admin_url_idx+'/ajax/artikel/timerSave', {
         parent_id         : $('#parent_id').val(),
         timer_check       : ($('#timer_check').prop('checked') ? 'on' : 'off'),
         timer_menge       : $('#t_menge').val(),
         t_jahr            : $('#t_jahr').val(),
         t_monat           : $('#t_monat').val(),
         t_tag             : $('#t_tag').val(),
         t_stunde          : $('#t_stunde').val(),
         t_minute          : $('#t_minute').val(),
         timer_anzeige     : ($('#timer_anzeige').prop('checked') ? 'on' : 'off'),
         timer_art_disable : ($('#timer_art_disable').prop('checked') ? 'off' : 'on')
      }, function(data) {
         if (data.status === 'ok') {
            // alertbox(data.msg, '', 3);
            var color = $('#zubehoerslider').closest('section').css('background-color');
            $('#timer').closest('section').animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 100, function() { $(this).animate({ 'background-color' : color }, 250); });
            $('#t_jahr').val(data.t_jahr);
            $('#t_monat').val(data.t_monat);
            $('#t_tag').val(data.t_tag);
            $('#t_stunde').val(data.t_stunde);
            $('#t_minute').val(data.t_minute);
            $('#t_minute').val(data.t_minute);
            $('#timer_end').val(data.timer_end);
            $('#timer_check').prop('checked', (data.timer_check === 'y' ? true : false));

            if (data.timer_check === 'y') {
               $('#tabs_extra_timer').addClass('active');
            }

            else {
               $('#tabs_extra_timer').removeClass('active');
            }

//         $('#tabs_extra_timer')
            Timer.lastsync = -1;
            Timer.countdown();
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Timer starten
   // 30.06.2019
   countdown: function () {
      // Timer nicht aktiv
      if ($('#timer_check').prop('checked') !== true || parseInt($('#timer_end').val()) < 1000) {
         $('#tabs_extra_timer').html('');
         Timer.time_offset = 0;
         return;
      }

      // Mit Server-Zeit synchronisieren
      var time = Date.now();
//      if (Timer.lastsync < 0 || Math.abs(time/1000 - Timer.lastsync/1000) > 3000) {
      if (Timer.lastsync < 0) {
         Timer.lastsync = 0;
         Timer.sync();
      }

      else {
         Timer.lastsync = time;
      }

      // Aktuelle Zeit um Server-Offset korrigiert
      var time_now  = Math.round(Date.now() / 1000) + Timer.time_offset; // in s
      var time_end  = parseInt($('#timer_end').val());  // in s
      var time_diff = time_end - time_now;

      // Zeit abgelaufen (+ 5 s Sicherheit, damit wirklich abgelaufen bei Hängern)?
      if (time_diff < -5) {
         $('#tabs_extra_timer').html('');
         return true;;
      }

      // Negtive Zeit auf 0
      if (time_diff < 0) {
         time_diff = 0;
      }

      // Zeit-Differenz umrechnen
      var tage     = Math.floor(time_diff / (60*60*24));
      var stunden  = Math.floor(time_diff / (60*60)) % 24;
      var minuten  = Math.floor(time_diff / 60) %60;

      if (stunden < 10) {
         stunden = '0'+stunden;
      }

      $('#tabs_extra_timer').html(tage+'T '+stunden+':'+minuten);
      if (minuten < 10) {
         minuten = '0'+minuten;
      }


      // Funktion ruft sich nach einer Sekunde wieder auf
      Timer.countdown_running = window.setTimeout('Timer.countdown()', 1000);
   },

// Zeit-Synchronistion mit Server
// 30.06.2019
   sync: function() {
      // Startzeit merken
      var starttime = Date.now();

      // Zeit vom Server abrufen
      $.post({
         url   : admin_url_idx+'/ajax/artikel/timerSync',
         type  : "GET",
         async : false,

         success: function(data) {
            if (data.status === 'ok') {
               // Zeit nach Server-Abfrage
               var endtime = Date.now();

               // (halbe) Zeit zwischen Anfrage und Antwort
               var laufzeit = Math.floor((endtime - starttime) / 2000); // in s
               var time_diff = Math.round((data.time - endtime)  / 1000) - laufzeit;
               Timer.time_offset = time_diff;
            }
         },

         error: function(){
         },

         complete: function(){
         }
      });
   }
};

// Start-Funktion, wenn Timer vorhanden
// 30.06.2019
$(function() {
   if ($('#timer').length) {
      Timer.countdown();
   }
});

// ************* Modul Megakonfigurator ***************************************
var Megakonfigurator = {
   // configurator_box Merkmal geändert -> Alle Einträge bis auf 1. löschen. Werte-Liste neu laden
   // 06.07.2019
   merkmalChanged: function(el) {
      var box        = $(el).closest('.configurator_box');
      var merkmal_id = $('.conf_merkmal select option:selected', $(box)).val();

      // Alle Einträge bis auf 1. löschen
      $('.configurator_zeile', $(box)).each(function(idx, elem) {
         if (idx !== 0) {
            $(elem).remove();
         }
      });

      $.post(admin_url_idx + '/ajax/artikel/configuratorWerteOptions', {
         merkmal_id : merkmal_id,
         wert_id    : -1
      },
      function(data) {
         if (data.status === 'ok') {
            $('.conf_wert', $(box)).html(data.wert);
            $(box).attr('data-merkmal', merkmal_id);
            Megakonfigurator.werteCheck();
            Megakonfigurator.sameHeight();
         }
      }, 'json');
   },

   // configurator_box hinzufügen (2x)
   // 18.07.2019
   boxAdd: function() {
      var anzahl = $('.configurator_box').length + 2;

      var boxadd1 = $('.configurator_box', $('#add_configurator')).clone();
      boxadd1.attr('data-box_id', anzahl).attr('data-merkmal', '-1');
      $('.span_id', boxadd1).html(anzahl);
      $('.configurator_box_title', boxadd1).html('Merkmal '+anzahl);
      $('.configurator_titel .pos3', boxadd1).html('Merkmal '+anzahl);
      $('.configurator_titel .pos4', boxadd1).html('Werte '+anzahl);
      $('.configurator_zeile .newdesign', boxadd1).attr('id', 'conf_check_'+anzahl+'_0');
      $('.configurator_zeile label', boxadd1).attr('for', 'conf_check_'+anzahl+'_0');
      $('.dropdownm_radio', boxadd1).attr('id', 'dropdownm'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdownm_label', boxadd1).attr('for', 'dropdownm'+anzahl);
      $('.dropdowns_radio', boxadd1).attr('id', 'dropdowns'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdowns_label', boxadd1).attr('for', 'dropdowns'+anzahl);
      $('.dropdowny_radio', boxadd1).attr('id', 'dropdowny'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdowny_label', boxadd1).attr('for', 'dropdowny'+anzahl);
      $(boxadd1).appendTo('#configurator_block');

      anzahl++;

      var boxadd2 = $('.configurator_box' ,$('#add_configurator')).clone();
      boxadd2.attr('data-box_id', anzahl).attr('data-merkmal', '-1');
      $('.span_id',boxadd2).html(anzahl);
      $('.configurator_box_title', boxadd2).html('Merkmal '+anzahl);
      $('.configurator_titel .pos3', boxadd2).html('Merkmal '+anzahl);
      $('.configurator_titel .pos4', boxadd2).html('Werte '+anzahl);
      $('.configurator_zeile .newdesign', boxadd2).attr('id', 'conf_check_'+anzahl+'_0');
      $('.configurator_zeile label', boxadd2).attr('for', 'conf_check_'+anzahl+'_0');
      $('.dropdownm_radio', boxadd2).attr('id', 'dropdownm'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdownm_label', boxadd2).attr('for', 'dropdownm'+anzahl);
      $('.dropdowns_radio', boxadd2).attr('id', 'dropdowns'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdowns_label', boxadd2).attr('for', 'dropdowns'+anzahl);
      $('.dropdowny_radio', boxadd2).attr('id', 'dropdowny'+anzahl).attr('name', 'dropdown'+anzahl);
      $('.dropdowny_label', boxadd2).attr('for', 'dropdowny'+anzahl);
      $(boxadd2).appendTo('#configurator_block');

//      $('#configurator_block').append('<div class="claer"></div>');
      Megakonfigurator.sameHeight();
   },

   // configurator_box - Neue Zeile hinzufügen
   // 18.07.2019
   wertAdd: function(el) {
      var box    = $('.configurator_box_content', $(el).closest('.configurator_box'));
      var box_id = $(el).closest('.configurator_box').attr('data-box_id');
      var zeile  = $('.configurator_zeile', box).length;

      $(box).append($(box).find('.configurator_zeile:first').clone());
      var newline = $(box).find('.configurator_zeile:last');

      $(newline).find('.conf_merkmal').html($(box).find('.configurator_zeile:first .conf_merkmal select option:selected').text());
      $(newline).find('.conf_merkmal').addClass('ellipsis').addClass('conf_merkmal_text');
      $(newline).find('.wert_art_nr').val('');
      $(newline).find('.newdesign').prop('checked', true);
      $(newline).find('.newdesign').attr('id', 'conf_check_'+box_id+'_'+zeile);
      $(newline).find('label').attr('for', 'conf_check_'+box_id+'_'+zeile);
      

      var wert = $(newline).find('.conf_wert option[0]').val();
      if (wert === '-1') {
         $(newline).find('.conf_wert option[0]').prepend('<option value="-1"><Bitte wählen</option>');
      }

      $(newline).find('.conf_wert').val('-1');

      Megakonfigurator.sameHeight();

   },

   // Popup Merkmale anzeigen
   // 06.07.2019
   merkmalePopup: function() {
      $.post(admin_url_idx+'/ajax/artikel/configuratorMerkmalePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.show();
         }
      }, 'json'
      );

   },

   // Merkmal-Popup - Neues Merkmal
   // 18.07.2019
   merkmalNeu: function() {
      var copy = $('#neuezeile').html();
      $('#merkmale_block').append('<div class="zeile" data-changed="0" data-merkmal_id="0">'+copy+'</div>');
      Multibox.resize();
   },

   // Popup Merkmale Speichern
   // 18.07.2019
   merkmaleSave:  function() {
      var lang   = langs.split(';');
      var params = [];
      var i      = 0;

      $('.zeile', $('#merkmale_block')).each( function() {
         var parent = $(this).closest('.zeile');
         var merkmale = {};

         if (parseInt(parent.attr('data-changed')) === 1) {
            merkmale.merkmal_id = parent.attr('data-merkmal_id');

            for (var j = 0; j < lang.length; j++) {
               merkmale['merkmal_'+lang[j]] = $('.merkmal_'+lang[j], $(this)).val();
            }

            params[i++] = merkmale;
         }
      });

      if(params.length) {
         $.post(admin_url_idx + '/ajax/artikel/configuratorMerkmaleSave', {
            merkmale : JSON.stringify(params)
         },
         function(data) {
            Multibox.close();

            if (data.status === 'ok') {
               var merkmal      = data.merkmal;
//               var merkmal_name = data.merkmal_name;

               $('.configurator_box').each(function() {
                  var option = $('.conf_merkmal select option:selected', $(this)).val();
                  $('.conf_merkmal select', $(this)).html(merkmal);
                  $('.conf_merkmal select', $(this)).val(option);
                  var name = $('.conf_merkmal select option:selected', $(this)).text();

                  $('.conf_merkmal', $(this)).each(function(idx, obj) {
                     if (idx !== 0) {
                        $(obj).html(data.name);
                     }
                  });
               });
            }
         }, 'json' );
      }
   },

   // Popup Werte anzeigen
   // 18.07.2019
   wertePopup: function() {
      $.post(admin_url_idx+'/ajax/artikel/configuratorWertePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.show();
         }
      }, 'json'
      );
   },

   // Konfigurator Block Zeile löschen
   // 18.07.2019
   wertDelete: function(el) {
      var is_fixed = ($('#configurator_add').css('visibility') === 'hidden' ? true : false);
      var box = $(el).closest('.configurator_box');
      var anzahl = $(box).find('.configurator_zeile').length;

      // Wenn letzte Zeile, Box löschen
      if (anzahl === 1) {
         if (is_fixed) {
            $(box).find('.configurator_zeile:first .conf_merkmal select').prepend('<option value="0">Bitte wählen</option>');
            $(box).find('.configurator_zeile:first .conf_merkmal select').val('-1');
            $(box).attr('data-merkmal', '-1');
         }
         else {
            $(box).remove();
         }
      }

      else {
         // 1. Zeile Löschen, Selct-Boc in 2. Zeile verschieben
         if ($(el).closest('.configurator_zeile').find('.conf_merkmal select').length) {
            var selectbox = $(el).closest('.configurator_zeile').find('.conf_merkmal').html();
            $(el).closest('.configurator_zeile').remove();
            $(box).find('.configurator_zeile:first .conf_merkmal').html(selectbox);
         }
         else {
            $(el).closest('.configurator_zeile').remove();
         }
      }

      Megakonfigurator.sameHeight();
      Megakonfigurator.werteCheck();
   },

   // Werte-Popup neu Zeile hinzufügen
   // 18.07.2019
   wertNeu:  function() {
      var copy = $('#werte_zeile_neu').html();
      //$('#werte_block').append('<div class="werte_zeile" data-werte_id="0" data-changed="0">'+copy+'</div>');
      $('#werte_block').append(copy);
      Multibox.resize();
   },

   // Werte-Popup Bild hochladen
   // 18.07.2019
   wert_parent : 0,
   wertImgUpload: function(el) {
      var parent = $(el).closest('.werte_zeile');
      Megakonfigurator.wert_parent = parent;

      $('.werte_zeile', $('#werte_block')).each(function() {
         if ($('.werte_img img', $(this)).attr('id') !== undefined) {
//            $('.werte_img img', $(this)).attr('id').remove();
         }
      });

      $('.werte_img img', parent).attr('id', 'wert_upload_img');

      var parent_id     = parseInt($('#parent_id').val());
      var merkmal_id    = parseInt($('.xmerkmalid', parent).val());
      var wert_id       = parent.attr('data-werte_id');
      var typ           = 'configurator_wert';
      var upload_target = 'wert_upload_img';
      var target_url    = admin_url_idx+'/ajax/artikel/imageUpload';
      var file_types    = ['jpg', 'png'];

      if (parent_id === 0) {
         alertbox(Artikel.neuMsg);
         return;
      }

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+merkmal_id+'\', \''+wert_id+'\', false, false, false, \
         true, \''+upload_target+'\', \'Megakonfigurator.wertImgCallback\');" />');
      $('#file_upload').click();

      return false;
   },

   wertImgCallback: function(data) {
      $(Megakonfigurator.wert_parent).attr('data-werte_id', data.wert_id);
      $('.werte_img img', $(Megakonfigurator.wert_parent)).attr('id', '');
//      alert(param);
   },

   wertImgDelete: function(el) {
      var parent  = $(el).closest('.werte_zeile');
      var wert_id = parent.attr('data-werte_id');

      $.post(admin_url_idx + '/ajax/artikel/configuratorImgDelete', {
         wert_id: wert_id
      }, function(data) {
         if (data.status === 'ok') {
            $('img', parent).attr('src', admin_url+'/img/nopic.png');
            $('.delete', parent).hide();
         }
         else  {
            alertbox('Grafik konnte nicht gelöscht werden');
         }
      }, 'json');
   },

   // Werte-Popup speichern
   //
   wertePopupSave:  function() {
      var i = 0;

      $('.werte_zeile', '#werte_block').each( function() {
         if (parseInt($(this).attr('data-changed')) !== 1) {
            return;
         }

         var params           = {};
         var merkmal_id       =  parseInt($('.xmerkmalid option:selected', ((this))).val());
         params['merkmal_id'] =  merkmal_id;
         params['wert_id']    =  $(this).attr('data-werte_id');

         // Alle aktivierten Sprachen
         var lang = langs.split(';');

         for (var j = 0; j < lang.length; j++) {
            params['wert_'+lang[j]] = $('.xwert_'+lang[j], $(this)).val();
         }

         $.post(admin_url_idx+'/ajax/artikel/configuratorWertePopupSave',
            params,
            function(data) {
               if (data.status === 'ok') {
                  var wert_options = data.wert_option;

                  $('.configurator_box').each(function(idx, obj) {
                     if ($(obj).data('merkmal') === merkmal_id) {
                        $(obj).find('.conf_wert').each(function(idx2, obj2) {
                           var wert = $(obj2).find('option:selected').val();

                           if ($(obj2).find('option[value="-1"]')) {
                              $(obj2).html(wert_options);
                           }
                           else {
                              $(obj2).html('<option value="-1">Bitte wählen</option>'+wert_options);
                           }

                           $(obj2).val(wert);
                        });
                     }
                  });
               }
            }, 'json'
         );
      });

      Multibox.close();
   },

   // <input id="configurator_val" Value erstellen
   // 18.07.2019
   werteCheck: function() {
      var arr      = [];
      var text_arr = [];
      var i        = 0;

      var configurator_artnr_check = $('#configurator_artnr_check').prop('checked') ? 'y' : 'n';

      $('.configurator_box').each(function(idx, obj) {
         var merkmal_id = parseInt($(this).attr('data-merkmal'));
         var dropdown = $('input[type=radio]:checked', $(obj)).val();
         var arr2 = [];
         var ii = 0;

         if (merkmal_id > 0) {
            $(obj).find('.configurator_zeile').each(function(idx2, elem) {
               var active            = $('.conf_check', elem).prop('checked') ? 'y' : 'n';
               var wert_id           = $('.conf_wert', elem).val();
               var netto             = $('.wert_netto_real', elem).val();
               var wert_art_nr       = $('.wert_art_nr', elem).val();

               arr2[ii]    = [wert_id, active, netto, configurator_artnr_check, wert_art_nr];
               ii++;
            });

            arr[i] = [merkmal_id, arr2, dropdown];
            i++;
         }
      });

      // Konfigurator Texte
      $('select', $('.configurator_text_block')).each(function(idx, obj) {
         if (parseInt($(this).val()) > 0) {
            text_arr[idx] = $(this).val();
         }
      });

      if (arr.length || text_arr.length) {
         $('#configurator_val').val(JSON.stringify({vals:arr, texts:text_arr}));
      }

      else {
         $('#configurator_val').val('');
      }
   },

   // Brutto / Netto berechnen Merkmale
   wertBerechnen: function(mode, el) {
      var parent = $(el).closest('.configurator_zeile');
      var steuer = parseFloat($('#steuer').val());
      var preis = 0.0;

      // Eingabe netto
      if (mode === 'netto') {
         var netto  = parseFloat(komma2point($('.wert_netto', $(parent)).val()));
         var brutto = netto * (1 + steuer / 100);

         $('.wert_netto', $(parent)).val(point2komma(netto.toFixed(2)));
         $('.wert_netto_real', $(parent)).val(netto.toFixed(9));
         $('.wert_brutto', $(parent)).val(point2komma(brutto.toFixed(2)));
      }

      // Eingabe brutto
      if (mode === 'brutto') {
         var brutto = parseFloat(komma2point($('.wert_brutto', $(parent)).val()));
         var netto  = brutto / (1 + steuer / 100);

         $('.wert_brutto', $(parent)).val(point2komma(brutto.toFixed(2)));
         $('.wert_netto', $(parent)).val(point2komma(netto.toFixed(2)));
         $('.wert_netto_real', $(parent)).val(netto.toFixed(9));
      }

      Megakonfigurator.werteCheck();
   },

   DELlistChange: function(id) {
      Megakonfigurator.listChanged[id] = 1;
   },

   // Modul configurator Merkmale speichern
   texteSave:  function() {
      $('.text_block', $('#configurator_texte')).each( function() {
         if (parseInt($(this).attr('data-changed')) !== 1) {
            return true;
         }

         var id     = $(this).attr('data-text_id');
         var lang   = langs.split(';');
         var params = {};
         params['text_id'] = id;

         // Alle aktivierten Sprachen
         for (var j = 0; j < lang.length; j++) {
            params['text_'+lang[j]] = $('.lang_'+lang[j], $(this)).val();
         }

         $.post(admin_url_idx + '/ajax/artikel/configuratorSaveText',
            params,
            function(data) {
               if (data.status === 'ok') {
                  var text_deu = data.text_back;
                  var text_id  = parseInt(data.text_id);

                  // Options in Select ersetzen/hinzufügen
                  $('select', $('#configurator_text_blocks')).each(function() {
                     var found = false;

                     $('option', $(this)).each(function() {
                        if (parseInt($(this).attr('value')) === text_id) {
                           $(this).text(text_deu);
                           found = true;

                           return false;
                        }
                     });

                     if (!found) {
                        $(this).append('<option value="'+text_id+'">'+text_deu+'</option>');
                     }
                  });
               }
            }, 'json'
         );
      });

      Multibox.close();
   },

   // Popup Text-Eingabe anzeigen
   // 18.07.2019
   textePopup: function() {
      $.post(admin_url_idx + '/ajax/artikel/configuratorTextePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      },'json' );
   },

   // Text zum Konfigurator hinzufürgen
   // 18.07.2019
   textAdd: function() {
      $('#conf_new_text').clone().appendTo('#configurator_text_blocks').show().removeAttr('id');
   },

   // Text vom Konfigurator löschen
   // 18.07.2019
   textDelete: function(el) {
      $(el).parent().remove();
      Megakonfigurator.werteCheck();
   },

   // linke und rechte configurator_box gleiche Höhe
   sameHeight: function() {
      var left = 0;
      var max  = 0;

      // Alle Boxen Höhe auf auto
      $('.configurator_box_content').each(function() {
         $(this).css('height', 'auto');
      });

      $('.configurator_box_content').each(function() {
         // Linke Box Höhe merken
         if (left === 0) {
            left = $(this);;
         }

         // Rechte Box
         else {
            max = Math.max($(left).height(), $(this).height());
            $(this).css('height', max + 5);
            $(left).css('height', max + 5);

            left = 0;
            max  = 0;
         }
      });
   },

   dummy: function() {}
//   <a class="ajax" data-fancybox-type="ajax" href="<?php echo ADMIN_URL_IDX; ?>/ajax/artikel/configuratorMerkmale"
};


var Artikelmixer = {
   popup: function() {
      $.post(admin_url_idx + '/artikel/mixerPopup', {
         parent_id : $('#parent_id').val()
      },
      function(data) {
         Multibox.content(data.html);
         Multibox.width(site_width + 14);
         Multibox.bg_close  = true;
         Multibox.close_btn = true;
         Multibox.show();
      },'json' );
   },

   add: function(art_id) {
      $.post(admin_url_idx + '/artikel/mixerAdd', {
         parent_id  : $('#parent_id').val(),
         article_id : art_id
      },
      function(data) {
         Multibox.close();
         if (data.status === 'ok') {
            $('#mixer_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alert(data.msg);
         }
         else {
            alert('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   delete: function(el, db_id) {
      $.post(admin_url_idx + '/artikel/mixerDelete ', {
         parent_id : $('#parent_id').val(),
         db_id : db_id
      },
      function(data) {
         if (data.status === 'ok') {
            $('#mixer_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alert(data.msg);
         }

         else {
            alert('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   save: function() {
      var sort = [];
      var db_id = [];
      var params = {};
      var i = 0;

      $('.mixer_line', $('#mixer_list')).each(function(){
         sort[i] = i;
         db_id[i] = $(this).find('.mdb_id').val();
         i++;
      });

      params.parent_id              = $('#parent_id').val();
      params.sort                   = sort;
      params.db_id                  = db_id;
      params.mixer_artikel_check    = ($('#mixer_artikel_check').prop('checked') ? 'on' : 'off');
      params.mixer_gewicht_check    = ($('#mixer_gewicht_check').prop('checked') ? 'on' : 'off');
      params.mixer_gewicht          = $('#mixer_gewicht').val();
      params.mixer_naehrwerte_check = ($('#mixer_naehrwerte_check').prop('checked') ? 'on' : 'off');

      $('#mixer_list').html('');

      $.post(admin_url_idx + '/artikel/mixerSave',
         params,

         function(data) {
            if (data.status === 'ok') {
               $('#mixer_list').html(data.html);
               showFeedback($('#mixer').closest('section'));
            }

            else {
               alert('Fehler bei der Verarbeitung');
            }

            $('#mixer_artikel').height('auto');
         },'json'
      );
   }
};

$(function() {
   if ($('#mixer').length) {
      $( "#mixer_list" ).sortable();
   }
});

// ************* Modul Musikplayer ***************************************
var Musikplayer = {
   audio_vol : 0,
   audio_el  : null,
//   audio_pos : '',

   init: function() {
      $("#playlist").sortable({
         items: ".audio_line",
         handle: ".audio_line_handle",
         cursor: "move",

         start: function (e, ui) {
            $(ui.item).find("textarea").each(function () {
               // Editor beenden, sonst wird beim Verscieben Inhalt gelöscht
               tinymce.execCommand("mceRemoveEditor", false, $(this).attr("id"));
            });
         },

         stop: function (e, ui) {
            $(ui.item).find("textarea").each(function () {
               // Editor wieder starten
               initPlaylist($(this));
            });

            $(".playlist").each(function() {
               var sort = 0;

               $(".audio_line").each(function() {
                  $(".audio_sort", $(this)).val(sort);
                  sort++;
               });
            });
         }
      });

      $("#playlist").disableSelection();
   },

   play: function(el) {
      var line     = $(el).closest('.audio_line');
      var pos      = $('.audio_pos', line).val();
      var audio_id = $('.audio_id', line).val();
      var player   = $('#player');
      var source   = $('#player_src');
      var filename = $('.audio_filename', line).val();

      if ($(el).hasClass('active')) {
         player[0].pause();
         $(el).removeClass('active');
         return;
      }

      $(player)[0].pause();
      $('.audio_play', $('.audio_line')).removeClass('active');
      $(el).addClass('active');

      source.attr('src', filename);
      player[0].load();
      player[0].oncanplaythrough = player[0].play();
   },

   add: function(pos) {
      var el = $('#audio_add > div').clone();
      $('#audio_'+pos+' .playlist').append(el);
      initPlaylist(el.find('textarea'));
      $('#tabs_extra_player').removeClass('active').addClass('active');
   },

   save: function() {
      var audio_data = [];
      var i = 0;

      tinymce.triggerSave();

      for (edId in tinymce.editors) {
         if (tinymce.editors[edId].id.substr(0,4) !== 'mce_') {
            continue;
         }
         tinymce.editors[edId].remove();
      }

      $('#audio_left .audio_line').each(function() {
         audio_data[i] = [];
         audio_data[i][0] = $(this).find('.audio_id').val();
         audio_data[i][1] = $(this).find('textarea').val();
         audio_data[i][2] = 'left';
         audio_data[i][3] = $(this).find('.audio_sort').val();
         i++;
      });

      $('#audio_right .audio_line').each(function() {
         audio_data[i] = [];
         audio_data[i][0] = $(this).find('.audio_id').val();
         audio_data[i][1] = $(this).find('textarea').val();
         audio_data[i][2] = 'right';
         audio_data[i][3] = $(this).find('.audio_sort').val();
         i++;
      });

      $.post(admin_url_idx + '/ajax/artikel/musikplayerSave', {
         parent_id  : $('#parent_id').val(),
         audio_data : audio_data,
         title_left : $('#audio_title_left .audio_title').val(),
         title_right: $('#audio_title_right .audio_title').val()
      }, function(data) {
         if (data.status === 'ok') {
            $('#musikplayer').html('');
            $('#musikplayer').html(data.html);
            showFeedback($('#musikplayere').closest('section'));
            $('.playlist .audio_line textarea').each(function() {
               initPlaylist($(this));
               Musikplayer.init($(this));
            });
         }
      }, 'json' );
   },

   delete: function(el) {
      var line     = $(el).closest('.audio_line');
      var pos      = $('.audio_pos', $(line)).val();
      var audio_id = $('.audio_id', $(line)).val();

      $.post(admin_url_idx + '/ajax/artikel/musikplayerDelete', {
         audio_id: audio_id
      }, function(data) {
         if (data.status === 'ok') {
            $(el).parents('.audio_line').remove();

            if (!$('.audio_line', $('#playlist')).length) {
               $('#tabs_extra_player').removeClass('active');
            }
         }
      }, 'json' );
   },

   upload: function(el) {
      var parent_id         = parseInt($('#parent_id').val());
      var typ               = 'musikplayer';
      var target_url        = admin_url_idx+'/ajax/artikel/imageUpload';
      var file_types        = ['mp3', 'ogg', 'wav', 'wmf'];

      var line              = $(el).closest('.audio_line');
      Musikplayer.audio_el  = line;
      var audio_id          = $('.audio_id', line).val();
      var audio_sort        = $('.audio_sort', line).val();
      var audio_pos         = ($(line).closest('#audio_left').length ? 'left' : 'right');

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+parent_id+'\', \''+audio_id+'\', \''+audio_sort+'\', \''+audio_pos+'\', false, \
         true, false, \'callbackMusikplayer\');" />');
      $('#file_upload').click();
   },

   callbackMusikplayer: function(id, link) {
//      var pos = Musikplayer.audio_pos;

      $('.audio_id', Musikplayer.audio_el).val(id);
      $('.audio_filename', Musikplayer.audio_el).val(link);
      $('.audio_play', Musikplayer.audio_el).removeClass('noplay');
   },

   volume: function(load) {
      var volume = parseFloat($('#player').prop('volume'));

      if (load === 0) {
         if ($('#volume').hasClass('nomute')) {
            Musikplayer.audio_vol = volume;
            $('#volume').removeClass('nomute').addClass('mute');
            $('#player').prop('volume', 0);
         }

         else {
            $('#volume').removeClass('mute').addClass('nomute');
            $('#player').prop('volume', (Musikplayer.audio_vol > 0 ? Musikplayer.audio_vol : 0.5));
         }
      }

      if (load === 1) {
         volume += 0.1;
         volume = (volume <= 1 ? volume : 1);
         $('#player').prop('volume', volume);
         $('#volume').removeClass('mute').addClass('nomute');
      }

      if (load === 2) {
         volume -= 0.1;
         volume = (volume > 0 ? volume : 0);

         if (volume === 0) {
            $('#volume').removeClass('nomute').addClass('mute');
         }

         $('#player').prop('volume', volume);
      }
   }
};

window.onload = function() {
   if ($('#musikplayer').length) {
      Musikplayer.init();
   }
};


var Zubehoer = {
   // Popup zue Artikelauswahl anzeigen
   // 15.07.2019
   popup: function() {
      $.post(admin_url_idx + '/ajax/artikel/zubehoerPopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(site_width + 14);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      },'json' );
   },

   // In Artikelliste ausgewählten Artikel hinzufügen (article_listr_sub.tpl.php)
   // 15.07.2019
   add: function(art_id) {
      $.post(admin_url_idx + '/artikel/zubehoerAdd', {
         art_id     : $('#parent_id').val(),
         zubehoer_id: art_id
      },
      function(data) {
         Multibox.close();

         if (data.status === 'ok') {
            $('#zubehoer_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alert(data.msg);
         }

         else {
            alertbox('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   // Einzelnen Zubehör-Artikel entfernen
   delete : function(el, db_id) {
      $.post(admin_url_idx + '/ajax/artikel/zubehoerDelete ', {
         parent_id : $('#parent_id').val(),
         db_id     : db_id
      },
      function(data) {
         if (data.status === 'ok') {
//            $(el).closest('.zubehoer_wrapper').remove();
            $('#zubehoer_list').html(data.html);
//            $('#zubehoer_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alertbox(data.msg);
         }
         else {
            alertbox('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   // Liste sortieren ???
   save: function() {
      var sort   = [];
      var db_id  = [];
      var params = {};
      var i      = 0;
      var letzte =

      $('#zubehoer').height($('#zubehoer').height());

      $('.zubehoer_line', $('#zubehoer_list')).each(function(){
         sort[i]  = i;
         db_id[i] = $(this).find('.zdb_id').val();
         i++;
      });

      params.parent_id = $('#parent_id').val();
      params.sort      = sort;
      params.db_id     = db_id;
      params.letzte    = ($('#letzte').prop('checked') ? 'on' : 'off');
      params.ztitle    = $('#ztitle').val();

//      $('#zubehoer_list').html('');

      $.post(admin_url_idx + '/ajax/artikel/zubehoerSave',
         params,

      function(data) {
         if (data.status === 'ok') {
            $('#zubehoer_list').html(data.html);
            showFeedback($('#zubehoer').closest('section'));
//            var color = $('#zubehoer').closest('section').css('background-color');
//            $('#zubehoer').closest('section').animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 100, function() { $(this).animate({ 'background-color' : color }, 250); });
         }

         else if (data.status === 'vorhanden') {
            alertbox('Artikel bereits vorhanden', '', 3);
         }

         else {
            alertbox('Fehler bei der Verarbeitung');
         }

         $('#zubehoer').height('auto');

      },'json' );
   }
};

$(function() {
   if ($('#zubehoer').length) {
      $( "#zubehoer_list" ).sortable();
   }
});


var Aehnliche = {
   popup: function() {
      $.post(admin_url_idx + '/ajax/artikel/aehnlichePopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(site_width + 14);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
//         $('#artikel_box').html(data.html);
//         $('#artikel_box_wrapper').css('opacity', 0).css('display', 'block').animate({opacity:0.6});
//         $('#artikel_box').css('opacity', 0).css('display', 'block').animate({opacity:1});
      },'json' );
   },

   add: function(article_id) {
      $.post(admin_url_idx + '/artikel/aehnlicheAdd', {
         art_id       : $('#parent_id').val(),
         aehnliche_id : article_id
      },
      function(data) {
         Multibox.close();

         if (data.status === 'ok') {
            $('#aehnliche_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alert(data.msg);
         }
         else {
            alert('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   delete : function(db_id) {
      $.post(admin_url_idx + '/artikel/aehnlicheDelete ', {
         parent_id : $('#parent_id').val(),
         db_id : db_id
      },
      function(data) {
         if (data.status === 'ok') {
            $('#aehnliche_list').html(data.html);
         }

         else if (data.status === 'failed') {
            alert(data.msg);
         }
         else {
            alert('Fehler bei der Verarbeitung');
         }
      },'json' );
   },

   save: function() {
      var sort = [];
      var db_id = [];
      var params = {};
      var i = 0;
      $('#aehnliche').height($('#aehnliche').height());

      $('.zubehoer_line', $('#aehnliche_list')).each(function(){
         sort[i] = i;
         db_id[i] = $(this).find('.zdb_id').val();
         i++;
      });

      params.parent_id = $('#parent_id').val();
      params.sort   = sort;
      params.db_id  = db_id;
      params.aetitle = $('#aetitle').val();

      var lang = langs.split(';');

      for (var i = 0; i < lang.length; i++) {
         params['aetitle_'+lang[i]] = $('#aetitle_'+lang[i]).val();
      }

      $('#aehnliche_list').html('');

      $.post(admin_url_idx + '/artikel/aehnlicheSave',
         params,

      function(data) {
         if (data.status === 'ok') {
            $('#aehnliche_list').html(data.html);
            showFeedback($('#aehnliche').closest('section'));
//            var color = $('#aehnliche').closest('section').css('background-color');
//            $('#aehnliche').closest('section').animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 100, function() { $(this).animate({ 'background-color' : color }, 250); });
         }

         else if (data.status === 'vorhanden') {
            alertbox('Artikel bereits verwendet');
         }
         else {
            alert('Fehler bei der Verarbeitung');
         }

         $('#aehnliche').height('auto');

      },'json' );
   }
};

$(function() {
   if($('#aehnliche').length) {
      $('#aehnliche_list').sortable(); }
   }
);

// Crosspromotion
var Zubehoerslider = {
   save: function() {
      var params = [];
      var i      = 0;

      $('.img_box', $('#zubehoerslider')).each(function() {
         var intern  = ($('input[name="as_intern"]', this).is(':checked') ? 'on' : 'off');
         var link    = $('input[name="as_link"]', this).val();
         var tooltip = $('input[name="as_tooltip"]', this).val();
         params[i]   = [intern, link, tooltip];
         i++;
      });

      $.post(admin_url_idx+'/ajax/artikel/zubehoersliderSave', {
         parent_id : $('#as_parent').val(),
         title    : $('#slider_text').val(),
         active   : ($('#slider_active_check').prop('checked') ? 'on' : 'off'),
         params   : params
      },
      function(data) {
         if (data.status === 'ok') {
            $('#slider_wrapper').html(data.html);
            showFeedback($('#zubehoerslider').closest('section'));
//            var color = $('#zubehoerslider').closest('section').css('background-color');
//            $('#zubehoerslider').closest('section').animate( { 'background-color' : 'rgb(100, 240, 100, 0.3)' }, 100, function() { $(this).animate({ 'background-color' : color }, 250); });
         }
      }, 'json');
   },

   upload: function(el) {
      var parent_id     = parseInt($('#parent_id').val());
      var typ           = 'zubehoerslider';
      var pic_nr        = $('.pic_nr', $(el).closest('.img_box')).val();
      var upload_target = 'slider_img_'+pic_nr;
      var target_url    = admin_url_idx+'/ajax/artikel/imageUpload';
      var file_types    = ['jpg', 'png'];

      if (parent_id === 0) {
         alertbox(Artikel.neuMsg);
         return;
      }

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', \''+parent_id+'\', \''+pic_nr+'\', false, false, false, \
         true, \''+upload_target+'\');" />');
      $('#file_upload').click();

      return false;
   },

   delete: function(elem) {
      $.post(admin_url_idx+'/ajax/artikel/zubehoersliderDelete', {
         parent_id : $('#as_parent').val(),
         pic_nr    : $('input[name="pic_nr"]', elem).val(),
         lang      : $('#as_lang').val()
      },
      function(data) {
         if (data.status === 'ok') {
            $('img', $(elem)).attr('src', data.new_image);
         }
      }, 'json');
   }
};

var Google = {
   change: function(level) {
      var z    = 0;
      var cats = '';

      $('.googleselect').each ( function () {
         if (z <= level) {
            if (z === 0) {
               cats += $($(this)).val();
            }

            else {
               cats += ';' + $($(this)).val();
            }
         }
         z++;
      });

      $.post(admin_url_idx+'/ajax/artikel/googlecats', {
         cats : cats
      }, function(data) {
         if (data.status === 'ok') {
           $('#googlecats').html(data.googlecats);
        }

         else {
            alertbox("Fehler");
         }
      }, 'json' );
   }
};

// Fehlermeldungen aktualisieren aus shop77
var Ebay = {
   ebayChange: function(level) {
//      $.fancybox.showLoading();
      var z = 0;
      var cats = '';

      $('.ebay_sel_div select').each ( function () {
         if (z <= level) {
            if (z === 0) {
               cats += $($(this)).val();
            }
            else {
               cats += ';' + $($(this)).val();
            }
         }
         z++;
      });

      $.post(admin_url_idx+'/ajax/artikel/ebaycats',
         {
            e_cats    : cats,
            parent_id : $('#parent_id').val()
         },
         function(data) {
            if (data.status === 'ok') {
               $('#ebay_cats').html(data.ebaycats);
               $('#ebay_options_div').html(data.ebay_options);
            }

            else {
               alertbox("Fehler");
            }

//            $.fancybox.hideLoading();
         },
         'json'
      );
   },

   ebayOptionChanged: function(el, mode) {
      if (mode === 0) {

      }
      else {
         $(el).closest('.ebay_option_line').find('input').val($('option:selected', el).text());
      }

      var options = '';
      $('.ebay_option_line', $('#ebay_options_div')).each(function() {
         options += (options !== '' ? '###' : '')+$('input', this).data('name')+'##'+$('input', this).attr('name')+'##'+$('input', this).val();
      });

      $('#ebay_options').val(options);
   },

   // Artikel-Details: Ebay (Optionen) speichern
   // 30.10.2019
   articleEbaySave: function(parent) {
      $.post(admin_url_idx + '/ajax/artikel/ebaysave', {
         e_id : parent,
         e_cats : $('#e_cats').val(),
         e_auktion :       $('input[name=e_auktion]:checked').val(),
         e_festpreis :     $('#e_festpreis').val(),
         e_varianten :     ($('#e_varianten').is(':checked') ? 'on' : 'off'),
         e_startpreis :    $('#e_startpreis').val(),
         e_vorschlag :     ($('#e_vorschlag').is(':checked') ? 'on' : 'off'),
         e_vorschlag_min : $('#e_vorschlag_min').val(),
         e_vorschlag_ok :  $('#e_vorschlag_ok').val(),
         e_dauer :         $('input[name=e_dauer]:checked').val(),
         e_dauer_tage :    $('#e_dauer_tage').val(),
         e_neu :           $('#e_neu option:selected').val(),
         e_menge :         $('#e_menge').val(),
         e_options :       $('#ebay_options').val()
      }, function(data) {
         if (data.status === '1' || data.status === 1) {
            $('#ebay').html(data.data);
//            alertbox('Ebay-Daten wurden gespeichert', '', 3);
            showFeedback($('#ebay').closest('section'));
         }
      }, 'json');
   },

   ebayAdd: function(id) {
      alertbox('Bitte etwas Geduld ...');

      $.post(admin_url_idx + '/ajax/artikel/ebayAdd', {
            e_id: id
         }, function(data) {
            if (data.status === 'ok') {
               alertbox(data.data);
            }

            else if (data.status === 'login') {
               alertbox(data.login);
            }

            else if (data.status === 'failed') {
               alertbox(data.fail);
            }

            else {
               alertbox(data);
            }
         }, 'json'
      ).fail(function(jqXHR) {alertbox(jqXHR.responseText);});
   },

   // Tools / Ebay-Reset
   toolsReset: function() {
//      $.fancybox.showLoading();

      $.post(admin_url_idx + '/ajax/artikel/resetEbay',
         function(data) {
//            $.fancybox.hideLoading();

            if (data.status === 'ok') {
               alertbox('Token wurde erneuert', '', 3);
            }

            else if (data.status === 'login') {
               alertbox(data.login);
            }

            else {
               alertbox('<span style="color:#cc0000;">Token konnte nicht erneuert werden!</span>');
            }
         }, 'json'
      );
   },

   // Tools / Ebay Kategorien neu laden
   toolsLoad: function() {
      if ($('#ebay_login').html() === 'Modul laden') {
         alertbox('Kategorien werden von Ebay gelesen und gespeichert.<br />Dies kann einige Minuten Dauern.<br />Bitte warten ...');
      }

      $.post(admin_url_idx + '/ajax/artikel/loadEbay',
         function(data) {
            if (data.status === 'ok') {
//               Multibox.close();
               alertbox('Kategorien OK.</div><br/><div>'+data.data);
//               $('#ebay_api').show();
            }

            else if (data.status === 'login') {
               alertbox(data.login);
               $('#ebay_login').html('Modul laden');
            }

            else if (data.status === 'failed') {
               alertbox(data.error);
            }

            else if (data.status === 'error') {
               alertbox(data.data);
            }

            else {
               alertbox('Kategorien OK.</div>');
            }
         }, 'json').fail(function(jqXHR) {
               alertbox(jqXHR.responseText);
         }
      );
   },

   // Tools / Ebay Business-Templates laden
   toolsShopOptions: function() {
      $.post(admin_url_idx + '/ajax/artikel/ebayShopOptions',
         {},

         function(data) {
            if (data.status === 'ok') {
               $('#ebay_options').html(data.html);
               showFeedback($('#ebay_options'));
            }
         }, 'json'
      );
   },

   // Tools / Ebay Business-Templates speichern
   toolsShopOptionsSave: function() {
      $.post(admin_url_idx + '/ajax/artikel/ebayShopOptionsSave',
         {
            ebay_payment : $('#ebay_payment').val(),
            ebay_return : $('#ebay_return').val(),
            ebay_shipping : $('#ebay_shipping').val()
         },

         function(data) {
            if (data.status === 'ok') {
            }

         }, 'json'
      );
   }
};


jQuery('.selectfilebutton').click(function (e) {
    jQuery('#videoupload').trigger('click');
});

jQuery("#videoupload").change(function (e) {
   //  jQuery("#videouploadform").submit();
});

var Kategorie = {
   pass_el    : null,
   cat_to_del : null,

   // Listen-Seite: zeige / schließe Unterkategorien
   // 10.05.2019
   openclose: function (elem, id) {
      classes = elem.className;
      if (classes.search('minus') !== -1) {
         classes = classes.replace('minus', 'plus');
         document.getElementById(id).style.display = 'none';
      }
      else if (classes.search('plus') !== -1) {
         classes = classes.replace('plus', 'minus');
         document.getElementById(id).style.display = 'block';
      }
      elem.className = classes;
   },

   // Kategorie aktiv / Inaktiv umschalten
   // 10.05.2019
   changeActive: function(el, cat_id) {
      $.post(admin_url_idx+'/ajax/kategorien/changeActive', {
         cat_id : cat_id
      },
      function(data) {
         if (data.status === 'ok') {
            if (data.changed === 'y') {
               $(el).removeClass('fa-times').addClass('fa-check');
            }

            else {
               $(el).removeClass('fa-check').addClass('fa-times');
            }
         }
      }, 'json' );
   },

   delete: function(el,id) {
      $.post(admin_url_idx + '/ajax/kategorien/deleteCat', {
         cat_id : id
      },
      function(data) {
         if (data.status === 'check') {
            Kategorie.deleteDo(el, id, data.msg);
         }

         if (data.status === 'ok' && data.html === 'reload') {
            location.href = admin_url_idx + '/kategorien';
         }
      }, 'json' );

   },

   // Kategorie löschen
   // 10.05.2019
   deleteDo: function(el, id, msg){
      if (el !== 0) {
         this.cat_to_del = el;
      }

      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Kategorie Löschen?';
         Confirmbox.html = msg;
         Confirmbox.yes_function = 'Kategorie.deleteDo(0, '+id+')';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/kategorien/deleteCat', {
         cat_id : id,
         delete : 'delete'
      },
      function(data) {
         if (data.status === 'ok') {
            // Kategorie war leer
            if (data.html === 'empty') {
               $(Kategorie.cat_to_del).closest('.catline').remove();
            }

            // Kategorein enthielt Unterkategorien oder Artikel
            else {
               $.post(admin_url_idx+'/kategorien/reload', {
               },
               function(data) {
                  if (data.status === 'ok') {
                     $('#listcontent').html(data.html);
                  }
               }, 'json');
            }
         }
      }, 'json' );
   },

   // Markenfilter aktiv / Inaktiv umschalten
   // 07.02.2019
   changeMarkenfilter: function(elem) {
//      $(elem).removeClass('markenfilter_on').removeClass('markenfilter_off');
      $(elem).removeClass('ci_color').removeClass('no_ci_color');

      $.post(admin_url_idx+'/ajax/kategorien/changeMarkenfilter', {
         cat_id : $(elem).data('id')
      }, function(data) {
         if (data.status === 'ok') {
            if (data.mode === 'y') {
//               $(elem).addClass('markenfilter_on');
               $(elem).addClass('ci_color');
            }

            else {
//               $(elem).addClass('markenfilter_off');
               $(elem).addClass('no_ci_color');
            }
         }
      }, 'json');
   },

   // Kategorie löschen, auch wenn Artikel vorhanden sind
   // 04.06.2019
   DELdeleteForce: function(id){
      $.post(admin_url_idx + '/ajax/kategorien/deleteCat', {
         cat_id : id,
         force  : 1
      }, function(data) {
         if (data.status === 'ok') {
            location.href = admin_url_idx + '/kategorien';
         }
      }, 'json' );
   },

   // Popup Kategorie-PW anzeigen
   // 10.05.2019
   changePassword: function(el) {
      this.pass_el     = el;
      var pass         = $('input', $(el)).val();
      var content      = '';

      content += '<h2 class="txt_tit">Kategorie schützen</h2>';
      content += '<div id="pass_zeile">';
      content += '   <span class="pass_text">Passwort:</span>';
      content += '   <input type="text" class="txt_inp pass_input" id="cat_newpass" value="'+pass+'">';
      content += '   <span class="pass_icon far fa-trash-alt pointer" onclick="$(\'#cat_newpass\').val(\'\');"></span>';
      content += '</div>';
      content += '<div class="buttonzeile">';
      content += '   <div class="button button_left txt_but" onclick="Multibox.close()">abbrechen</div>';
      content += '   <div class="button_ci button_right txt_but" onclick="Kategorie.savePassword()">speichern</div>';
      content += '</div>';

      Multibox.content(content);
      Multibox.width(350);
      Multibox.close_btn = true;
      Multibox.bg_close = true;
      Multibox.show();
   },

   // Neues Kategorie-PW speichern / löschen
   // 07.02.2019
   savePassword: function() {
      var el     = this.pass_el;
      var el_inp = $('input', $(el));
      var pass   = $('#cat_newpass').val();

      $(el_inp).val(pass);

      $.post(admin_url_idx+'/ajax/kategorien/savePassword', {
         cat_id   : $(el).attr('data-id'),
         cat_pass : pass
      },

      function(data) {
         if (data.status === 'ok') {
            Multibox.close();

            if (pass === '') {
               $(el).removeClass('fa-lock').addClass('fa-unlock');
               $(el).removeClass('ci_color').addClass('no_ci_color');
            }

            else {
               $(el).removeClass('fa-unlock').addClass('fa-lock');
               $(el).removeClass('no_ci_color').addClass('ci_color');
            }
         }

         else {
            Multibox.close();
            alertbox(data.message);
         }
      }, 'json' )
      .fail(function() {
         Multibox.close();
         alertbox('Fehler bei der Übertragung');
      });
   },

   // Altercheck aktiv / inaktiv
   // 10.05.2019
   changeAltercheck: function(elem) {
      $(elem).removeClass('altercheck_on').removeClass('altercheck_off');

      $.post(admin_url_idx+'/ajax/kategorien/altercheck', {
         catid : $(elem).data('id')
      }, function(data) {
         if (data.status === 'on') {
            $(elem).addClass('altercheck_on');
         }

         else {
            $(elem).addClass('altercheck_off');
         }
      }, 'json');
   },

   // File-Upload Kategorie-XML
   // 10.05.2019
   importCatXML: function() {
      var target_url = admin_url_idx+'/ajax/kategorien/importCatXml';
      var file_types = ['xml', 'csv'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\');" />');
      $('#file_upload').click();
   },

   // ***************** Funktionen Kategorien-Details ************************************
   // 30.04.2019
   detailSave: function(cat_id, backtocategories = true) {
      tinymce.triggerSave();
      $.post(admin_url_idx+'/ajax/kategorien/detailSave',
         $('#cat_details_form').serialize(),
      function(data) {
          if (data.status === 'ok') {
              if (backtocategories) {
                  document.location.href = admin_url_idx + '/kategorien';
              } else {
                  document.location.href = admin_url_idx+'/kategorien/details/'+data.cat_id;
              }
         }
      }, 'json');
   },

   // Kategorie-Filter-Popup laden
   // 10.05.2019
   katfilterPopup: function(cat_id) {
      $.post(admin_url_idx + '/ajax/kategorien/katfilterPopup', {
         cat_id : cat_id
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.bg_close = true;
            Multibox.width(900);
            Multibox.show();
         }
      }, 'json');
   },

   // Kategorie-Filter-Popup Selectbox geändert -> Neue Checkbox-Liste laden
   // 10.05.2019
   katfilterWerte: function(el, pos) {
      $('#filter_wert'+pos).html('');

      $.post(admin_url_idx + '/ajax/kategorien/katfilterWerte', {
         merkmal_id   : $(el).val()
      }, function(data) {
         if (data.status === 'ok') {
            $('#filter_wert'+pos).html(data.html);
            $('#filter_wert'+pos).sortable('refresh');

            $(".wert_check", $('#filter_wert'+pos)).each( function() {
               $(this).on("change", function() {
                  if ($(this).prop("checked")) {
                     $(this).parent().find(".sort").addClass("active");
                  }
                  else {
                     $(this).parent().find(".sort").removeClass("active");
                  }
               });
            });

            Multibox.bg_close = true;
            Multibox.width(900);
            Multibox.resize();
         }
      }, 'json');
   },

   // Kategorie-Filter-Popup speichern
   // 10.05.2019
    katfilterSave: function () {
      
      var marken     = ($('#marke_check').prop('checked') ? 'y' : 'n');
      var merkmal1   = $('#filter_sel_merkmal1').val();
      var merkmal2   = $('#filter_sel_merkmal2').val();;
      var werte_set1 = [];
      var werte_set2 = [];

      var i = 0;

      $('.wert_check', $('#filter_wert1')).each(function() {
         if ($(this).prop('checked')) {
            werte_set1[i] = $(this).attr('data-value');
            i++;
         }
      });

      i = 0;

      $('.wert_check', $('#filter_wert2')).each(function() {
         if ($(this).prop('checked')) {
            werte_set2[i] = $(this).attr('data-value');
            i++;
         }
      });

      $.post(admin_url_idx+'/ajax/kategorien/katfilterSave', {
         cat_id        : $('#filter_cat_id').val(),
         filter_active : ($('#filter_active').prop('checked') ? 'on' : 'off'),
         data          : { marken:marken, merkmal1:merkmal1, merkmal2:merkmal2, werte_set1:werte_set1 || '', werte_set2:werte_set2 || '' }
      }, function(data) {
              if (data.status === 'ok') {

                  var cat_id = data.cat_id;
                  var filter_active = data.filter_active;

                  var catselector = jQuery(".merkmalfilter[data-id='" + cat_id + "']");

                  catselector.removeClass("no_ci_color");
                  catselector.removeClass("ci_color");

                  if (filter_active == 'y') {
                      catselector.addClass("ci_color");
                  } else {
                      catselector.addClass("no_ci_color");
                  }

            Multibox.close();
         }
      }, 'json');
   },

   // Mixer-Bild Upload / Upload Kategoriebilder in externem Script
   // 25.04.2020
   uploadImg: function(pic_nr, cat_id, upload_target) {
      if (parseInt(cat_id) === 0) {
         alertbox('Kategorie muss zuvor gespeichert werden.');
         return;
      }

      // Dieselbe Funktion wie auch bei Bild-Upload
      var target_url = admin_url_idx+'/ajax/kategorien/imageUpload';
      var file_types = ['jpg', 'png'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+pic_nr+'\', \''+cat_id+'\', false, false, false, false, \
         true, \''+upload_target+'\');" />');
      $('#file_upload').click();

   },

   bildRefresh: function(cat_id) {
      $("#more_images").fileinput("destroy");
      $('#fileinput').html('');

      $.post(admin_url_idx+'/ajax/kategorien/bildRefresh', {
         cat_id : cat_id
      },
      function(data) {
         if (data.status === 'ok') {
            $('#file_uploader').html(data.html);
         }
      },
      'json');
   },

   bildSeo: function(el, cat_id) {
      var parent = $(el).closest('.kv-preview-thumb');
      var sort   = $('img', $(parent)).attr('data-sort');

      $.post(admin_url_idx + '/ajax/kategorien/bildSeo', {
         cat_id : cat_id,
         sort     : sort
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width('auto');

            Multibox.show();
         }

         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   bildSeoSave: function(el, modul_id) {
      $.post(admin_url_idx + '/ajax/kategorien/bildSeoSave', {
         cat_id     : $('#modul_id').val(),
         sort       : $('#modul_sort').val(),
         seo_link   : $('#livedesigner_seo_link').val(),
         seo_intern : ($('#livedesigner_seo_intern').prop('checked') ? 'on' : 'off'),
         seo_seo    : $('#livedesigner_seo_seo').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }

         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   text_element : null,

   bildColors: function(el, cat_id) {
      var parent = $(el).closest('.kv-preview-thumb');
      var sort = $('img', $(parent)).attr('data-sort');
      Kategorie.text_element = parent;

      $.post(admin_url_idx + '/ajax/kategorien/bildColors', {
         cat_id : cat_id,
         sort   : sort
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width(350);

            Multibox.show();

            Design.initLinkColors('top right');
         }

         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   bildColorsSave: function() {
      var text = $('#bild_text').val();

      $.post(admin_url_idx + '/ajax/kategorien/bildColorsSave', {
         cat_id    : $('#modul_id').val(),
         sort      : $('#modul_sort').val(),
         color     : $('#livedesigner_colors_color').val(),
         color_opc : $('#livedesigner_colors_color').attr('data-opacity'),
         bg        : $('#livedesigner_colors_bg').val(),
         bg_opc    : $('#livedesigner_colors_bg').attr('data-opacity'),
         text      : text
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
            $('.file_preview_color', $(Kategorie.text_element)).html(text)
            (text === '' ? $('.file_preview_color', $(Kategorie.text_element)).hide() : $('.file_preview_color', $(Kategorie.text_element)).show());

         }

         else {
            alertbox(data.msg);
         }
      }, 'json');
   },


   // Mixer-Bild löschen
   // 31.01.2021
   deleteImg: function(pic_nr, cat_id) {
      $.post(admin_url_idx + '/ajax/kategorien/deleteImg', {
         pic_nr   : pic_nr,
         cat_id   : cat_id
      }, function(data) {
         if (data.status === 'ok') {
            $('img', $('#img_'+pic_nr)).prop('src', data.html);
         }
      }, 'json');
   },

   // Popup Link / Keywords
   // 30.04.2019
   linkPopup: function(elem, pic_nr, cat_id) {
      if (parseInt(cat_id) === 0) {
         alertbox('Kategorie muss zuerst gespeichert werden.');
         return;
      }
      var content = '';

      content += '<div id="bild_verlinken">';
      content += '   <h2 class="txt_tit">Bild verlinken</h2>';
      content += '   <input type="hidden" id="searchbox_cat_id" value="'+cat_id+'" />';
      content += '   <input type="hidden" id="searchbox_pic_nr" value="'+pic_nr+'" />';
      content += '   <input type="hidden" id="searchbox_lang" value="" />';
      content += '   <input type="hidden" id="searchbox_elem" value="'+elem+'" />';
      content += '   <div class="searchbox_link"><input type="text" class="txt_inp" id="searchbox_link" name="searchbox_link" value="'+$('#link'+pic_nr).val()+'" placeholder="http://" /></div>';
      content += '   <div class="searchbox_intern txt_bez"><input type="checkbox" class="newdesign" id="searchbox_intern" name="searchbox_intern" '+($('#intern'+pic_nr).val() === 'y' ? ' checked="checked"' : '')+'/><label for="searchbox_intern">intern verlinken</label></div>';

      content += '   <h3 class="fliesstext">Keywords des Bildes (wird als Title & Alt umgesetzt)</h3>';
      content += '   <input type="text" class="txt_inp" id="searchbox_search" value="'+$('#search'+pic_nr).val()+'" />';
      content += '   <div class="buttonzeile">';
      content += '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>';
      content += '      <span class="button_ci button_right txt_btn" onclick="Kategorie.linkSave();">speichern</span>';
      content += '   </div>';
      content += '</div>';

      Multibox.content(content);
      Multibox.width(450);
      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.show();
   },

   // Daten Popup speichern
   // 03.03.2019
   linkSave: function() {
      var cat_id = $('#searchbox_cat_id').val();
      var pic_nr = $('#searchbox_pic_nr').val();
      var lang   = $('#searchbox_lang').val();
      var elem   = $('#searchbox_elem').val();

      $.post(admin_url_idx + '/ajax/kategorien/saveLinks', {
         cat_id : cat_id,
         pic_nr : pic_nr,
         lang   : lang,
         intern : ($('#searchbox_intern').is(':checked') ? 'on' : 'off'),
         link   : $('#searchbox_link').val(),
         search : $('#searchbox_search').val()
      }, function(data) {
         Multibox.close();

         if (data.status === 'ok') {
            $('#intern'+pic_nr).val(data.intern);
            $('#link'+pic_nr).val(data.link);
            $('#search'+pic_nr).val(data.search);
            // alertbox('Daten wurden gespeichert', 3);
         }

         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Popup Netzkategorie laden
   // 10.05.2019
   DELnetworkPopup: function () {
      $.post(admin_url_idx+'/ajax/kategorien/networkPopup', {
         net_id : $('#net_id').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.width(400);
            Multibox.content(data.html);
            Multibox.show();
         }

         else if(data.status === 'failed') {
            alertbox(data.msg);
         }

         else {
            alertbox('unbekannter Fehler aufgetreten');
         }
      }, 'json');
   },

   // Popup Netzkategorie geändert - Popup neu laden
   // 10.05.2019
   networkChanged: function(net_id) {
      $.post(admin_url_idx + '/ajax/kategorien/networkChanged', {
         net_id: net_id
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.width(400);
            Multibox.content(data.html);
            Multibox.show();
         }

         else if(data.status === 'failed') {
            alertbox(data.msg);
         }

         else {
            alertbox('unbekannter Fehler aufgetreten');
         }
      }, 'json');
   },

   // Popup Netzkategorie speichern
   // 10.05.2019
   DELnetworkSave: function() {
      $.post(admin_url_idx + '/ajax/kategorien/networkSave', {
         cat_id   : $('#cat_id').val(),
         net_id : $('#net_id').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }

         else if(data.status === 'failed') {
            alertbox(data.msg);
         }

         else {
            alertbox('unbekannter Fehler aufgetreten');
         }
      }, 'json');
   },

   dummy: function() {}
};


var Seiten = {
   // Popup Editor anzeigen
   // 06.05.2019
   popup: function(seite) {
      // Evtl. vorhandenen Editor entfernen
      if (typeof(tinymce) !== 'undefined') {
         tinymce.remove();
      }

      $.post(admin_url_idx + '/ajax/seiten/popup', {
         seite : seite
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(1200);
//            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
            initEditor1();
            editorStart();
         }
         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Popup Editor speichern
   // 06.05.2019
   savePopup: function(seite) {
      if (typeof(tinymce) !== 'undefined') {
         tinymce.triggerSave();
      }

      $.post(admin_url_idx + '/ajax/seiten/savePopup', {
         seite                : seite,
         text                 : $('#text').val(),

         title_name           : ($('#title_name').length      ? $('#title_name').val() : ''),
         inhaber_check        : ($('#inhaber_check').length   ? ($('#inhaber_check').prop('checked')   ? 'on' : 'off') : ''),
         widerruf_check       : ($('#widerruf_form').length   ? ($('#widerruf_form').prop('checked')   ? 'on' : 'off') : ''),
         check                : ($('#kontakt_rechts1').length ? ($('#kontakt_rechts1').prop('checked') ? 'on' : 'off') : ''),

         ds_gvo_text          : ($('#ds_gvo_text').length   ? $('#ds_gvo_text').val() : ''),
         ds_gvo_check: ($('#ds_gvo_check').length ? ($('#ds_gvo_check').prop('checked') ? 'on' : 'off') : ''),
         telefon_aktiv        : ($('#telefon_aktiv').length ? ($('#telefon_aktiv').prop('checked') ? 'on' : 'off') : ''),

         titletag             : ($('#titeltag').length    ? $('#titeltag').val() : ''),
         description          : ($('#description').length ? $('#description').val() : ''),
         keywords             : ($('#keywords').length    ? $('#keywords').val() : ''),
         schlichtung_check    : ($('#schlichtung_check').length ? ($('#schlichtung_check').prop('checked') ? 'on' : 'off') : '')
      }, function(data) {
         if (data.status === 'ok') {
            if (data.title !== '') {
               $('.site_item.'+seite+' .site_name').html(data.title);
            }

            Multibox.close();
         }

         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Popup Sitemap anzeigen
   // 28.01.2020
   popupSitemap: function(seite) {
      $.post(admin_url_idx + '/ajax/seiten/popupSitemap', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.multibox3 = true;
            }

            else if ($('#multibox').length){
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(500);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json' );
   },

   // Popup Sitemap speichern
   // 28.01.2020
   savePopupSitemap: function() {
      $.post(admin_url_idx + '/ajax/seiten/savePopupSitemap', {
         sitemap_menu     : ($('#sitemap_menu').prop('checked') ? 'on' : 'off'),
         sitemap_agb      : ($('#sitemap_agb').prop('checked') ? 'on' : 'off'),
         sitemap_cat      : ($('#sitemap_cat').prop('checked') ? 'on' : 'off'),
         sitemap_cat_lev1 : ($('#sitemap_cat_lev1').prop('checked') ? 'on' : 'off'),
         sitemap_cat_lev2 : ($('#sitemap_cat_lev2').prop('checked') ? 'on' : 'off'),
         sitemap_articles : ($('#sitemap_articles').prop('checked') ? 'on' : 'off'),
         sitemap_xml      : ($('#sitemap_xml').prop('checked') ? 'on' : 'off'),
         sitemap_title    : ($('#sitemap_title').prop('checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }

         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Klick auf Seite aktiv/deaktiv speichern
   // 06.05.2019
   active: function(el, seite) {
      var active = 'off';

      if ($(el).hasClass('fa-times')) {
         active = 'on';
      }

      $.post(admin_url_idx + '/ajax/seiten/active', {
         seite  : seite,
         active : active
      }, function(data) {
         if (data.status === 'ok') {
            if (active === 'on') {
               $(el).removeClass('fa-times').addClass('fa-check');
            }
            else {
               $(el).removeClass('fa-check').addClass('fa-times');
            }
         }
      }, 'json' );
   },

   // Bild Upload
   // 03.03.2019
   upload: function(pic_nr, uns, upload_target) {
      var target_url = admin_url_idx+'/ajax/seiten/upload';
      var file_types = ['jpg', 'png'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+pic_nr+'\', \''+uns+'\', false, false, false, false, \
         true, \''+upload_target+'\');" />');
      $('#file_upload').click();
   },

   // Bild löschen
   // 03.03.2019
   delete: function(el, filename, data) {
      $.post(admin_url_idx + '/ajax/seiten/deleteImg', {
         filename : filename,
         data     : data
      }, function(data) {
         if (data.status === 'ok') {
            if (filename !== 'danke1' && filename !== 'danke2') {
               $('img', $(el).parent()).attr('src', admin_url+'/img/nopic.png');
            }

            else {
               $('img', $(el).closest('.image')).attr('src', template_url+'/images/system/danke_seite.png');
            }
         }
      }, 'json');
   },

   // Popup Link / Keywords anzeigen
   // 03.03.2019
   linkPopup: function(seite, elem, elem_id) {
      var content = '';

      content += '<div id="bild_verlinken">';
      content += '   <h2 class="txt_tit">Bild verlinken</h2>';
      content += '   <input type="hidden" id="searchbox_seite" value="'+seite+'" />';
      content += '   <input type="hidden" id="searchbox_elem" value="'+elem+'" />';
      content += '   <input type="hidden" id="searchbox_elem_id" value="'+elem_id+'" />';
      content += '   <div class="searchbox_link">';
      content += '   <input type="text" class="txt_inp" id="searchbox_link" name="searchbox_link" value="'+$('#'+elem+'_link'+elem_id).val()+'" placeholder="http://" />';
      content += '   </div>';
      content += '   <div class="searchbox_intern txt_bez">';
      content += '      <input type="checkbox" class="newdesign" id="searchbox_intern" name="searchbox_intern" '+($('#'+elem+'_'+'intern'+elem_id).val() === 'y' ? ' checked="checked"' : '')+' />';
      content += '      <label for="searchbox_intern">intern verlinken</label>';
      content += '   </div>';

      content += '   <h3 class="fliesstext">Keywords des Bildes (wird als Title & Alt umgesetzt)</h3>';
      content += '   <input type="text" class="txt_inp" id="searchbox_seo" value="'+$('#'+elem+'_seo'+elem_id).val()+'" />';
      content += '   <div class="buttonzeile">';
      content += '      <span class="button button_left txt_but" onclick="Multibox.close();">Abbrechen</span>';
      content += '      <span class="button_ci button_right txt_btn" onclick="Seiten.linkSave();">Speichern</span>';
      content += '   </div>';
      content += '</div>';

      // 2. Multibox, wenn nicht Danke-Seite
      if (seite !== 'danke') {
         if ($('#multibox2').length) {
            Multibox.multibox3 = true;
         }

         else {
            Multibox.multibox2 = true;
         }
      }

      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.content(content);
      Multibox.width(450);
      Multibox.show();
   },

   // Daten Popup speichern
   // 03.03.2019
   linkSave: function() {
      var seite   = $('#searchbox_seite').val();
      var elem    = $('#searchbox_elem').val();
      var elem_id = $('#searchbox_elem_id').val();

      $.post(admin_url_idx + '/ajax/seiten/saveLinks', {
         seite   : seite,
         elem_id : elem_id,
         intern  : ($('#searchbox_intern').is(':checked') ? 'on' : 'off'),
         link    : $('#searchbox_link').val(),
         seo     : $('#searchbox_seo').val()
      }, function(data) {
         Multibox.close();

         if (data.status === 'ok') {
            $('#'+elem+'_link'+elem_id).val($('#searchbox_link').val());
            $('#'+elem+'_intern'+elem_id).val(($('#searchbox_intern').prop('checked') ? 'y' : 'n'));
            $('#'+elem+'_seo'+elem_id).val($('#searchbox_seo').val());
            // alertbox('Daten wurden gespeichert', 3);
         }

         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Popup Conversion laden
   loadConversion: function() {
      $.post(admin_url_idx+'/ajax/seiten/loadConversion', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(400);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Popup Conversion speichern
   saveConversion: function() {
      $.post(admin_url_idx+'/ajax/seiten/saveConversion', {
         script   : $('#conversion_text').val(),
         tracking : $('#tracking_text').val()
      }, function(data) {
         if (data.status === 'ok') {
            $('#adminbox_html').html(data.html);
            Multibox.close();
         }
      }, 'json');
   },

   // Popup Shopsiegel anzeigen, Link zu Shopsiegel
   loadShopsiegel: function(titel, link, daten) {
      Multibox.content('<div class="txt_tit" style="margin-bottom:15px;">'+titel+'</div>\n\
                        <div style="text-align:center;">\n\
                           <a href="'+link+'/shopanmeldung/'+daten+'" class="txt_bez ci_color" target="_blank" onclick="Multbox.close();">zur Anmeldung</a>\n\
                        </div> \
                        <div class="buttonzeile"> \
                           <span class="button txt_but" onclick="Multibox.close();">abbrechen\n</span> \
                        </div>');
      Multibox.width(400);
      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.show();
   },

   // Popup TrustedShops anzeigen
   loadTrustedshops: function() {
      Multibox.content('<div class="txt_tit">Trusted Shops ID eingeben</div>\n\
                        <input type="text" class="txt_inp" id="trusted_input" value="" /> \
                        <div class="buttonzeile"> \
                           <span class="button_left button txt_but" onclick="Multibox.close();">abbrechen</span> \
                           <span class="button_right button_ci txt_btn" onclick="Seiten.saveTrustedshops();">speichern</span> \
                        </div>');
      Multibox.width(400);
      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.show();
      $('#trusted_input').val($('#trustedshop').val());
   },

   // Popup TrustedShops speichern
   saveTrustedshops: function() {
      $.post(admin_url_idx + '/ajax/seiten/saveTrustedshops', {
         trustedshop : $('#trusted_input').val()
      }, function(data) {
         if (data.status === 'ok') {
            $('#trustedshop').val(data.code);
            Multibox.close();
         }

         else {
            alertbox('Fehler beim Speichern.', '', 3);
         }
      }, 'json');
   },

   headerPopup: function() {
      $.post(admin_url_idx + '/ajax/seiten/headerPopup', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(580);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }

         else {
            alertbox('Fehler beim Speichern.', '', 3);
         }
      }, 'json');
   },

   cookiePopup: function() {
      $.post(admin_url_idx + '/ajax/seiten/cookiePopup', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(580);
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.show();
         }

         else {
            alertbox('Fehler beim Speichern.', '', 3);
         }
      }, 'json');
   },

   dummy: function() {}
};


var Design = {
   rebuild_interval : 0,

   // Shop aktiv /deaktiv
   // 23.05.2019
   shopOnOff: function() {
      var status = 'on';

      if ($('#shop_on_off .shop_on_off').hasClass('shop_on')) {
         status = 'off';
      }

      $.post(admin_url_idx+'/ajax/designTemplate/shopOnOff', {
         status : status
      },
      function(data) {
         if (data.status === 'ok') {
            if (status === 'on') {
               $('#shop_on_off .shop_on_off').removeClass('shop_off').addClass('shop_on');
            }

            else {
               $('#shop_on_off .shop_on_off').removeClass('shop_on').addClass('shop_off');
            }
         }
      }, 'json');
   },

   // Neues Design Einstellen
   // 16.05.2019
   designChange: function(template) {
      $.post(admin_url_idx+'/ajax/designTemplate/template', {
         template : template
      },
      function(data) {
         if (data.status === 'ok') {
            location.href = admin_url_idx+'/designTemplate';
         }
      }, 'json');
   },

   // Bild speichern
   // 16.05.2019
   uploadImg: function(bild, bild_nr, upload_target, filetypes) {
      var target_url = admin_url_idx+'/ajax/designTemplate/uploadImg';
      var file_types = ['jpg'];

      if (typeof(filetypes) !== 'undefined') {
         file_types = filetypes.split(',');
      }

      if (bild === 'slide' && bild_nr < 9) {
          $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_normal', template_url+'/images/'+bild+bild_nr+'w_'+sel_lang+'.jpg');
          $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_right', template_url+'/images/'+bild+bild_nr+'_'+sel_lang+'.jpg');
          $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_fullscreen', template_url+'/images/'+bild+bild_nr+'l_'+sel_lang+'.jpg');

         if ($('#fullscreen_slide').prop('checked')) {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', template_url+'/images/'+bild+bild_nr+'l_'+sel_lang+'.jpg');
         }

         else if($('#slideshow_r_check').prop('checked')) {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', template_url+'/images/'+bild+bild_nr+'_'+sel_lang+'.jpg');
         }

         else {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', template_url+'/images/'+bild+bild_nr+'w_'+sel_lang+'.jpg');
         }
      }

      else if (bild === 'slide' || bild === 'collage') {
          $('#link'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', template_url+'/images/'+bild+bild_nr+'_'+sel_lang+'.jpg');
      }

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
         \''+bild+'\', \''+bild_nr+'\', false, false, false, false, \
         true, \''+upload_target+'\');" />');

      $('#file_upload').click();
   },

   slideshowCallback: function (bild, bild_nr, img_normal, img_left, img_fullscreen, html) {
      if (bild_nr < 0) {
         $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_normal', img_normal);
         $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_left', img_left);
         $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src_fullscreen', img_fullscreen);

         if ($('#fullscreen_slide').prop('checked')) {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', img_fullscreen);
         }

         else if($('#slideshow_r_check').prop('checked')) {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', img_normal);
         }

         else {
            $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src', img_left);
         }

         $('#slide_img').attr('src', $('#link1'+bild_nr+'_seo').parent('.upload_block_horiz').attr('data-src'));
      }
   },

   // Bild löschen
   // 16.05.2019
   deleteImg: function(bild, bild_nr, upload_target, upload_src) {
      $.post(admin_url_idx + '/ajax/designTemplate/deleteImg', {
         image  : bild,
         pic_nr : bild_nr
      }, function(data) {
         if (data.status === 'ok') {
            if (bild === 'slide') {
               if (bild_nr < 9) {
                  $(upload_src).attr('data-src_normal', $('#slideshow').attr('data-bg_normal'));
                  $(upload_src).attr('data-src_right', $('#slideshow').attr('data-bg_right'));
                  $(upload_src).attr('data-src_fullscreen', $('#slideshow').attr('data-bg_fullscreen'));

                  if ($('#fullscreen_slide').prop('checked')) {
                     $(upload_src).attr('data-src', $('#slideshow').attr('data-bg_fullscreen'));
                  }

                  else if($('#slideshow_r_check').prop('checked')) {
                     $(upload_src).attr('data-src', $('#slideshow').attr('data-bg_right'));
                  }

                  else {
                     $(upload_src).attr('data-src', $('#slideshow').attr('data-bg_normal'));
                  }

                  $('#'+upload_target).attr('src', $(upload_src).attr('data-src'));
               }

               else {
                  $('#'+upload_target).attr('src', admin_url+'/img/slideshow_rechts.jpg');
               }
            }

            else if (bild === 'collage') {
               $('#'+upload_target).attr('src', $('#'+upload_target).attr('data-no_img'));
            }

            else {
               $('#'+upload_target).attr('src', admin_url+'/img/nopic.png');
            }
         }
      }, 'json');
   },

   // Link-Popup anzeigen
   // 16.05.2019
   //linkPopup: function(name, multibox2, multibox3) {
   linkPopup: function(name) {
      var content = '';
      var is_text = $('#'+name+'_text').length;
      content += '<div id="bild_verlinken">';
      content += '   <input type="hidden" id="searchbox_name" value="'+name+'" />';

      if (name !== 'logobanner') {
      // Link
         content += '   <div class="txt_tit">Bild verlinken</div>';
         content += '   <div class="searchbox_link">';
         content += '      <input type="text" class="txt_inp" id="searchbox_link" name="searchbox_link" value="'+$('#'+name+'_link').val()+'" placeholder="http://" />';
         content += '   </div>';

         // Checkbox intern
         content += '   <div class="searchbox_intern txt_bez">';
         content += '      <input type="checkbox" class="newdesign" id="searchbox_intern" name="searchbox_intern" '+($('#'+name+'_'+'intern').val() === 'y' ? ' checked="checked"' : '')+' />';
         content += '      <label for="searchbox_intern">intern verlinken</label>';
         content += '   </div>';
      }

      else {
         content += '   <input type="hidden" id="searchbox_link" name="searchbox_link" value="" />';
         content += '   <input type="hidden" id="searchbox_intern" name="searchbox_intern" value="n" />';
      }

      // Schriftfarbe / Hintergrundfarbe anzeigen
      if (is_text) {
         content += '   <div class="text_color">';
         content += '      <div class="text_color_text">';
         content += '         <div class="text_title">Text</div>';
         content += '         <input type="hidden" class="opacity" id="searchbox_color_text_opc" value="'+$('#'+name+'_color_text_opc').val()+'" />';
         content += '         <input type="text" class="txt_inp minicolors minicolors_input" id="searchbox_color_text" data-opacity="'+$('#'+name+'_color_text_opc').val()+'" value="'+$('#'+name+'_color_text').val()+'" />';
         content += '      </div>';
         content += '      <div class="text_color_input">';
         content += '         <input type="text" class="txt_inp" id="searchbox_text" value="'+$('#'+name+'_text').val()+'" />';
         content += '      </div>';
         content += '      <div class="text_color_bg">';
         content += '         <div class="text_title">Hintergrundfarbe</div>';
         content += '         <input type="hidden" class="opacity" id="searchbox_color_bg_opc" value="'+$('#'+name+'_color_bg_opc').val()+'" />';
         content += '         <input type="text" class="txt_inp minicolors minicolor_input" id="searchbox_color_bg"   data-opacity="'+$('#'+name+'_color_bg_opc').val()+'"   value="'+$('#'+name+'_color_bg').val()+'" />';
         content += '      </div>';
         content += '   </div>';
      }
      // SEO
      content += '   <h2 class="fliesstext">Keywords des Bildes (wird als Title & Alt umgesetzt)</h2>';
      content += '   <input type="text" class="txt_inp" id="searchbox_seo" value="'+$('#'+name+'_seo').val()+'" />';

      if (name !== 'logobanner' && name !== 'banner2') {

      }

      content += '   <div class="buttonzeile">';
      content += '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>';
      content += '      <span class="button_ci button_right txt_btn" onclick="Design.linkSave();">speichern</span>';
      content += '   </div>';
      content += '</div>';

      Multibox.bg_close  = true;
      Multibox.close_btn = true;
      Multibox.content(content);
      Multibox.width(450);

      if ($('#multibox2').length) {
         Multibox.multibox3 = true;
      }

      else if ($('#multibox').length) {
         Multibox.multibox2 = true;
      }

      Multibox.show();

      Design.initLinkColors('bottom right');
   },

   // Link-Popup speichern
   // 16.05.2019
   linkSave: function() {
      var name           = $('#searchbox_name').val();
      var is_text        = $('#'+name+'_text').length;

      var intern         = ($('#searchbox_intern').is(':checked') ? 'y' : 'n');
      var link           = $('#searchbox_link').val();
      var seo            = $('#searchbox_seo').val();

      var text1           = '';
      var color_text     = '';
      var color_text_opc = '';
      var color_bg       = '';
      var color_bg_opc   = '';

      if (is_text) {
         text1           = $('#searchbox_text').val();
         color_text     = $('#searchbox_color_text').val();
         color_text_opc = $('#searchbox_color_text_opc').val();
         color_bg       = $('#searchbox_color_bg').val();
         color_bg_opc   = $('#searchbox_color_bg_opc').val();
      }

      $.post(admin_url_idx + '/ajax/designTemplate/saveLink', {
         name           : name,
         intern         : intern,
         link           : link,
         seo            : seo,
         text           : text1,
         color_text     : color_text,
         color_text_opc : color_text_opc,
         color_bg       : color_bg,
         color_bg_opc   : color_bg_opc
      }, function(data) {
         if (data.status === 'ok') {
            $('#'+name+'_link').val($('#searchbox_link').val());
            $('#'+name+'_intern').val(($('#searchbox_intern').prop('checked') ? 'y' : 'n'));
            $('#'+name+'_seo').val($('#searchbox_seo').val());

            if (is_text) {
               $('#'+name+'_text').val(text1);
               $('#'+name+'_color_text').val(color_text);
               $('#'+name+'_color_text_opc').val(color_text_opc);
               $('#'+name+'_color_bg').val(color_bg);
               $('#'+name+'_color_bg_opc').val(color_bg_opc);
            }

            Multibox.close();
         }

         else {
            alertbox('Fehler beim Speichern', 3);
         }
      }, 'json' );
   },

   // Minicolors starten / Wird von verschiedenen Scripts/Modulen verwendet
   // <input type=hidden class=opacity kann durch data-opacity ersetzt werden. Bei Design-Farben allerdings notwendig, da ganze Seite als Formular gespeichert wird
   // 01.04.2020
   initLinkColors: function(mode) {
      if (mode === 'undefined') {
         mode = 'buttom right';
      }

      $('input.minicolors').each( function() {
         $(this).minicolors({
            control  : $(this).attr('data-control') || 'wheel',
            opacity  : $(this).attr('data-opacity'),
            position : mode,
            change: function(hex, opacity) {
               if (opacity) {
                  // Für Design-Farben
                  $(this).parent().parent().find('.opacity').val(opacity);
               }

               // Merker, dass Farbe geändert wurde / Livedesigner/Einfügen
               if ($('#popup_startseite').length) {
                  $(this).closest('.line').attr('data-color_changed', true);
               }

               // Merker, dass Farbe geändert wurde / Livedesigner/Logobanner
               if ($('#popup_logobanner.length')) {
                  $('.bg_header', $('#popup_logobanner')).css('background-color', hex);
               }
               if ($('#livedesigner_colors_image').length) {
                  var c = hex.substring(1).split('');
                  c = '0x' + c.join('');
                  var rgba = 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+opacity+')';

                  // Testfarbe
                  if ($(this).attr('id') === 'livedesigner_colors_color') {
                     $('#livedesigner_colors_text').css('color', rgba);
                  }

                  // Hintergrundfarbe
                  else {
                     $('#livedesigner_colors_text').css('background-color', rgba);
                  }
               }
            }
         });
      });
   },

   // Popup Einstellungen Menü laden
   // 16.05.2019
   loadMenuPopup: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/loadMenuPopup', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Popup Einstellungen Menü speichern
   // 16.05.2019
   saveMenuPopup: function() {
      var params = $('#design_menu_form').serialize();
      var test = params.split('&');

//      var homebutton_check = ($.inArray('homebutton_check=on', test) !== -1 ? 'y' : 'n');
//      var kontakt_check    = ($.inArray('kontakt_check=on', test) !== -1 ? 'y' : 'n');
      var anmelden_mode    = ($.inArray('anmelden_mode=1', test) !== -1 ? 1 : $.inArray('anmelden_mode=2', test) !== -1 ? 2 : 3);
      var merkliste_mode   = ($.inArray('merkliste_mode=1', test) !== -1 ? 1 : $.inArray('merkliste_mode=2', test) !== -1 ? 2 : 3);
      var warenkorb_mode   = ($.inArray('warenkorb_mode=1', test) !== -1 ? 1 : $.inArray('warenkorb_mode=2', test) !== -1 ? 2 : 3);
      var suchfeld_mode    = ($.inArray('suchfeld_mode=1', test) !== -1 ? 1 : $.inArray('suchfeld_mode=2', test) !== -1 ? 2 : 3);
      var flaggen_mode     = ($.inArray('flaggen_mode=1', test) !== -1 ? 1 : $.inArray('flaggen_mode=2', test) !== -1 ? 2 : 3);
      var icon_farbe       = ($.inArray('icon_farbe=weiss', test) !== -1 ? 1 : 0);

      $.post(admin_url_idx + '/ajax/designTemplate/saveMenuPopup',
      params,
      function(data) {
         if (data.status === 'ok') {
            $('#adminbox_html').html(data.html);

            $('.fe_icons_right').removeClass('dunkel');
            if (icon_farbe === 0) { $('.fe_icons_right').addClass('dunkel'); }

//            $('.homebutton_check').removeClass('fe_icon_home').removeClass('fe_icon_home_inactive');
//            if (homebutton_check === 'y') { $('.homebutton_check').addClass('fe_icon_home'); } else { $('.homebutton_check').addClass('fe_icon_home_inactive'); }

//            $('.kontakt_check').removeClass('fe_icon_kontakt').removeClass('fe_icon_kontakt_inactive');
//            if (kontakt_check === 'y') { $('.kontakt_check').addClass('fe_icon_kontakt'); } else { $('.kontakt_check').addClass('fe_icon_kontakt_inactive'); }

            $('.anmelden_mode').removeClass('fe_icon_anmelden1').removeClass('fe_icon_anmelden2').removeClass('fe_icon_anmelden3');
            $('.anmelden_mode').addClass('fe_icon_anmelden'+anmelden_mode);

            $('.merkliste_mode').removeClass('fe_icon_merkliste1').removeClass('fe_icon_merkliste2').removeClass('fe_icon_merkliste3');
            $('.merkliste_mode').addClass('fe_icon_merkliste'+merkliste_mode);

            $('.warenkorb_mode').removeClass('fe_icon_warenkorb1').removeClass('fe_icon_warenkorb2').removeClass('fe_icon_warenkorb3');
            $('.warenkorb_mode').addClass('fe_icon_warenkorb'+warenkorb_mode);

            $('.suchfeld_mode').removeClass('fe_icon_suchfeld1').removeClass('fe_icon_suchfeld2').removeClass('fe_icon_suchfeld3');
            $('.suchfeld_mode').addClass('fe_icon_suchfeld'+suchfeld_mode);

            $('.flaggen_mode').removeClass('fe_icon_flaggen1').removeClass('fe_icon_flaggen2').removeClass('fe_icon_flaggen3');
            $('.flaggen_mode').addClass('fe_icon_flaggen'+flaggen_mode);

            //pc_box.close();
            Multibox.close();
         }
      }, 'json');
   },

   // Logobanner / bildschirmbreit
   // 09.06.2019
   bildschirmbreit: function(el) {
      // Bildschirmbreit aktiv
      if ($(el).prop('checked')) {
//         $('.menu_box_right', $('#header_menu')).removeClass('bg_banner_width').addClass('bg_banner_width');
         $('#header_menu .menu_box_right').removeClass('menuleiste');
         $('#header_menu').addClass('menuleiste');

         $('#logobanner .banner_box_right').removeClass('bg_header');
         $('#logobanner').addClass('bg_header');

         $('#abstand_oben_breit').hide();
         $('#abstand_oben_schmal').show();
      }

      // Bildschirmbreit deaktiviert
      else {
//         $('.menu_box_right', $('#header_menu')).removeClass('bg_banner_width');
         $('#header_menu .menu_box_right').addClass('menuleiste');
         $('#header_menu').removeClass('menuleiste');

         $('#logobanner .banner_box_right').addClass('bg_header');
         $('#logobanner').removeClass('bg_header');

         $('#abstand_oben_breit').show();
         $('#abstand_oben_schmal').hide();
      }
   },

   checkSlideshow: function() {
      var slideshow_mode = 'normal';   // ohne rechte Bilder
      var right_check    = $('#slideshow_r_check').prop('checked');
      var fullscreen     = $('#fullscreen_slide').prop('checked');

      if (fullscreen) {
         slideshow_mode = 'fullscreen';   // Fullscreen
      }

      else if (right_check) {
         slideshow_mode = 'right';  // mit rechten Bildern
      }

      // Slideshow ohne rechte Bilder
      if (slideshow_mode === 'normal') {
         $('#slideshow_hidden').removeClass('max_width900').addClass('max_width900');
         $('#slideshow_left').removeClass('pos_normal').removeClass('pos_right').removeClass('pos_fullscreen').addClass('pos_normal');
         $('#slideshow_right').removeClass('fullscreen_hide').addClass('fullscreen_hide');
         $('.slideshow_right_check').show();

         $('.slider_line', $('#slideshow_left')).each( function(idx, i) {
            $('.upload_block_horiz', $(this)).attr('data-src', $('.upload_block_horiz', $(this)).attr('data-src_normal'));

            if (idx === 0) {
               $('#slide_img').attr('src', $('.upload_block_horiz', $(this)).attr('data-src'));
               $('#preview_nr').html('1');
            }
         });
      }

      // Slideshow mit rechten Bildern
      else if (slideshow_mode === 'right') {
         // Slideshow mit rechten Bildern
         $('#slideshow_hidden').removeClass('max_width900').addClass('max_width900');
         $('#slideshow_left').removeClass('pos_normal').removeClass('pos_right').removeClass('pos_fullscreen').addClass('pos_right');
         $('#slideshow_right').removeClass('fullscreen_hide');
         $('.slideshow_right_check').show();

         $('.slider_line', $('#slideshow_left')).each( function(idx, i) {
            $('.upload_block_horiz', $(this)).attr('data-src', $('.upload_block_horiz', $(this)).attr('data-src_right'));

            if (idx === 0) {
               $('#slide_img').attr('src', $('.upload_block_horiz', $(this)).attr('data-src'));
               $('#preview_nr').html('1');
            }
         });
      }

      // Slideshow Fullscreen
      else if (slideshow_mode === 'fullscreen') {
         // Slideshow mit rechten Bildern
         $('#slideshow_hidden').removeClass('max_width900');
         $('#slideshow_left').removeClass('pos_normal').removeClass('pos_right').removeClass('pos_fullscreen').addClass('pos_fullscreen');
         $('#slideshow_right').removeClass('fullscreen_hide').addClass('fullscreen_hide');
         $('.slideshow_right_check').hide();

         $('.slider_line', $('#slideshow_left')).each( function(idx, i) {
            $('.upload_block_horiz', $(this)).attr('data-src', $('.upload_block_horiz', $(this)).attr('data-src_fullscreen'));

            if (idx === 0) {
               $('#slide_img').attr('src', $('.upload_block_horiz', $(this)).attr('data-src'));
               $('#preview_nr').html('1');
            }
         });
      }

      if ($('#multibox').length) {
         $('#multibox').css('top', 'unset').css('bottom', 'unset');
         Multibox.resize();
      }

      if ($('#multibox2').length) {
         $('#multibox2').css('top', 'unset').css('bottom', 'unset');
         Multibox.resize();
      }
   },

   rebuildImages: function() {
      alertbox('Bitte warten ...', 'Verarbeitung gestartet');

      clearInterval(Design.rebuild_interval);
      Design.rebuild_interval = setInterval('Design.rebuildStatus()', 1000);

      $.post(admin_url_idx + '/ajax/artikel/rebuildImages', {
      }, function(data) {
         if (data.status === 'cronjob') {
            clearInterval(Design.rebuild_interval);
            Multibox.close(true);
            alertbox(data.msg, '', 3);
         }

         else {
         }
      }, 'json'
      );
   },

   rebuildStatus: function() {
      $.post(admin_url_idx + '/ajax/artikel/rebuildStatus', {
      }, function(data) {
         if (data.status === 'running' || data.status === 'ok') {
            // Umwandlung läuft noch
            $('#multibox_content').html(data.msg);
         }

         // Umwandlung erfolgreich beendet
         else if (data.status === 'stop')  {
            Multibox.close(true);
            clearInterval(Design.rebuild_interval);
            alertbox(data.msg, 'Erfolgreich');
         }

         else if (data.status === 'failed') {
            Multibox.close(true);
            clearInterval(Design.rebuild_interval);
            alertbox(data.msg, 'Fehler');
         }

         else {
            Multibox.close(true);
            clearInterval(Design.rebuild_interval);
            alertbox('Keine Daten empfangen', 'Fehler');
         }
      }, 'json')
      .fail(function() {
         clearInterval(Design.rebuild_interval);
         Multibox.close(true);
         alertbox('Versuchen Sie es erneut. Die Verarbetung wird fortgesetzt.', 'Verarbeitung vom Server abgebrochen');
      });
   },

    // Popup Footer-Icons speichern
   // 18.05.2019
   footerPopup: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/footerPopup', {
      }, function(data) {
         if (data.status === 'ok') {
            // Bei LiveDesigner
            if ($('#multibox').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(566);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

    // Popup Footer-Icons speichern, auch Livedesigner
   // 18.05.2019
   saveFooter: function() {
      var livedesigner = $('#livedesigner').length;
      var colors       = $('input[name=footer_farbe]:checked').val();
      var params       = $('#footer_icons_form').serialize();
      params          += '&livedesigner='+(livedesigner ? 'on' : 'off');

      $.post(admin_url_idx + '/ajax/designTemplate/saveFooter',
      params,
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();
            if (livedesigner) {
               $('#ld_icons').html(data.html);
               $('.footer_kontakt').removeClass('weiss').removeClass('anthrazit').removeClass('bunt').addClass(colors);
            }
         }
      }, 'json');
   },

   // Popup Headerscript laden
   // 16.05.2019
   loadHeaderscript: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/loadHeaderscript', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(580);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Popup Headerscript speichern
   // 16.05.2019
   saveHeaderscript: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/saveHeaderscript', {
         script1      : $('#script_text1').val(),
         script2      : $('#script_text2').val(),
         script3      : $('#script_text3').val(),
         cookie_check : $('input[name=cookie_check]:checked').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }
      }, 'json');
   },

   // Popup Cookie speichern
   // 21.09.2020
   saveCookiePopup: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/saveCookiePopup', {
         cookie_check       : $('input[name=cookie_check]:checked').val(),

         wesentlich_text    : $('#wesentlich_text').val(),
         social_text        : $('#social_text').val(),

         marketing_title    : $('#marketing_title').val(),
         marketing_text     : $('#marketing_text').val(),
         marketing_script   : $('#marketing_script').val(),

         funktionell_title  : $('#funktionell_title').val(),
         funktionell_text   : $('#funktionell_text').val(),
         funktionell_script : $('#funktionell_script').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }
      }, 'json');
   },

   socialPopup: function(id) {
      $.post(admin_url_idx + '/ajax/designTemplate/socialPopup', {
         id: id
      }, function(data) {
         if (data.status === 'ok') {
            // Im Live'Designer aufgerufen
            if ($('#multibox').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(400);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   saveSocial: function(id) {
      $.post(admin_url_idx + '/ajax/designTemplate/saveSocial', {
         id                : id,
         name              : id,
         footer_check      : $('#footer_check').is(':checked') ? 'on' : '',
         footer_link       : $('#footer_link').val(),
         teilen_check      : $('#teilen_check').length ? ($('#teilen_check').is(':checked') ? 'y' : 'n') : 'd',
         detail1_check     : $('#detail1_check').length ? ($('#detail1_check').is(':checked') ? 'y' : 'n') : 'd',
         detail2_check     : $('#detail2_check').length ? ($('#detail2_check').is(':checked') ? 'y' : 'n') : 'd',
         script_check      : $('#script_check').length ? $('#script_check').val() : 'n',
         detail_script     : $('#detail_script').val(),
         detail_link_check : $('#detail_link_check').length ? ($('#detail_link_check').is(':checked') ? 'on' : 'n') : 'n',
         detail_link       : $('#detail_link').val(),
         social_name       : $('#social_name').length ? $('#social_name').val() : ''
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();

            $('.img_'+id, $('#social_icons')).removeClass('not_active');

            if (data.active === 'n') {
               $('.img_'+id, $('#social_icons')).addClass('not_active');
            }

            if ($('#livedesigner').length) {
               Livedesigner.saveNetzwerk();
            }

            else {
               $('#social_icons').html(data.html);
            }
         }
      }, 'json');
   },

   callCheck: function() {
      $.post(admin_url_idx + '/ajax/designTemplate/callCheck', {
         call_check: ($('#call_check').prop('checked') ? 'on' : 'off')
	  }, function(data) {
            if (data.status === 'ok') {
               showFeedback($('.call_me'));
            }
      }, 'json');
   },

// ******************* Design ******************************************


   // Geschäftspapier Bild upload
   // 14.05.2019
   papierUpload: function(bild, upload_target) {
      var target_url = admin_url_idx+'/ajax/designGeschaeftspapier/fileUpload';
      var file_types = ['jpg'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+bild+'\', false, false, false, false, false, \
         true, \''+upload_target+'\');" />');
      $('#file_upload').click();
   },

   // Geschäftspapier Bild löschen
   // 14.05.2019
   papierDelete: function(image, target) {
      $.post(admin_url_idx+'/ajax/designGeschaeftspapier/papierDelete', {
         image : image
      }, function(data) {
         if(data.status === 'ok') {
            $('#'+target).attr('src', data.html);
         }
      }, 'json');
   },

   // Geschäftspaier speichern
   // 14.05.2019
   papierSave: function() {
      $.post(admin_url_idx+'/ajax/designGeschaeftspapier/papierSave', {
         rechnung : $('#rechnung').val()
      }, function(data) {
         if(data.status === 'ok') {
            alertbox('RE-Nr. und Geschäftspapier wurde gespeichert', '', 3);
         }
      }, 'json');
   },


   // Bild Anzeigen
   // 14.05.2019
   popupImage: function() {
      if ($('#popup_img').attr('src') !== '') {
         Multibox.content('<img src="'+$('#popup_img').attr('src')+'" alt="" />');
         Multibox.width('auto');
         Multibox.bg_close  = true;;
         Multibox.close_btn = true;;
         Multibox.show();
      }
   },

   // Bild Pupup Upload
   // 14.05.2019
   popupUpload: function() {
      var target_url = admin_url_idx+'/ajax/designExtended/popupUpload';
      var file_types = ['jpg', 'png'];
      var target     = 'popup_img';
      var typ        = 'popup';

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\',\
          \''+typ+'\', false, false, false, false, false, \
         true, \''+target+'\');" />');
      $('#file_upload').click();
   },

   // Popup speichern
   // 14.05.2019
   popupSave() {
      $.post(admin_url_idx+'/ajax/designExtended/popupSave', {
         popup_check      : ($('#popup_check').prop('checked') ? 'on' : 'off'),
         mod_popup_link   : $('#mod_popup_link').val(),
         mod_popup_intern : ($('#mod_popup_intern').prop('checked') ? 'on' : 'off'),
         mod_popup_button : ($('#mod_popup_button').prop('checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            alertbox('Extended-Popup wurde gespeichert', '', 3);
         }
      }, 'json');
   },

   // Bild Pupup löschen
   // 14.05.2019
   popupDelete() {
      $.post(admin_url_idx+'/ajax/designExtended/popupDelete', {
      }, function(data) {
         if (data.status === 'ok') {
            $('#popup_img').attr('src', data.html);
         }
      }, 'json');
   },


   // Accordion speichern
   // 14.05.2019
   saveAccordion: function() {
      // Editoren beenden
      tinymce.remove();
      var params = {};

      $('input', $('#pro_accordion')).each(function() {
         var name = $(this).attr('name');

         if ($(this).attr('type') === 'checkbox') {
            params[name] = ($(this).is(':checked') ? 'on' : 'off');
         }

         else if ($(this).attr('type') === 'radio') {
            if ($(this).is(':checked')) {
               params[name] = $(this).val();
            }
         }

         else if ($(this).attr('type') !== 'file') {
            params[name] = $(this).val();
         }
      });

      $('select', $('.accordion_conf')).each(function() {
         var name = $(this).attr('name');
         params[name] = $(this).val();
      });

      $('textarea', $('#accordion_html')).each(function() {
         var name = $(this).attr('name');
         params[name] = $(this).val();
      });

      $.post(admin_url_idx + '/ajax/designExtended/updateAccordion',
         params,
         function(data) {
            if (data.status === 'ok') {
               $('#pro_accordion').html(data.html);
               alertbox('Accordion wurde gespeichert', '', 3);
               extendedInit();
            }
      }, 'json');
   },

   // Karussell speichern
   // 14.05.2019
   saveCarussell: function() {
      var params = {};

      $('input', $('#pro_carussell')).each(function() {
         var name = $(this).attr('name');

         if ($(this).attr('type') === 'checkbox') {
            params[name] = ($(this).is(':checked') ? 'on' : 'off');
         }

         else if ($(this).attr('type') === 'radio') {
            if ($(this).is(':checked')) {
               params[name] = $(this).val();
            }
         }

         else if ($(this).attr('type') !== 'file') {
            params[name] = $(this).val();
         }
      });

      $.post(admin_url_idx+'/ajax/designExtended/updateCarussell',
         params,
         function(data) {
            if (data.status === 'ok') {
               $('#pro_carussell').html(data.html);
               alertbox('Karussell wurde gespeichert', '', 3);
            }
      }, 'json');
   },

   // Artikel-Slider speichern
   // 14.05.2019
   saveSlider: function() {
      var params = {};

      $('input', $('#pro_slider')).each(function() {
         var name = $(this).attr('name');

         if ($(this).attr('type') === 'checkbox') {
            params[name] = ($(this).is(':checked') ? 'on' : 'off');
         }

         else if ($(this).attr('type') === 'radio') {
            if ($(this).is(':checked')) {
               params[name] = $(this).val();
            }
         }

         else if ($(this).attr('type') !== 'file') {
            params[name] = $(this).val();
         }
      });

      $.post(admin_url_idx + '/ajax/designExtended/updateSlider',
         params,
         function(data) {
            if (data.status === 'ok') {
               $('#pro_slider').html(data.html);
               alertbox('Artikel-Slider wurde gespeichert', '', 3);
            }
      }, 'json');
   },

   // HTML5 Bild upload
   // 14.05.2019
   uploudProImg: function(typ, sort, image) {
      var target_url = admin_url_idx+'/ajax/designExtended/upload';
      var file_types = ['jpg', 'png'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \''+target_url+'\',\
                                    \''+file_types+'\',\
                                    \''+typ+'\',\
                                    \''+sort+'\',\
                                    false,\
                                    false,\
                                    false,\
                                    false, \
                                    true,\
                                    \''+image+'\');" />');
      $('#file_upload').click();
   },

   // HTML5 Bild löschen
   // 14.05.2019
   deleteProImg: function(name, sort, target) {
      $.post(admin_url_idx+'/ajax/designExtended/delete', {
         name : name,
         sort : sort
      }, function(data) {
         if (data.status === 'ok') {
            if (name === 'accordion') {
               $('#'+target).attr('src', data.html);
            }

            else {
               $('img.image_bg', $('#'+target)).attr('src', data.html);
            }
//            $('img.image_bg', $('#'+target)).attr('src', data.html);
         }

         else {
            alertbox('Fehler beim Löschen');
         }
      }, 'json');
   },

   dummy: function() {}
};


$(function() {
   if($('#design_extended').length) {
      extendedInit();
   }
});

$(document).on('error', 'img', function() {
   $(this).attr('src', admin_url+'/img/nopic.png');
});



var Tools = {
   mail_id       : 0,
   label_interval: null,
   mail_interval : null,
   foto_interval : null,

   save: function() {
      $('#toolsform').submit();
   },

   // Allgemien Funktion für Export
   // 15.03.2019
   export: function(url, mode, param1, param2, param3) {
      $('#iframe').remove();

      $.post(admin_url_idx+'/ajax/tools/exportParams', {
         export : mode,
         param1 : (param1 !== undefined ? param1 : ''),
         param2 : (param2 !== undefined ? param1 : ''),
         param3 : (param3 !== undefined ? param1 : '')
      },
      function(data) {
         $('body').append('<iframe id="iframe" src="'+admin_url_idx+'/ajax/tools/'+url+'" style="display:none;"></iframe>');
      });
   },

   // Artikel exportieren
   // 15.03.2019
   exportArticle: function(el, mode) {
      Tools.export('exportArtikel', mode);
   },

   // Buchungen Easycash exportieren / param1 -> 1/2-zeilig
   // 05.04.2019
   exportEasycash: function(el, mode, param1) {
      Tools.export('exportEesycash', mode, param1);
   },

   // Buchungen Datev exportieren / param1 -> 1/2-zeilig
   // 05.04.2019
   exportDatev: function(el, mode, param1) {
      Tools.export('exportDatev', mode, param1);
   },

   // Portale / Shops
   exportShops: function(el, mode) {
      Tools.export('exportArtikel', mode);
      $(el).parents('.tools_export').find('.tools_export_file').html('Zum Refreshen der Seite bitte Taste F5 drücken');
      $(el).parents('.tools_export').find('.tools_export_info').html('');

//      window.open(admin_url_idx+'/tools/exportArtikel?export='+mode, '_blank ');
   },

   // Allgemeine Funktion für Uploads
   // 15.03.2019
   upload: function(target_url, file_type, mode, param2, param3, param4, param5, param6, show_image, upload_target) {
      file_types = file_type.split(',');
      $('body').append('<input type="file" id="file_upload" style="opacity:0;" onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\', \''+mode+'\', \''+param2+'\', \''+param3+'\', \''+param4+'\', \''+param5+'\', \''+param6+'\', \''+show_image+'\', \''+upload_target+'\');" />');
      $('#file_upload').click();
   },

   // XML/CSV-Datei hochladen - Artikel-, Lager-, Kunden import
   uploadArticle: function(el, mode, file_type, pic_upload) {
      var overwrite     = ($('#article_overwrite1').prop('checked') ? 'y' : 'n');
      var cat_name      = ($('#cat_name1').prop('checked') ? 'y' : 'n');
      var picload_check = ($('#picload_check').prop('checked') ? 'on' : 'off');
      var haendler_id   = $('#haendler_id').val();

      // Warnhinweis bei Cronjob
      if (pic_upload === 'picload') {
         if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
            Confirmbox.head = "Achtung";
            Confirmbox.center = true;

            if (cronjob) {
               Confirmbox.html = "Der Upload der Bilder erfolgt über Cronjob\nund kann mehrere Stunden dauern!";
            }

            else {
               Confirmbox.html = "Der Upload mit Bildern kann mehrere Stunden dauern!\n Splitten Sie Ihre CSV in je 100 Artikel!";
            }

            Confirmbox.yes_function = 'Tools.uploadArticle(0, "'+mode+'", "'+file_type+'", "nix")';
            Confirmbox.show();
            return;
         }
      }

      Confirmbox.yes_function = '';
      Tools.upload(admin_url_idx+'/tools/importArtikel', file_type, mode, overwrite, cat_name, picload_check, haendler_id, 0, 0, false, '');
   },

   // Alle Bestellungen löschen
   deleteBestellungen: function() {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Alle Bestellungen löschen?';
         Confirmbox.yes_function = 'Tools.deleteBestellungen()';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/tools/deleteBestellungen', {
      }, function(data) {
         if (data.status) {
            if (data.status === 'ok') {
            }
         }
      }, 'json');
   },

   // Alle Artikel löschen (Schnittstellen
   allArticlesDelete: function()  {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Alle Artikel löschen?';
         Confirmbox.yes_function = 'Tools.allArticlesDelete()';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      $.post(admin_url_idx + '/ajax/tools/allArticlesDelete', {
      },
      function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg);
         }
      }, 'json' );
   },

   // Alle Artikel eines Händlers löschen (Portal)
   deleteArticlesHaendler: function(haendler_id)  {
      if (confirm("Sind Sie sicher, dass Sie ALLE Artikel löschen möchten?")) {

         $.post(admin_url_idx + '/ajax/tools/allArticlesDeleteHaendler', {
            haendler_id: haendler_id
         }, function(data) {
            if (data.status) {
               if (data.status === 'ok') {
//                  alertbox(data.msg);
               }
            }
         }, 'json');
      }
   },
   // Gutscheine - Senden

   gutscheineSave() {
      $('#gutscheine').css('opacity', 0.5);
      $('#gutscheine_nl').css('opacity', 0.5);
      var gutscheine = {};
      gutscheine.sonderpreis_ausschliessen = ($('#sonderpreis_ausschliessen').prop('checked') ? 'on' : 'off');

      for (var i = 1; i < 6; i++) {
         gutscheine['gs_'+i+'_code'] = $('#gs_'+i+'_code').val();
         gutscheine['gs_'+i+'_wert'] = $('#gs_'+i+'_wert').val();
         gutscheine['gs_'+i+'_mode'] = $('#gs_'+i+'_mode').val();
         gutscheine['gs_'+i+'_min'] = $('#gs_'+i+'_min').val();
         gutscheine['gs_'+i+'_datum'] = $('#gs_'+i+'_datum').val();
      }

      gutscheine.aktiv = ($('#gutschein_aktiv').prop('checked') ? 'on' : 'off');
      gutscheine.activate_voucher = ($('#activate_voucher').prop('checked') ? 'on' : 'off');       
      gutscheine.bonusprogramm_aktiv = ($('#bonusprogramm_aktiv').prop('checked') ? 'on' : 'off');
      gutscheine.bonusprogramm_prozent = $('#bonusprogramm_prozent').val();
      gutscheine.activate_voucher = ($('#activate_voucher').prop('checked') ? 'on' : 'off');           
      gutscheine.newsletter_footer = ($('#newsletter_footer').prop('checked') ? 'on' : 'off');
      gutscheine.show_coupon = ($('#show_coupon').prop('checked') ? 'on' : 'off');

      $.post(admin_url_idx + '/tools/gutscheineSave',
         gutscheine,
      function(data) {
         if (data.status === 'ok') {
            $('#gutscheine_nl').html(data.html);
            $('#gutscheine_nl').animate({opacity : 1}, 250);
         }
      }, 'json');

      // Print-Gutscheien speichern, falls Modul vorhanden
      if ($('#gutscheine_print').length) {
//         Tools.gsPrintSave();
         $('#gutscheine_print').css('opacity', 0.5);
         var i             = 0;
         var gs_print_data = [];

         $('#gutscheine_print .gs_inner .gs_line').each(function() {
            // Neue Einträge ohne Code überspringen
            if (parseInt($(this).data('gs_id')) === 0 && $('.gs_pos_2 input', $(this)).val() === '') {
               return true;
            }

            // Gutschein als Objekt
            var gutschein = {};

            gutschein.gs_id   = $(this).data('gs_id');
            gutschein.gs_code = $('.gs_pos_2 input', $(this)).val();
            gutschein.gs_wert = $('.gs_pos_4 input', $(this)).val();
            gutschein.gs_mode = $('.gs_pos_4 select', $(this)).val();
            gutschein.gs_min  = $('.gs_pos_5 input', $(this)).val();
            gutschein.gs_date = $('.gs_pos_6 input.datum', $(this)).val();

            // Und in Array speichern
            gs_print_data[i] = gutschein;
            i++;
         });

         $.post(admin_url_idx + '/ajax/tools/gutscheinePrintSave', {
            gs_print_data: JSON.stringify(gs_print_data)
         }, function(data) {
            if (data.status === 'ok') {
               $('#gutscheine_print').html(data.html);
               $('#gutscheine_print').animate({opacity : 1}, 250);
            }
         }, 'json' );
      }

      $('#gutscheine').animate({opacity : 1}, 250);

   },

   // Mailversand Gutschein (+speichern)
   // 13.03.2019
   gutscheinSend: function(id) {
      Tools.mail_id = id;

      var code  = $('#gs_' + (id) + '_code').val();
      var wert  = $('#gs_' + (id) + '_wert').val();
      var mode  = $('#gs_' + (id) + '_mode').val();
      var datum = $('#gs_' + (id) + '_datum').val();

      $.post(admin_url_idx + '/tools/gutscheinSend', {
         gid   : id,
         code  : code,
         wert  : wert,
         mode  : mode,
         datum : datum
      },
      function(data) {
         if (data.status === 'start') {
            Tools.mail_interval = setInterval('Tools.gutscheinStatus()', 1000);

            Multibox.content(data.msg);
            Multibox.width(200);
            Multibox.show();
         }
         else {
            alertbox('E-Mails konnten nicht versendet werden:\n'+data.msg);
         }
      }, 'json' );
   },

   // Rückmeldung Mailversand
   // 13.03.2019
   gutscheinStatus: function() {
      $.post(admin_url_idx + '/tools/gutscheinStatus', {
         id : Tools.mail_id
      }, function(data) {
         if (data.status === 'start' || data.status === 'ok') {
            Multibox.content(data.msg);
            Multibox.title('Verarbeitung');
            Multibox.width(300);
            Multibox.show();
//            document.getElementById('feedback_title').innerHTML = 'Verarbeitung';
//            document.getElementById('feedback_time').innerHTML = json.msg;
         }
         if (data.status === 'stop')  {
            clearInterval(Tools.mail_interval);
            Multibox.content(data.msg);
            Multibox.title('Erfolgreich');
            Multibox.width(300);
            Multibox.button();
            Multibox.timer = 10;
            Multibox.show();
//            document.getElementById('feedback_title').innerHTML = 'Erfolgreich';
//            document.getElementById('feedback_time').innerHTML = json.msg;
         }
      }, 'json' );
   },

   // Gutscheine / Print-Gutscheine - Aktionscode generieren
   // 06.03.2019
   gsZufall: function(el) {
      var zufall = '';
      var chars  = '123456789ABCDEFGHIJKLMNPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
      var laenge = Math.floor(6 + 5 * Math.random());

      for (var i = 0; i < laenge; i++) {
         var zahl = Math.floor(chars.length * Math.random());
         zufall += chars.substring(zahl,zahl + 1);
      }

      $('.code_inp', $(el).closest('.gs_line')).val(zufall);
   },

   // Gutscheine / Print-Gutscheine - Datum auf Gültigkeit prüfen
   // 07.03.2019
   gsDatum: function(el) {
      var tag   = parseInt($('.tag',   $(el).closest('.gs_pos_6')).val());
      var monat = parseInt($('.monat', $(el).closest('.gs_pos_6')).val());
      var jahr  = parseInt($('.jahr',  $(el).closest('.gs_pos_6')).val());

      $('.tag',   $(el).closest('.gs_pos_6')).css('border-color', $('.gs_pos_3').css('border-color'));
      $('.monat', $(el).closest('.gs_pos_6')).css('border-color', $('.gs_pos_3').css('border-color'));
      $('.jahr',  $(el).closest('.gs_pos_6')).css('border-color', $('.gs_pos_3').css('border-color'));

      if (tag > 31 || tag > 30 && (monat === 4 || monat === 6 || monat === 9 || monat === 11) || tag > 20 && monat === 2) {
         $('.tag', $(el).closest('.gs_pos_6')).css('border-color', '#cc0000');
      }

      if (tag < 10) {
         $('.tag', $(el).closest('.gs_pos_6')).val('0'+tag);
      }

      if (monat < 10) {
         $('.monat', $(el).closest('.gs_pos_6')).val('0'+monat);
      }

      if (monat > 12) {
         $('.monat', $(el).closest('.gs_pos_6')).css('border-color', '#cc0000');
      }

      if (jahr < 100) {
         jahr = jahr + 2000;
         $('.jahr', $(el).closest('.gs_pos_6')).val(jahr);
      }

      $('.datum', $(el).closest('.gs_pos_6')).val(jahr+'-'+monat+'-'+tag);
   },


   // Print-Gutscheine auf doppelte Codes prüfen
   // 13.03.2019
   gsPrintCheckCode: function(el) {
      var search = $(el).val();

      $(el).css('border-color', $('.gs_wert', $(el).closest('.gs_line')).css('border-color'));

      $('.gs_line', $(el).closest('.gs_inner')).each(function() {
         if (el !== $('.gs_pos_2 input', $(this))[0] && $('.gs_pos_2 input', $(this)).val() === search) {
            $(el).css('border-color', '#cc0000');
            return false;
         }
      });
   },

   // Print-Gutscheine - speichern
   // 07.07.2019
   gsPrintSave: function() {
      $('#gutscheine_print').css('opacity', 0.5);
      var i             = 0;
      var gs_print_data = [];

      $('#gutscheine_print .gs_inner .gs_line').each(function() {
         // Neue Einträge ohne Code überspringen
         if (parseInt($(this).data('gs_id')) === 0 && $('.gs_pos_2 input', $(this)).val() === '') {
            return true;
         }

         // Gutschein als Objekt
         var gutschein = {};

         gutschein.gs_id   = $(this).data('gs_id');
         gutschein.gs_code = $('.gs_pos_2 input', $(this)).val();
         gutschein.gs_wert = $('.gs_pos_4 input', $(this)).val();
         gutschein.gs_mode = $('.gs_pos_4 select', $(this)).val();
         gutschein.gs_min  = $('.gs_pos_5 input', $(this)).val();
         gutschein.gs_date = $('.gs_pos_6 input.datum', $(this)).val();

         // Und in Array speichern
         gs_print_data[i] = gutschein;
         i++;
      });

      $.post(admin_url_idx + '/ajax/tools/gutscheinePrintSave', {
         gs_print_data: JSON.stringify(gs_print_data)
      }, function(data) {
         if (data.status === 'ok') {
            $('#gutscheine_print').html(data.html);
            $('#gutscheine_print').animate({opacity : 1}, 250);
         }
      }, 'json' );
   },

   // Print-Gutscheine - Neuer Gutschein
   // 07.03.2019
   gsPrintNew: function() {
      var check = $('.gs_td_2 input', $('#gs_print_tab tr:last'));
      var copy  = $('#gutscheine_print .gs_inner .gs_line:last').clone();
console.log($(copy).find('.gs_pos_0 span'));
      $(copy).find('.gs_pos_0 span').removeClass('deleted').removeClass('outdated').removeClass('fa-check').attr('title', '');
      $(copy).find('.gs_pos_1').html('Gutschein neu');
      $(copy).find('.gs_pos_2 input').val('');
//      $(copy).find('.gs_pos_8').remove();
      $(copy).attr('data-gs_id', '0');
      $(copy).find('.gs_pospos_id_2 input').focus();
      $('#gutscheine_print .gs_inner').append(copy);

//      Tools.gsPrintCheck(check);
   },

   // Print-Gutscheine - löschen
   // 07.03.2019
   gsPrintDel: function(el) {
      var gs_id = parseInt($(el).closest('.gs_line').data('gs_id'));

      // Neu, noch nicht gespeichert
      if (gs_id === 0) {
         $(el).closest('.gs_line').remove();
         return;
      }

      $.post(admin_url_idx+'/ajax/tools/gutscheinePrintDel', {
         gs_print_del: gs_id
      }, function(data) {
         if (data.status === 'ok') {
            $(el).closest('.gs_line').remove();
         }
      }, 'json' );
   },


   //
   einsashopImport: function() {
      $.post(admin_url_idx + '/ajax/tools/cronjobInit', {
         cronjob_url         : $('#einsashop_url').val(),
         cronjob_overwrite   : $('#einsashop_overwrite').val(),
         cronjob_images      : ($('#einsashop_picload_check').is(':checked') ? 'on' : ''),
         cronjob_haendler_id : $('#einsashop_haendler_id').val()
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.msg);
            Multibox.width(300);
            Multibox.close_btn = true;
            Multibox.show();
         }
         else {
            Multibox.content(data.msg);
            Multibox.width(300);
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');

   },

   // Modul Wiso - Einstellungen speichern
   // 13.03.2019
   meinBueroSave: function() {
      $.post(admin_url_idx + '/ajax/tools/meinBueroSave', {
         mb_id           : $('#mb_id').val(),
         mb_pass         : $('#mb_pass').val(),
         mb_pass_check   : ($('#mb_pass_check').is(':checked') ? 'on' : 'off'),
         mb_gesamtbrutto : ($('#mb_gesamtbrutto').is(':checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg, '', 3);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Modul Wiso - Einstellungen speichern
   // 13.03.2019
   orgamaxSave: function() {
      $.post(admin_url_idx + '/ajax/tools/orgamaxSave', {
         orgamax_id           : $('#orgamax_id').val(),
         orgamax_pass         : $('#orgamax_pass').val(),
         orgamax_pass_check   : ($('#orgamax_pass_check').is(':checked') ? 'on' : 'off'),
         orgamax_gesamtbrutto : ($('#orgamax_gesamtbrutto').is(':checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg, '', 3);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },


   dhlSave: function() {
      $.post(admin_url_idx+'/ajax/tools/dhlSave', {
         dhl_is_ekp      : $('#dhl_is_ekp').val(),
         dhl_is_user     : $('#dhl_is_user').val(),
         dhl_is_sign     : $('#dhl_is_sign').val(),
         dhl_teilnehmer  : $('#dhl_teilnehmer').val(),
         dhl_api_version : $('input.dhl_api_version:checked').val()
      }, function(data) {
         if(typeof(data.status) !== 'undefined' && data.status === 'ok') {
            if ($('#dhl_old_api').val() === $('input.dhl_api_version:checked').val()) {
               alertbox('Daten gespeichert'), '', 3;
            }

            else {
               location.href = admin_url_idx+'/toolsSchnittstellen';
            }
         }
         else {
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
         }
      }, 'json')
      .fail(function() {
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
      });
   },

   dhlGewicht: function() {
      $.post(admin_url_idx+'/ajax/tools/dhlGewicht', {
         dhl_gewicht      : $('#dhl_gewicht').val(),
         dhl_versicherung : $('#dhl_versicherung option:selected').val()
      }, function(data) {
         if(typeof(data.status) !== 'undefined' && data.status === 'ok') {
            alertbox('Daten gespeichert', '', 3);
         }
         else {
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
         }
      }, 'json')
      .fail(function() {
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
      });
   },

   dhlDatum: function() {
      $('#dhl_start').html('suche');
      $('#dhl_ende').html('suche');

      $.post(admin_url_idx+'/ajax/tools/dhlDatum', {
         dhl_datum : $('#dhl_datum').val()
      }, function(data) {
         if(typeof(data.status) !== 'undefined' && data.status === 'ok') {
            $('#dhl_start').html(data.start);
            $('#dhl_ende').html(data.ende);
         }
         else {
            $('#dhl_start').html('Fehler');
            $('#dhl_ende').html('Fehler');
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
         }
      }, 'json')
      .fail(function() {
            $('#dhl_start').html('Fehler');
            $('#dhl_ende').html('Fehler');
            alertbox('<span style="color:#cc0000;">Fehler beim Speichern</span>');
      });
   },

   dhlPrintlabel: function(mode) {
console.log(mode);
//      Tools.label_interval = setInterval(function() { Tools.dhlLabelStatus(); }, 1000);
//      alertbox('Labelerstellung gestartet');
      if (mode === 'send') {
         alertbox('Übertragung zu DHL gestartet');
      }

      else if (mode === 'label') {
         alertbox('Labelerstellung gestartet');
      }

      else {
         alertbox('CSV-Datei wird erstellt');
      }

      Spinner.on();

      $.post(admin_url_idx+'/ajax/tools/dhlPrintlabel', {
         start_id     : $('#dhl_start option:selected').val(),
         ende_id      : $('#dhl_ende option:selected').val(),
         dhl_datum    : $('#dhl_datum').val(),
         dhl_laenge   : $('#dhl_laenge').val(),
         dhl_breite   : $('#dhl_breite').val(),
         dhl_hoehe    : $('#dhl_hoehe').val(),
         dhl_paketart : $('#dhl_paketart').val(),
         dhl_mode     : mode
      }, function(data) {

         // Labelerstellung fertig
         if (data.status === 'ok') {
            Multibox.close();
            Spinner.off();
            location.href= admin_url_idx+'/ajax/tools/dhlPdf', '_blank';
         }

         // Label-Erstellung läuft noch
         else if (data.status === 'running') {
            Multibox.close();
            Spinner.off();

            if (confirm(data.msg+"\nTrotzdem Starten?")) {
               $.post(admin_url_idx + '/ajax/tools/dhlAbort',
               function(data) {
                  Tools.dhlPrintlabel();
               });
            }
         }

         // Keine Labels zum Erstellen
         else if (data.status === 'nothing') {
            clearInterval(Tools.label_interval);
            Spinner.off();
            alertbox(data.msg, '', 3, 1);
         }

         // Nochmals abfragen
         else {
            clearInterval(Tools.label_interval);
            Spinner.off();
            alertbox('Fehler: Übertragung nicht möglich', '', 0, 1);
         }
      }, 'json')
      .fail(function() { Spinner.off(); alertbox('Fehler: Übertragung nicht möglich', '', 0, 1); } );

   },

   dhlLabelStatus: function () {
      $.post(admin_url_idx + '/ajax/tools/dhlLabelstatus', {
         status: 'refresh'
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.close();
            alertbox(data.msg, '', 3, 1);
         }

         else if (data.status === 'end') {
            clearInterval(Tools.label_interval);
            alertbox(data.msg, '', 0, 1);
         }

         else  {
            clearInterval(Tools.label_interval);
            alertbox(data.msg, '', 0, 1);
         }
      }, 'json')
      .fail(function() { clearInterval(Tools.label_interval); });
   },


   googleApi: function() {

   },


   // Standard-Fotoset speichern
   // 01.04.2019
   fotoSave: function() {
      var fotoset = [];

      for (var i = 0; i < 7; i++) {
         var foto = {};

         foto.name  = $('#foto_name_'+i).val();
         foto.size  = $('#foto_size_'+i).val();
         foto.price = $('#foto_price_'+i).val();

         fotoset[i] = foto;
      }

      $.post(admin_url_idx+'/ajax/tools/saveFotodata', {
         fotoset : JSON.stringify(fotoset)
      },
      function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg, '', 3);
         }
      }, 'json'
      );
   },

   // Foto-Artikel generieren
   // 01.04.2019
   fotoArtikel: function() {
      var foto_dir = $('#foto_dir option:selected').val();

      if (foto_dir === '0') {
         alertbox('Kein Verzeichnis auf dem Server gewählt!');
         return;
      }

      alertbox('Bitte warten ...', 'Verarbeitung gestartet');

      $.post(admin_url_idx+'/ajax/artikel/saveFotoartikel', {
         foto_art_id      : $('input[name=foto_art_id]:checked').val(),
         foto_artnr       : $('#foto_artnr').val(),
         foto_artname     : $('#foto_artname').val(),
         foto_keywords_on : $('input[name=foto_keywords_on]:checked').val(),
         foto_keywords    : $('#foto_keywords').val(),
         foto_desc        : $('#foto_desc').val(),
         foto_cat         : $('#foto_cat option:selected').val(),
         foto_dir         : foto_dir,
         foto_price       : $('#foto_price_0').val()
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg, 'Verarbeitung');
            clearInterval(Tools.foto_interval);
         }

         else {
            alertbox(data.msg);
         }
      }, 'json');

      Tools.foto_interval = setInterval('Tools.foto_status()', 1000);
   },

   // Status während der Erstellung Artikelbilder anzeigen
   foto_status: function() {
      $.post(admin_url_idx + '/ajax/artikel/fotoStatus', {
         foto: 'kl'
      }, function(data) {
         if (data.status === 'start' || data.status === 'ok') {
            //alertbox(data.msg, 'Verarbeitung');
            $('#multibox_content').html(data.msg);
         }

         if (data.status === 'stop')  {
            $('#multibox_bg').remove();
            $('#multibox').remove();
            clearInterval(Tools.foto_interval);
            alertbox(data.msg, 'Erfolgreich', 3);
         }
      }, 'json');
   },

   // Fotos bereinigen
   // 01.04.2019
   fotoClean: function() {
      $.post(admin_url_idx + '/ajax/artikel/fotoClean', {
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Alle Cronjobs beenden
   // 10.09.2019
   cronClean: function() {
      $.post(admin_url_idx + '/ajax/tools/cronClean', {
      }, function(data) {
         if (data.status === 'ok') {
            alertbox(data.msg, '', 3);
         }
         else {
            alertbox(data.msg);
         }
      }, 'json');
   },

   // Wasserzeichen hochladen
   // 01.04.2019
   wasserzeichenUpload: function() {
      var target_url = admin_url_idx+'/ajax/tools/wasserzeichenUpload';
      var file_types = ['png'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" onchange="fileUpload(this, \''+target_url+'\', \''+file_types+'\', false, false, false, false, true, false, true, \'wasser_div\');" />');
      $('#file_upload').click();

      //fileUpload(file_input, target_url, file_types, mode, lang, show_image, upload_target);
   },

   // Wasserzeichen löschen
   // 01.04.2019
   wasserzeichenDelete: function() {
      if (confirm('Wasserbild löschen?')) {
         $.post(admin_url_idx + '/ajax/tools/wasserzeichenDelete',
         function(data) {
            if (data.status === 'ok') {
               $('#wasser_div').html(data.html);
            }
         }, 'json' );
      }
   },


   // Rabatte speichern
   // 01.04.2019
   rabatteSave: function () {
      $('#rabatte').css('opacity', 0.5);

      $.post(admin_url_idx+'/ajax/tools/rabatteSave',
         $('#form_rabatte').serialize(),
         function(data) {
            if (data.status === 'ok') {
               $('#rabatte_inner').html(data.html);
               $('#rabatte').animate( {'opacity': 1}, 200);
            }
         },
      'json' );
   },

   backup: function() {
      alertbox('Das Backup kann mehrere Minuten dauern. Seite wird nach Fertigstellung neu geladen.');
   },

    cleanOrders: function() {

        // Zielland / Währung für CSV-Export
        $.post(admin_url_idx + '/ajax/tools/cleanOrders', {
           
        },
            function (data) {
                if (data.status === 'ok') {
                    alert("Bestellungen bereinigt");
                } else {
                    alert("Bestellungen konnten nicht bereinigt werden.")
                }
            },
            'json');

    },

    saveGoogle: function () {
       
      // Zielland / Währung für CSV-Export
      $.post(admin_url_idx+'/ajax/tools/saveGoogle', {
            google_shopping : ($('#google_shopping_check').prop('checked') ? 'on' : 'off')
         },
         function(data) {
            if (data.status === 'ok') {
               var color = $('#google_shopping').css('background-color');
               $('#google_shopping').animate( { 'background-color' : 'rgb(100, 240, 100)' }, 1000, function() {
                  $(this).animate({'background-color' : color }, 250);
               });
            }
         },
      'json' );
   },


    googleExport: function (mode) {
     
      var land     = $('#google_land').val();
      var waehrung = $('#google_waehrung').val();
      var form     = '';

      form += '<form id="hiddenform" action="'+admin_url_idx+'/tools/googleExport" method="post" target="_blank">';
      form += '   <input type="hidden" name="export" value="'+mode+'" />';
      form += '   <input type="hidden" name="land" value="'+land+'" />';
      form += '   <input type="hidden" name="waehrung" value="'+waehrung+'" />';
      form += '</form>';

      $('body').append(form);
      $('#hiddenform').submit().remove();
   },


   saveEbay: function() {
      $.post(admin_url_idx+'/ajax/tools/saveEbay', {
            ebay_api : ($('#ebay_api').prop('checked') ? 'on' : 'off')
         },
         function(data) {
            if (data.status === 'ok') {
               var color = $('#ebay_module').css('background-color');
               $('#ebay_module').animate( { 'background-color' : 'rgb(100, 240, 100)' }, 1000, function() {
                  $(this).animate({'background-color' : color }, 250);
               });
            }
         },
      'json' );
   },


   saveAmazonOrders: function() {
      $.post(admin_url_idx+'/ajax/tools/saveAmazonOrders', {
         amazonorders_enabled     : ($('#amazonorders_enabled').prop('checked') ? 'on' : 'off'),
         amazonorders_mws_access  : $('#amazonorders_mws_access').val(),
         amazonorders_mws_secret  : $('#amazonorders_mws_secret').val(),
         amazonorders_seller_id   : $('#amazonorders_seller_id').val(),
         amazonorders_marketplace : $('#amazonorders_marketplace').val(),
         amazonorders_artnr       : $('#amazonorders_artnr').val(),
         amazonorders_lager       : ($('#amazonorders_lager').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            $('#module_amazonorders').html(data.html);

            var color = $('#module_amazonorders').css('background-color');
            $('#module_amazonorders').animate( { 'background-color' : 'rgb(100, 240, 100)' }, 1000, function() {
               $(this).animate({'background-color' : color }, 250);
            });
         }
      },
      'json' );
    },

   saveBillbee: function() {
      $.post(admin_url_idx+'/ajax/tools/saveBillbee', {
         billbee_shop_id : $('#billbee_shop_id').val(),
         billbee_key     : $('#billbee_key').val()
      },
      function(data) {
         if (data.status === 'ok') {
            var color = $('#module_billbee').css('background-color');
            $('#module_billbee').animate( { 'background-color' : 'rgb(100, 240, 100)' }, 1000, function() {
               $(this).animate({'background-color' : color }, 250);
            });
         }
      }, 'json' );
   },

   handlerbundSave : function() {
      Spinner.on();

      $.post(admin_url_idx+'/ajax/tools/haendlerbundSave', {
         haendlerbund_token : $('#haendlerbund_token').val()
      },
      function(data) {
         Spinner.off();

         if (data.status === 'ok') {
            $('#heandlerbund').html(data.html);
         }

         if (data.status === 'token_error') {
            $('#haendlerbund').html(data.html);
            $('#token_error_hb').show();
         }
      }, 'json' );
  },

   itrectkanzleiSave : function() {
      Spinner.on();

      $.post(admin_url_idx+'/ajax/tools/itrectkanzleiSave', {
         haendlerbund_token : $('#itrectkanzlei_token').val()
      },
      function(data) {
         Spinner.off();

         if (data.status === 'ok') {
            $('#itrectkanzlei').html(data.html);
         }

         if (data.status === 'token_error') {
            $('#itrectkanzlei').html(data.html);
            $('#token_error_it').show();
         }
      }, 'json' );
  },

   dummy: function() {}
};

$(function() {
   if ($('#dhl_haendler').length) {
      $('#dhl_savegewicht').click(function() { Tools.dhlGewicht(); });
      $('#dhl_printlabel_send').click(function() { Tools.dhlPrintlabel('send'); });
      $('#dhl_printlabel_print').click(function() { Tools.dhlPrintlabel('label'); });
      $('#dhl_printlabel_csv').click(function() { Tools.dhlPrintlabel('csv'); });
      $('#dhl_params').click(function() { Tools.dhlSave(); });
      $('#dhl_datum').datepicker({
         dateFormat:      'yy-mm-dd',
         maxDate:         0,
         yearRange:       "<?php echo (date('Y') - 1); ?>:<?php echo (date('Y')); ?>",
         dayNames:        ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
         dayNamesMin:     [ "So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
         dayNamesShort:   [ "Son", "Mon", "Din", "Mit", "Don", "Fre", "Sam" ],
         monthNames:      [ "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" ],
         monthNamesShort: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ]
      });
      $('#datepicker_starter').click(function() { $("#dhl_datum").datepicker("show"); });
      $('#dhl_datum').change(function() { Tools.dhlDatum(); });
   }
});

// Ebay Business-Options nachladen
// 29.10.2019
$(function() {
   if ($('#ebay_options').length && $('#ebay_shop').hasClass('listsloaded')) {
      $.post(admin_url_idx + '/ajax/artikel/ebayShopOptionsFile',
         {},

         function(data) {
            if (data.status === 'ok') {
               $('#ebay_options').html(data.html);
            }
         }, 'json'
      );
   }
});


var Shopinhaber = {
   // Daten Shopinhaber speichern
   save: function() {
      if ($('#pass1').val() !== '') {
         var passtest = Shopinhaber.checkPasswords();

         if (!passtest) {
            return;
         }
      }

      $('#inhaber_daten').submit();
   },

   checkPasswords: function() {
      var pass1 = $('#pass1').val();
      var pass2 = $('#pass2').val();
      $('#pass2').css('color', $('#pass2').css('color'));

      if (pass1.length !== 0 && pass1 !== pass1) {
         $('#pass2').css('color', '#cc0000');
         $('label', $('#passwort2')).css('color', '#cc0000');

         return false;
      }

      return true;
   },

   popupSmtp: function() {
      $.post(admin_url_idx+'/ajax/shopinhaber/popupSmtp', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width(450);
            Multibox.show();

            $('#smtp_email').val($('#shop_email').val());
         }

         else  {
            alertbox('Statistik konnte nicht geladen werden');
         }
      }, 'json');
   },

   popupSmtpSave: function() {
      var smtp_email = $('#smtp_email').val();
      var smtp_check  = $('#smtp_check2').prop('checked');

      $.post(admin_url_idx+'/ajax/shopinhaber/popupSmtpSave', {
         smtp_email  : smtp_email,
         smtp_check  : ($('#smtp_check2').prop('checked') ? 'on' : 'off'),
         smtp_user   : $('#smtp_user').val(),
         smtp_pass   : $('#smtp_pass').val(),
         smtp_server : $('#smtp_server').val(),
         smtp_port   : $('#smtp_port').val()
      }, function(data) {
         if (data.status === 'ok') {
          //  $('#shop_email').val(smtp_email);
            Multibox.close();

            if (smtp_check) {
               $('.email_button').removeClass('button_ci').removeClass('button').addClass('button_ci');
            }

            else {
               $('.email_button').removeClass('button_ci').removeClass('button').addClass('button');
            }
         }
      }, 'json');
   },

   // Umsatzt-Statistik Jahr ändern
   // 06.04.2019
   statistikChanged: function() {
      var year = $('#statistik_year').find('option:selected').val();
      var mode = ($('#statistik_mode_all').prop('checked') ? 'all' : 'session');

      $.post(admin_url_idx+'/ajax/shopinhaber/statistikChanged', {
         year: year,
         mode: mode
      }, function(data) {
         if (data.status === 'ok') {
            $('#statistik').html(data.statistik);
            $('#stat_aktuell').html(data.aktuell);
            $('#stat_last').html(data.last);
         }

         else  {
            alertbox('Statistik konnte nicht geladen werden');
         }
      }, 'json');
   },

   // Monatliche Klicks - Klicks/User umschalten und
   // Monatliche Klicks - Jahr geändert
   statistikClicks: function() {
      var year = '';

      // Ohen Selectbox
      if (!$('#statistik_year_clicks').length) {
         year = $('#stat_aktuell_clicks').html();
      }

      // Mit Selectbox
      else {
         year = $('#statistik_year_clicks').find('option:selected').val();
      }

      var mode = ($('#statistik_mode_all').prop('checked') ? 'all' : 'session');

      $.post(admin_url_idx+'/ajax/shopinhaber/statistikClicks', {
         year: year,
         mode: mode
      }, function(data) {
         if (data.status === 'ok') {
            $('#statistik_monat_clicks').html(data.statistik);
            $('#stat_aktuell_clicks').html(data.aktuell);
            $('#stat_last_clicks').html(data.last);
            $('#user_klicks').html(mode === 'all' ? 'Klicks' : 'User');
         }

         else  {
            alertbox('Statistik konnte nicht geladen werden');
         }
      }, 'json');
   },

   useStatistic: function() {
      // Statistik aus -> einschalten:true; ausscahlten:false
      var use_statistic = ($('#use_statistic').hasClass('statistic_off'));

      $.post(admin_url_idx+'/ajax/shopinhaber/statisticActive', {
         use_statistic : (use_statistic ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            if (use_statistic) {
               $('#use_statistic').removeClass('statistic_off').removeClass('statistic_on').addClass('statistic_on');
            }

            else {
               $('#use_statistic').removeClass('statistic_off').removeClass('statistic_on').addClass('statistic_off');
            }
         }

         else  {
            alertbox('Statistik konnte nicht geladen werden');
         }
      }, 'json');
   },

   showPass: function(el) {
console.log(el, $('#smtp_pass').attr('type'));
      if ($('#smtp_pass').attr('type') === 'password') {
         $('#smtp_pass').attr('type', 'text');
         $(el).removeClass('fa-eye').addClass('fa-eye-slash');
      }

      else {
         $('#smtp_pass').attr('type', 'password');
         $(el).removeClass('fa-eye-slash').addClass('fa-eye');
      }
   },

   dummy: function() {}
};
var Versandart = {
   checkVersandwert: function(id, tab) {
      var wert2 = parseFloat(komma2point($('#versandwert2_'+tab).val()));
      var wert4 = parseFloat(komma2point($('#versandwert4_'+tab).val()));

      if (id === 1 && wert4 !== 0 && wert2 > wert4) {
         wert4 = wert2;
      }

      if (id === 2 && wert4 < wert2) {
         wert4 = wert2 + 1;
      }

      var wert5 = wert4 + 0.01;
      $('#versandwert2_'+tab).val(point2komma(wert2.toFixed(2)));
      $('#versandwert4_'+tab).val(point2komma(wert4.toFixed(2)));
      $('#versandwert5_'+tab).html(point2komma(wert5.toFixed(2)));
   },

   checkGewichtswert: function(id, tab) {
      var wert1 = parseFloat(komma2point($('#gewichtwert1_'+tab).val()));
      var wert2 = parseFloat(komma2point($('#gewichtwert2_'+tab).val()));
      var wert3 = parseFloat(komma2point($('#gewichtwert3_'+tab).val()));
      var wert4 = parseFloat(komma2point($('#gewichtwert4_'+tab).val()));

      if (id === 1 && wert1 > wert2) {
         wert1 = wert2;
      }

      if (id === 2 && wert2 < wert1) {
         wert2 = wert1 + 0.01;
      }

      if (id === 3 && wert3 < wert2) {
         wert3 = wert2 + 0.01;
      }

      if (id === 4 && wert4 < wert3) {
         wert4 = wert3 + 0.01;
      }


      var wert5 = wert4 + 0.001;
      $('#gewichtwert1_'+tab).val(point2komma(wert1.toFixed(3)));
      $('#gewichtwert2_'+tab).val(point2komma(wert2.toFixed(3)));
      $('#gewichtwert3_'+tab).val(point2komma(wert3.toFixed(3)));
      $('#gewichtwert4_'+tab).val(point2komma(wert4.toFixed(3)));
      $('#gewichtwert5_'+tab).html(point2komma(wert5.toFixed(3)));
   },

   dummy: function() {}
};

// Spalte 2 und 3 anzeigen
// 07.05.2019
$(function() {
   if ($('#versandart').length) {
      $('.versandlist_toggle').each(function() {
         $(this).on('click', function() {
            // 'einblenden' bei Klick ausblenden und Box
            $(this).hide();

            // Block-Nr (2 oder 3)
            var block = $(this).attr('data-versand_show');
            $('.versandlist_toggle').each( function() {
               if ($(this).attr('data-versand_show') === block) {
                  $('.show_none', $(this).parent()).show();
                  $('.show_none', $(this).parent()).css('cssText', 'display:block !important');
                  $('.versand_line', $(this).parent()).animate({'opacity' : 1}, 500);
                  $('.versand_line_hr', $(this).parent()).animate({'opacity' : 1}, 500);
                  $('input', $(this).parent()).each(function() { $(this).attr('disabled', false); });
               }
            });
         });
      });
   }
});


var Zahlart = {
   // Popup Zahlungstexte anzeigen
   // 24.04.2019
   popup: function () {
      $.post(admin_url_idx+'/ajax/zahlungsart/getZahlartPopup',
         {},
         function(data) {
            if (data.status === 'ok') {
               Multibox.content(data.html);
               Multibox.width(500);
               Multibox.bg_close = true;
               Multibox.show();
               Zahlart.check();
               Multibox.resize();
            }
         },
         'json'
      );
   },

   // Popup Zahlungstexte speichern
   // 24.04.2019
   popupSave: function () {
      $.post(admin_url_idx+'/ajax/zahlungsart/saveZahlartPopup',
         $("#za_text_form").serialize(),
         function(data) {
            if (data.status === 'ok') {
               Multibox.close();
            }
         },
         'json'
      );
   },

   // Popup Zahlungstexte ausblenden nur verwendete (Checkbox) anzeigen
   // 24.04.2019
   check: function() {
      $('.za_box', $('#zahlart_popup')).hide();

      $('.zahlart_line').each(function() {
         var id    = $(this).attr('data-check_id');
         var check = $('.zahlart_title input', $(this)).length && $('.zahlart_title input', $(this)).prop('checked');

         if (check) {
            $('.za_box').each(function() {
               if ($(this).attr('data-check_id') === id) {
                  $(this).show();
               }
            });
         }
      });
   },

   twintCert: function() {
      $.post(admin_url_idx+'/ajax/zahlungsart/twintCert',
         function(data) {
            if (data.status === 'ok') {
               Multibox.content(data.html);
               Multibox.width(350);
               Multibox.bg_close = true;
               Multibox.show();
               Zahlart.check();
               Multibox.resize();
            }
         },
         'json'
      );
   },

   twintCertUpload: function() {
      var cert_file = $('#cert_upload');
      var cert_pass = $('#cert_pass').val();

      if (cert_file[0].files.length === 0) {
         alertbox('Keine Datei ausgewählt');
         return;
      }

      if (cert_file[0].files[0] !== '' && cert_pass === '') {
         alertbox('Passwort nicht angegeben');
         return;
      }

      var target_url = admin_url_idx+'/ajax/zahlungsart/twintCertUpload';
      var file_types = ['p12', 'pem'];

      fileUpload(cert_file, target_url, file_types, cert_pass);
   },

   dummy: function() {}
};
// Vorbestellbutton anzeigen / verstecken
// 25.04.2019
$(function() {
   if ($('#lager_leer').length) {
      $('#lager_leer').change(function() {
         if ($('#lager_leer').prop('checked')) {
            $('#vorbestellung').show();
         }

         else {
            $('#vorbestellung').hide();
         }
      });
   }
});


var Steuer = {
   multishopChange: function() {
      $.post(admin_url_idx+'/ajax/steuer/multishopChange', {
         multishop : ($('#multishop').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            var color = $('label', $('#multishop').closest('.steuer_line')).css('color');
            $('label', $('#multishop').closest('.steuer_line')).animate( { 'color' : 'rgb(100, 240, 100)' }, 100, function() { $(this).animate({ 'color' : color }, 250); });
         }
      }, 'json' );
   },

   multishop: function() {
      $.post(admin_url_idx+'/ajax/steuer/multishopPopup', {
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.bg_close  = true;
            Multibox.close_btn = true;
            Multibox.content(data.html);
            Multibox.width(350);
            Multibox.show();
         }
      }, 'json' );
   },

   multishopSave: function() {
      $.post(admin_url_idx+'/ajax/steuer/multishopSave', {
         multishop_server : $('#multishop_server').val(),
         multishop_user   : $('#multishop_user').val(),
         multishop_pass   : $('#multishop_pass').val(),
         multishop_db     : $('#multishop_db').val(),
         multishop_port   : $('#multishop_port').val(),
         multishop_images : $('#multishop_images').val()
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();
         }
      }, 'json' );
   }
};

var Texte = {
   reset: function() {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         Confirmbox.head = 'Alle Texte auf Standard zurücksetzen?';
         Confirmbox.yes_function = 'Texte.reset()';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      location.href = admin_url_idx+'/texte/reset';
   },

   // Upload Headergrafik
   // 25.04.2019
   headerUplaod: function() {
      var target_url = admin_url_idx+'/ajax/texte/headerUpload';
      var file_types = ['png'];

      $('body').append('<input type="file" id="file_upload" style="opacity:0;" \
         onchange="fileUpload(this, \
                              \''+target_url+'\', \
                              \''+file_types+'\', \
                              false, false, false, false, false, false, \
                              true, \'header_img\');" />');
      $('#file_upload').click();

      //fileUpload(file_input, target_url, file_types, mode, lang, show_image, upload_target);
   },

   // Headergrafik löschen
   // 25.04.2019
   headerDelete: function() {
      $.post(admin_url_idx+'/ajax/texte/headerDelete',
         {},
         function(data) {
            if(data.status === 'ok') {
               $('#header_img').prop('src', '');
            }
         }, 'json'
      );
   },

   dummy: function() {}
};
