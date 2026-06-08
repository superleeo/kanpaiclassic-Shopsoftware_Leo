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

/* global Multibox, admin_url_idx, Design, tinymce, Livedesigner2, Livedesigner_ext, Confirmbox, design */

var Livedesigner = {
   popupBackground: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupBackground', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('bottom right');
         }
      }, 'json');
   },

   saveBackground: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveBackground', {
         bg_aussen         : $('#bg_aussen').val(),
         bg_aussen_opacity : $('#bg_aussen_opacity').val(),
         bg_fixed          : ($('#bg_fixed1').prop('checked') ? 'off' : 'on'),
         bg_repeat         : $('input[name=bg_repeat]:checked').val(),
         flaeche_hg        : ($('#ld_flaeche_mitte').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            $('.minicolors').minicolors('destroy');
            Multibox.close();
            location.href = admin_url_idx+'/designLivedesigner';
         }
      }, 'json');
   },

   popupVideo: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupVideo', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(360);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('bottom right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveVideo: function() {
      Multibox.close();
      location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
   },

   popupMenuLeft: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupMenuLeft', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveMenuLeft: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveMenuLeft', {
         menuleiste         : $('#menuleiste').val(),
         menuleiste_opacity : $('#menuleiste_opacity').val(),
         menu_oben          : $('#menu_oben').val(),
         over_oben          : $('#over_oben').val(),
         shop_check         : ($('#shop_check').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            $('.minicolors').minicolors('destroy');
            Multibox.close();
            location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
         }
      }, 'json');
   },

   popupMenuRight: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupMenuRight', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveMenuRight: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveMenuRight', {
         menuleiste         : $('#menuleiste').val(),
         menuleiste_opacity : $('#menuleiste_opacity').val(),
         menu_oben          : $('#menu_oben').val(),
         over_oben          : $('#over_oben').val(),

         icon_farbe         : $('input[name=icon_farbe]:checked').val(),
         anmelden_mode      : $('input[name=anmelden_mode]:checked').val(),
         merkliste_mode     : $('input[name=merkliste_mode]:checked').val(),
         warenkorb_mode     : $('input[name=warenkorb_mode]:checked').val(),
         suchfeld_mode      : $('input[name=suchfeld_mode]:checked').val(),
         flaggen_mode       : $('input[name=flaggen_mode]:checked').val()
      },
      function(data) {
         if (data.status === 'ok') {
            $('.minicolors').minicolors('destroy');
            Multibox.close();
            location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
         }
      }, 'json');
   },

   popupBreite: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupBreite', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(360);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveBreite: function() {
      var max_width      = $('#ld_max_width').val();
      var flaeche_header = ($('#ld_flaeche_header').prop('checked') ? 'on' : 'off');
      var flaeche_mitte  = ($('#ld_flaeche_mitte').prop('checked') ? 'on' : 'off');
      var flaeche_liste  = ($('#ld_flaeche_liste').prop('checked') ? 'on' : 'off');
      var flaeche_footer = ($('#ld_flaeche_footer').prop('checked') ? 'on' : 'off');
/*
      var ld_bg_header         = $('#ld_bg_header').val();
      var ld_bg_header_opacity = $('#ld_bg_header_opacity').val();
      var ld_bg_innen          = $('#ld_bg_innen').val();
      var ld_bg_innen_opacity  = $('#ld_bg_innen_opacity').val();
      var ld_bg_footer         = $('#ld_bg_footer').val();
      var ld_bg_footer_opacity = $('#ld_bg_footer_opacity').val();
*/
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveBreite', {
         max_width      : max_width,
         flaeche_header : flaeche_header,
         flaeche_mitte  : flaeche_mitte,
         flaeche_liste  : flaeche_liste,
         flaeche_footer : flaeche_footer
