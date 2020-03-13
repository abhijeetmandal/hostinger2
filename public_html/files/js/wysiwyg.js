$(function() {
    $('#main-content').on('click', '.wysiwyg .controls a', function(e) {
        e.preventDefault();

        var $this = $(this);
        var $container = $this.closest('.wysiwyg');
        var $overlay = $container.find('.overlay-upload');

        $overlay.find('.submit-upload').on('click', function(e) {

        });


        if ($this.hasClass('preview-button')) {
            $this.parent().addClass('active');
            $container.find('.edit-button').parent().removeClass('active');

            // Hide textarea and append html
            $container.find('textarea').hide();
            $.post('/files/ajax/preview.php', {data: $container.find('textarea').val()}, function(data) {
                $container.find('.preview').html(data).show();
            }, 'html');

        } else if ($this.hasClass('edit-button')) {
            $this.parent().addClass('active');
            $container.find('.preview-button').parent().removeClass('active');

            // Delete html and show textarea
            $container.find('.preview').hide();
            $container.find('textarea').show();

        } else if ($this.hasClass('show-smilies')) {
            $this.parent().toggleClass('active');
            $container.find('.smilies').slideToggle();
        } else if ($this.hasClass('show-upload')) {
            toggleUpload($overlay);
        } else {
            var textarea = $container.find('textarea');
            var tag = this.getAttribute('data-tag');

            var value = this.getAttribute('data-value');
            var extra = this.getAttribute('data-extra');

            if (tag) {
                value = (value)?'='+value:'';
                tagBefore = '[' + tag + value + ']';
                tagAfter = '[/' + tag + ']';

                if (extra) {
                    tagBefore += '\n[' + extra + '] ';
                    tagAfter = '\n' + tagAfter;
                }
            } else {
                tagBefore = ' ' + value + ' ';
                tagAfter = '';
            }

            var pos = textarea.getCursorPosition();

            textarea.val(function(index, value) {
                before = value.substring(0, pos.start);
                middle = value.substring(pos.start, pos.end);
                end = value.substring(pos.end);

                return before + tagBefore + middle + tagAfter + end;
            }).focus().setCursorPosition(pos.start + tagBefore.length + ((middle.length)?middle.length + tagAfter.length:0));
        }
    }).on('keypress', '.wysiwyg textarea', function(e) {
        if (e.which == 13 && !e.shiftKey) {
            $form = $(this).closest('form');
            if ($form.find('#enter').is(':checked')) {
                e.preventDefault();
                $form.submit();
            }
        }
    }).on('change', '#enter', function(e) {
        var state = $(this).is(':checked');
        createCookie("wysiwygEnter", state);
    });

    function toggleUpload(overlay) {
        overlay.stop().fadeToggle();
    }
});