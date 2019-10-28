(function() {
    tinymce.PluginManager.add('cta_buttons', function( ed, url ) {
      ed.addButton('cta-menu', {
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
              },
              {
                text: 'Get Pricing',
                onclick : function() {
                     ed.selection.setContent('[get-pricing category="CRM" cta="Get Pricing" width="200px" url="product name-reviews"]' + ed.selection.getContent() + '[/get-pricing]');
                }
              }
          ]
      });

    });

})();