/*
         ld_bg_header         : ld_bg_header,
         ld_bg_header_opacity : ld_bg_header_opacity,
         ld_bg_innen          : ld_bg_innen,
         ld_bg_innen_opacity  : ld_bg_innen_opacity,
         ld_bg_footer         : ld_bg_footer,
         ld_bg_footer_opacity : ld_bg_footer_opacity
 */
      },
      function(data) {
         if (data.status === 'ok') {
            if (data.reload === 'y') {
               location.href = admin_url_idx+'/designLivedesigner';
               return;
            }

            $('.minicolors').minicolors('destroy');
            $('.content_center', $('#livedesigner')).css('max-width', ld_max_width+'px');
            $('.livedesigner_width', $('#livedesigner')).css('max-width', ld_max_width+'px');
            Multibox.close();
            Livedesigner.loadCssColors();
         }
      }, 'json' );
   },

   popupLogobanner: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupLogobanner', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
            setTimeout(function() { Multibox.resize(); }, 500);
         }
      }, 'json');
   },

   // Elemnte oder Template
   saveLogobanner: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveLogobanner', {
         // logobanner_seo wird direkt gespeichert (Design.saveLink())
         bg_header         : $('#bg_header').val(),
         bg_header_opacity : $('#bg_header_opacity').val()
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
            }
         }
      }, 'json');
   },

   popupKategorien: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupKategorien', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveKategorien: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveKategorien', {
         // kategorien_links     : ($('#kategorien_links1').prop('checked') ? 'off' : 'on'),
         kategorien_links     : $('input[name=kategorien_links]:checked').val(),
         shop_check           : ($('#shop_check').prop('checked') ? 'on' : 'off'),
         schatten             : ($('#schatten').prop('checked') ? 'on' : 'off'),
         linien_kat           : ($('#linien_kat').prop('checked') ? 'on' : 'off'),

         horiz_kat            : $('#horiz_kat').val(),
         horiz_kat_opacity    : $('#horiz_kat_opacity').val(),
         horiz_aktiv          : $('#horiz_aktiv').val(),
         horiz_aktiv_opacity  : $('#horiz_aktiv_opacity').val(),
         vertikal_kat         : $('#vertikal_kat').val(),
         vertikal_kat_opacity : $('#vertikal_kat_opacity').val(),
         unter_kat            : $('#unter_kat').val(),
         unter_kat_opacity    : $('#unter_kat_opacity').val(),
         over_kat             : $('#over_kat').val(),
         over_kat_opacity     : $('#over_kat_opacity').val(),

         horiz_kat_c          : $('#horiz_kat_c').val(),
         horiz_kat_c_ovr      : $('#horiz_kat_c_ovr').val(),
         haupt_kat_c          : $('#haupt_kat_c').val(),
         haupt_kat_c_ovr      : $('#haupt_kat_c_ovr').val()
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();
            setTimeout( function() { $('.minicolors').minicolors('destroy'); }, 500);

            if ($('#multibox2').length) {
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
            }
         }
      }, 'json' );

   },

   popupAbstand: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupAbstand', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(350);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Immer vorhanden
   saveAbstand: function() {
      var abstand      = $('#ld_abstand').val();

      $.post(admin_url_idx+'/ajax/designLivedesigner/saveAbstand', {
         abstand      : abstand
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json' );

   },

   popupAbstandoben: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupAbstandoben', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(350);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Immer vorhanden
   saveAbstandoben: function() {
      var abstand_oben = $('#ld_abstand_oben').val();

      $.post(admin_url_idx+'/ajax/designLivedesigner/saveAbstandoben', {
         abstand_oben : abstand_oben
      },
      function(data) {
         if (data.status === 'ok') {
            Multibox.close();
            location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
         }
      }, 'json' );

   },

   // Immer vorhanden
   popupElemente: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupElemente', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();

            Livedesigner.moduleInit();
            Design.initLinkColors('buttom right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveElemente: function() {
      // Farben speichern, wenn geändert
      $('.line').each( function() {
         if ($(this).attr('data-color_changed') === 'true') {
            var modul_id = $(this).attr('data-id');
            var modultyp = $(this).attr('data-modultyp');

            if (modul_id === 'slideshow') { }

            else if (modul_id === 'starthtml') {
               $.post(admin_url_idx+'/ajax/designLivedesigner/saveModuleBackground', {
                  modul_id       : modul_id,
                  background     : $('.background', $(this)).val(),
                  background_opc : $('.background', $(this)).attr('data-opacity')
               },
               function(data) {

               }, 'json');
            }

            else if (modul_id === 'collage') { }
            else if (modul_id === 'artikelliste') { }
            else if (modul_id === 'bannerunten') { }

            else if (modultyp === 'akkordeon' || modultyp === 'karussell' || modultyp === 'slider') {
               $.post(admin_url_idx+'/ajax/designLivedesigner/saveModuleBackgroundExt', {
                  modul_id       : modul_id,
                  background     : $('.background', $(this)).val(),
                  background_opc : $('.background', $(this)).attr('data-opacity')
//                  background_opc : $('.opacity', $(this)).val()
               },
               function(data) {

               }, 'json');
            }

            else {
               $.post(admin_url_idx+'/ajax/designLivedesigner/saveModuleBackground2', {
                  modul_id       : modul_id,
                  background     : $('.background', $(this)).val(),
                  background_opc : $('.background', $(this)).attr('data-opacity')
               },
               function(data) {

               }, 'json');
            }
         }
      });

      $.post(admin_url_idx+'/ajax/designLivedesigner/saveElemente', {
         telefon_on   : ($('#ld_telefon_on').prop('checked') ? 'on' : 'off'),
         slideshow_on : ($('#slideshow_on').prop('checked') ? 'on' : 'off'),
         starthtml_on : ($('#starthtml_on').prop('checked') ? 'on' : 'off'),
         collage_on   : ($('#collage_on').prop('checked') ? 'on' : 'off'),
         artikelliste_on   : ($('#artikelliste_on').prop('checked') ? 'on' : 'off'),
         bannerunten_on   : ($('#bannerunten_on').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            $('.minicolors').minicolors('destroy');
            Multibox.close();
            location.href = admin_url_idx+'/designLivedesigner';
         }
      }, 'json');

   },
   line_to_delete : false,

   moduleInit: function() {
      $('#livedesigner2_sortable').sortable({
         handle      : ".sort",
         placeholder : "livedesigner2_heighlight",
         stop        : function(event, ui) {
            Livedesigner.moduleSort(event, ui);
         }
      });

      $( "#sortable" ).disableSelection();
   },

   moduleSort: function(event, ui) {
      var sort_arr = [];
      var sort     = 0;

      $('.line', $('#livedesigner2_sortable')).each(function() {
         sort_arr[sort] = [$(this).attr('data-id'), (sort + 1)];
         sort++;
      });

      $.post(admin_url_idx + '/ajax/designLivedesigner/moduleSort', {
         sort_arr : sort_arr
      }, function(data) {
         if (data.status === 'ok') {
            showFeedback($('#livedesigner2_sortable'));
         }
      }, 'json');

   },

   moduleNew: function(module) {
      $('#livedesigner_select').val('');

      $.post(admin_url_idx + '/ajax/designLivedesigner/moduleNew', {
         module : module
      }, function(data) {
         if (data.status === 'ok') {
            $('input.minicolors', $('#livedesigner2_sortable')).each( function() {
               $(this).minicolors('destroy');
            });

         $('#livedesigner2_sortable').append(data.html);

            var option = $('#livedesigner_select option[value='+module+']');

            if ($(option).attr('data-mode') === 'single') {
               $(option).hide();
            }

            Design.initLinkColors('buttom right');
         }
      }, 'json');
   },

   moduleActive: function(el, modul_id) {
      var active = ($(el).prop('checked') ? 'on' : 'off');

      $.post(admin_url_idx + '/ajax/designLivedesigner/moduleActive', {
         modul_id : modul_id,
         active   : active
      }, function(data) {
         if (data.status === 'ok') {
            // Popup aktualisieren, wenn vom Popup 2. Ebene aufgerufen
            if ($('#popup_artikel2').length || $('#popup_bild2').length || $('#popup_text2').length) {
               $('.line[data-id="'+modul_id+'"] input', $('#livedesigner2_sortable')).prop('checked', (active === 'on' ? true : false));
            }

            else {
               showFeedback($(el).closest('.line'));
            }
         }
      }, 'json');
   },

   moduleDelete: function(el) {
      if (Confirmbox.yes_function === '' || Confirmbox.yes_function !== 'start') {
         // Aufrufendes Objekt speichern
         Livedesigner.line_to_delete = el;
         Confirmbox.head = 'Modul löschen?';
         Confirmbox.html = '';
         Confirmbox.yes_function = 'Livedesigner.moduleDelete("el")';
         Confirmbox.show();
         return;
      }

      Confirmbox.yes_function = '';

      var parent = $(Livedesigner.line_to_delete).closest('.line');
      var modul_id = parent.attr('data-id');
      var modultyp = parent.attr('data-modultyp');

      $.post(admin_url_idx + '/ajax/designLivedesigner/moduleDelete', {
         modul_id : modul_id

      }, function(data) {
         if (data.status === 'ok') {
            parent.remove();
            var option = $('#livedesigner_select option[value='+modultyp+']');

            if ($(option).attr('data-mode') === 'single') {
               $(option).show();
            }

         Multibox.resize();
         }
      }, 'json');
   },

   popupSlideshow() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupSlideshow', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#popup_startseite').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(958);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();

            setTimeout(function() { Multibox.resize(); }, 1000);
         }
      }, 'json');
   },

   // Elemnte oder Template
   saveSlideshow() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveSlideshow', {
         slideshow_r_check : ($('#slideshow_r_check').prop('checked') ? 'on' : 'off'),
         rechts_slide      : ($('#rechts_slide').prop('checked') ? 'on' : 'off'),
         fullscreen_slide  : ($('#fullscreen_slide').prop('checked') ? 'on' : 'off'),
         slideshow_on      : ($('#popup_slideshow_on').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               $('#slideshow_on').prop('checked', $('#popup_slideshow_on').prop('checked'));
               Multibox.close();
            }

            else {
               Multibox.close();
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
            }
         }
      }, 'json');
   },

   popupStarthtml: function() {
      if (typeof tinymce !== 'undefined' && tinymce.editors[0] !== 'undefined') {
         tinymce.editors[0].remove();
      }

      $.post(admin_url_idx + '/ajax/designLivedesigner/popupStarthtml', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#popup_startseite').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            initEditor2();
            Multibox.resize();
            editorStart();
         }
      }, 'json');
   },

   // Elemnte oder Template
   saveStarthtml() {
      if (typeof tinymce !== 'undefined' && typeof tinymce.triggerSave === 'function') {
         tinymce.triggerSave();
      }

      $.post(admin_url_idx+'/ajax/designLivedesigner/saveStarthtml', {
         starthtml : ($('#starthtml_text').val()),
         starthtml_on : ($('#starthtml_on').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupCollage() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupCollage', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#popup_startseite').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(975);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();

            setTimeout(function() { Multibox.resize(); }, 1000);
         }
      }, 'json');
   },

   saveCollage() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveCollage', {
         collage_on : ($('#collage_on').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupArtikelListe: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupArtikelListe', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#popup_startseite').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(970);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Elemnte oder Template
   saveArtikelListe: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveArtikelListe', {
         artikelliste_on    : ($('#artikelliste_on2').prop('checked') ? 'on' : 'off'),
         startseite_artikel : ($('#startseite_artikel1').prop('checked') ? 'reihen' : 'artikel'),
         startseite_reihen  : $('#startseite_reihen').val(),
         zoom_artikel       : ($('#zoom_artikel').prop('checked') ? 'on' : 'off'),
         thumb_over_check   : ($('#thumb_over_check').prop('checked') ? 'on' : 'off'),
         merkmal_over_check : ($('#merkmal_over_check').prop('checked') ? 'on' : 'off'),
         cbp_display        : $('#cbp_display').val(),
         cbp_animation      : $('#cbp_animation').val(),
         wk_popup_check     : ($('#wk_popup_check').prop('checked') ? 'on' : 'off'),
         cpf_size           : $('input[name=cpf_size]:checked').val(),
         // image_ratio        : $('#image_ratio').val()
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
               $('#artikelliste_on').prop('checked', $('#artikelliste_on2').prop('checked'));
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupArtikelDetails: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupArtikelDetails', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(490);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // ???
   saveArtikelDetails: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveArtikelDetails', {
         detailbild : $('input[name=detailbild]:checked', $('#popup_artikel_detail')).val()
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupBannerunten: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupBannerunten', {
      }, function(data) {
         if (data.status === 'ok') {
            if ($('#popup_startseite').length) {
               Multibox.multibox2 = true;
            }

            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
         }
      }, 'json');
   },

   // Elemnte oder Template
   saveBannerunten: function() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveBannerunten', {
         bannerunten_on : ($('#bannerunten').prop('checked') ? 'on' : 'off')
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupFooter: function() {
      if (typeof tinymce !== 'undefined' && tinymce.editors[0] !== 'undefined') {
         tinymce.editors[0].remove();
      }

      $.post(admin_url_idx + '/ajax/designLivedesigner/popupFooter', {
//        bannerunten_on    : ($('#bannerunten_on').prop('checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('buttom right');
            initEditor2();
            Multibox.resize();
            editorStart();
         }
      }, 'json');
   },

   // Immer vorhanden
   saveFooter() {
      if (typeof tinymce !== 'undefined' && typeof tinymce.triggerSave === 'function') {
         tinymce.triggerSave();
      }

      $.post(admin_url_idx+'/ajax/designLivedesigner/saveFooter', {
         footer_text       : ($('#footer_text').val()),
         footer_mode       : $('input[name=footer_mode]:checked').val(),
         menu_unten        : $('#ld_menu_unten').val(),
         over_unten        : $('#ld_over_unten').val(),
         bg_footer         : $('#ld_bg_footer').val(),
         bg_footer_opacity : $('#ld_bg_footer_opacity').val()
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
               Livedesigner.loadCssColors();
               $('#footer_text').html(data.html);
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupFooterlinks: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupFooterlinks', {
        bannerunten_on    : ($('#bannerunten_on').prop('checked') ? 'on' : 'off')
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(810);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('buttom right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveFooterlinks() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveFooterlinks', {
         footer_mode       : $('input[name=footer_mode]:checked').val(),
         menu_unten        : $('#ld_menu_unten').val(),
         over_unten        : $('#ld_over_unten').val(),
         bg_footer         : $('#ld_bg_footer').val(),
         bg_footer_opacity : $('#ld_bg_footer_opacity').val()
      },
      function(data) {
         if (data.status === 'ok') {
            if ($('#multibox2').length) {
               Multibox.close();
               $('#footer_text').html(data.html);
            }

            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
            }
         }
      }, 'json');
   },

   popupNetzwerk: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupNetzwerk', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(890);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   // Immer vorhanden
   saveNetzwerk() {
      $.post(admin_url_idx+'/ajax/designLivedesigner/saveNetzwerk', {
         telefon_on   : ($('#ld_telefon_on').prop('checked') ? 'on' : 'off'),
         social_status : $('input[name=social_status]:checked').val()
      },
      function(data) {
         if (data.status === 'ok') {
//            if ($('#multibox2').length) {
               Multibox.close();
//               Livedesigner.loadCssColors();
//               $('#footer_text').html(data.html);
//            }

//            else {
               location.href = admin_url_idx+'/designLivedesigner?'+Math.random();
               return;
//            }
         }
      }, 'json');
   },

   socialIconCallback: function(img) {
      $('#socialicon1').attr('src', img);
      $('#socialicon2').attr('src', img);
   },

   DELpopupAccordion: function() {
      $.post(admin_url_idx + '/ajax/designLivedesigner/popupAccordion', {
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.content(data.html);
            Multibox.width(940);
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            Design.initLinkColors('top right');
         }
      }, 'json');
   },

   editSeite: function(seite) {
      if (typeof tinymce !== 'undefined' && tinymce.editors[0] !== 'undefined') {
         tinymce.editors[0].remove();
      }

      $.post(admin_url_idx + '/ajax/designLivedesigner/editSeite', {
         seite : seite
      }, function(data) {
         if (data.status === 'ok') {
            Multibox.multibox2 = true;
            Multibox.content(data.html);
            Multibox.width('auto');
            Multibox.bg_close = true;
            Multibox.close_btn = true;
            Multibox.show();
            initEditor1();
            Multibox.resize();
            editorStart();

            setTimeout(function() { Multibox.resize(); }, 1000);
         }
      }, 'json');
   },

   loadCss: function() {
      $.get(admin_url_idx+'/ajax/designLivedesigner/templateCss',
         function(css) {
            $('#template_var_css').text(css);
         }
      );
   },

   loadCssColors: function() {
      $.get(template_url+'/css/colors.css',
         function(css) {
            $('#colors_css').text(css);
         }
      );
   },

   dummy: function() {}
};
