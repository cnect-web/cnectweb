/**
 * @file
 * CNECT In Page navigation.
 */

(function ($, Drupal, CKEDITOR) {
    // Register the plugin within the editor.
    CKEDITOR.plugins.add('cnt_in_page_navigation_item', {
      // The plugin initialization logic goes inside this method.
      init: function (editor) {
        // Define the editor command that inserts a In Page Navigation item (h2 with unique id).
        editor.addCommand('insertInPageNavigationItem', {
          // Define the function that will be fired when the command is executed.
          exec: function (editor) {
            var selection = editor.getSelection();
            var selectedText =  selection.getSelectedText();
            var element = selection.getStartElement();

            if (element) {
              element = element.getAscendant('h2', true);
              console.log(element);
            }

            if (!element || element.getName() != 'h2') {
              element = editor.document.createElement('h2');
              element.appendText(selectedText);
              element.setAttribute('id', "ecl-inpage-"+(new Date().getTime()).toString(36));
              this.insertMode = true;
            }
            
            if (element || element.getName() == 'h2') {
              element.setAttribute('id', "ecl-inpage-"+(new Date().getTime()).toString(36));
              editor.updateElement();
            }
            
            else{
              this.insertMode = false;
            }

            var h2 = element;

            if ( this.insertMode ){
              editor.insertElement( h2 );
            }
                
          }
        });
        // Create the toolbar button that executes the above command.
        editor.ui.addButton('InPageNavigationItem', {
          label: 'Insert in page navigation item',
          command: 'insertInPageNavigationItem',
          icon: this.path + 'icons/cnt_in_page_navigation_item.png'
        });
      }
    });
  })(jQuery, Drupal, CKEDITOR);