(function() {
    tinymce.PluginManager.add('cta_buttons', function( ed, url ) {
      ed.addButton('cta-one', {
          text : 'Add CTA',
          icon : false,
          onclick : function() {
               ed.selection.setContent('[cta-one]' + ed.selection.getContent() + '[/cta-one]');
          }
      });

    });

})();
