(function() {
    tinymce.PluginManager.add('cta_buttons', function( ed, url ) {
      ed.addButton('cta-one', {
          text : 'Add CTA',
          type: 'menubutton',
          icon : false,
          menu: [
              {
                text: 'Version 1',
                onclick : function() {
                     ed.selection.setContent('[cta-one]' + ed.selection.getContent() + '[/cta-one]');
                }
              },
              {
                text: 'Version 2',
                onclick : function() {
                     ed.selection.setContent('[cta-two]' + ed.selection.getContent() + '[/cta-two]');
                }
              }
          ]
      });

    });

})();
