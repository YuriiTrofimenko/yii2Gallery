(function($) {
    function Autocomplete($input, settings) {
        var _settings = {
            url: '../../../../',
            text: 'text',
            value: 'value',
            chars: 3,
            initText: ''
        };

        $.extend(_settings, settings);

        var $hidden = $('<input type="hidden" name="' + $input.attr('name') + '" value="' + $input.val() + '"/>');
        var $autocomplete = $('<div class="autocomplete-suggestions" style="display: none; position: absolute;"><ul></ul></div>');
        var text = '';
        var data = null;

        $input.attr('name', null);
        $input.addClass('autocomplete');
        $input.val(_settings.initText);
        $input.after($hidden);

        $autocomplete.appendTo(document.body);

        $input.keyup(function() {
            if ($input.val() != text) {
                text = $input.val();

                if (text.length >= _settings.chars) {
                    $.ajax({
                        url: _settings.url,
                        data: {
                            query: text
                        },
                        dataType: 'json',
                        success: function(response) {
                            data = response;

                            $autocomplete.css({
                                left: $input.offset().left + 'px',
                                top: $input.offset().top + $input.outerHeight() + 'px',
                                width: $input.outerWidth()
                            });
                            $autocomplete.children().empty();

                            for (var i in data) {
                                $autocomplete.children().append($('<li data-value="' + data[i][_settings.value] + '">' + data[i][_settings.text] + '</li>'));
                            }

                            $autocomplete.find('li').click(function () {
                                $hidden.val($(this).data('value'));
                                $input.val($(this).text());
                                $autocomplete.hide();
                            });

                            $autocomplete.show();
                        }
                    })
                }
            }
        });
    }

    $.fn.autocomplete = function(settings) {
        this.each(function() {
            $(this).data('autocomplete', new Autocomplete($(this), settings));
        });

        return this;
    }

})(jQuery);