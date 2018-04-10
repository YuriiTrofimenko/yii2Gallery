(function($) {
    $(document).ready(function () {
        //добавляем обработчик кликов ко всем картинкам,
        //когда документ загружен в синхронном режиме
        setImagesEngine();
        $(document).ajaxComplete(function (event, xhr, settings) {
            //добавляем обработчик кликов ко всем картинкам,
            //когда часть документа перезагружена в асинхронном режиме,
            //причем это был запрос на добавление к контенту следующего блока фото
            //или только что загруженных фото
            if(settings.url.indexOf('page=') != -1 || settings.url.indexOf('_pjax=') != -1){
                setTimeout(setImagesEngine, 500);
            }
        });
        //добавляем обработчик клика по кнопке "Все альбомы"
        $('a#ajax_all_albums').click(function () {
            $(this).hide();
        });
    });
    //процедура прикрепления обработчика кликов к картинкам,
    //имеющимся на странице в данный моменты
    function setImagesEngine () {
        //клик по фотографии
        $("[id *= photo_id]").off('click');
        $("[id *= photo_id]").click(function (e) {
            var id = $(this).context.dataset['id'];
            //асинхронно запрашиваем фото
            $.ajax({
                url: "http://abram-world.loc/index.php?r=albums/get-photo",
                type: 'POST',
                dataType: 'json',
                data: ("id=" + id),
                success: function (data, textStatus, jqXHR) {
                    var response_data = JSON.parse(data);
                    var singleImgContainer = $('#single_img_container');
                    //создаем див-контейнер для фотографии и дополнительных элементов
                    var imgDiv = $('<div>');
                    imgDiv.css('float', 'left');
                    var img = $('<img>');
                    img.attr('src', '/images/' + response_data.file_name + '_360_360.png');
                    img.appendTo(imgDiv);

                    var imgDescDiv = $('<div>');
                    imgDescDiv.attr('id', 'img_div');
                    imgDescDiv.text(response_data.description !== ""
                        ? response_data.description
                        : "Редактировать описание");
                    imgDescDiv.width(360);
                    imgDescDiv.height(100);
                    imgDescDiv.css('margin-top', 10);
                    //imgDesc.attr('id', response_data['id']);
                    imgDescDiv.appendTo(imgDiv);

                    //ссылка: удалить фото
                    var imgControlsDiv = $('<div>');
                    imgControlsDiv.css('float', 'left');
                    var imgDeleteHref = $('<a>');
                    imgDeleteHref.text('Удалить');
                    imgDeleteHref.appendTo(imgControlsDiv);
                    imgDeleteHref.click(function () {
                        $.ajax({
                            url: "http://abram-world.loc/index.php?r=albums/delete-photo",
                            type: 'POST',
                            dataType: 'text',
                            data: ("id=" +id),
                            success: function () {
                                singleImgContainer.text('Фото удалено');
                                $('div.modal-header button.close').click(function () {
                                    $('#ajax_photos').click();
                                });
                            }
                        });
                    });
                    //imgDeleteHref.attr('href', 'javascript:void(0)');

                    //загружаем содержимое в окно просмотра фотографии
                    singleImgContainer.html(imgDiv);
                    imgControlsDiv.appendTo(singleImgContainer);

                    //клик по какому-либо элементу страницы
                    $('body').click(function (e) {

                        var imgDescTextAreaDiv;
                        var imgDescTextArea;
                        //если клик по диву с описанием картинки
                        if($(e.target).closest(imgDescDiv).length !== 0){
                            imgDescDiv.hide();
                            //если текстовое поле описания еще не было создано
                            if(!$("div").is("#img_desc_text_area_div")) {
                                imgDescTextAreaDiv = $('<div>');
                                imgDescTextAreaDiv.attr('id', 'img_desc_text_area_div');
                                imgDescTextArea = $('<textarea>');
                                imgDescTextArea.width(360);
                                imgDescTextArea.height(100);
                                imgDescTextArea.css('margin-top', 10);
                                imgDescTextArea.text(response_data.description);
                                imgDescTextArea.attr('id', 'img_desc_txt_area' + response_data['id']);
                                imgDescTextArea.appendTo(imgDescTextAreaDiv);
                                imgDescTextAreaDiv.appendTo(imgDiv);
                                //если текстовое поле описания ранее было создано
                            } else {
                                $('#img_desc_text_area_div').show();
                                $('#img_desc_text_area_div textarea').text(imgDescDiv.text() !== "Редактировать описание"
                                    ? imgDescDiv.text()
                                    : "");
                            }
                            //если клик не по текстовому полю описания картинки, но оно существует и не скрыто
                        } else if ($(e.target).closest($('#img_desc_text_area_div')).length === 0
                            && $("div").is("#img_desc_text_area_div")
                            && !($('#img_desc_text_area_div')).is(':hidden')) {
                            var imgDescTextArea = $('#img_desc_text_area_div textarea');
                            ($('#img_desc_text_area_div')).hide();
                            $('#img_div').show();
                            $('#img_div').text((imgDescTextArea).val() !== ""
                                ? imgDescTextArea.val()
                                : "Редактировать описание");
                            //асинхронно обновляем описание картинки в БД
                            $.ajax({
                                url: "http://abram-world.loc/index.php?r=albums/save-photo-description",
                                type: 'POST',
                                dataType: 'text',
                                data: ("id=" + imgDescTextArea.attr('id').replace('img_desc_txt_area', '') + "&value=" + imgDescTextArea.val())
                            });
                        }
                    });
                }
            });
        });
    }
})(jQuery);