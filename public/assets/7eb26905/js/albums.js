(function($) {
    $(document).ready(function () {
        var ajax_blocked = false;
        //добавляем обработчик кликов ко всем картинкам,
        //когда документ загружен в синхронном режиме
        setImagesEngine();
        $(document).ajaxComplete(function (event, xhr, settings) {
            //добавляем обработчик кликов ко всем картинкам,
            //когда часть документа перезагружена в асинхронном режиме,
            //причем это был запрос на добавление к контенту следующего блока фото
            //или только что загруженных фото
            //console.log(settings);
            if(settings.url.indexOf('page=') != -1 || settings.url.indexOf('_pjax=') != -1){
                console.log(0);
                ajax_blocked = true;
                setTimeout(setImagesEngine, 1500);
            }
            if(settings.url.indexOf('_pjax=') != -1 && settings.url.indexOf('w4') != -1 && ajax_blocked == false) {
                console.log(1);
                //ajax_blocked = true;
                setTimeout(function () {
                    console.log(2);
                    $('#ajax_last_albums').click();
                }, 1500);
            } else if(settings.url.indexOf('_pjax=') != -1 && settings.url.indexOf('w1') != -1  && ajax_blocked == false) {
                console.log(3);
                //ajax_blocked = true;
                setTimeout(function () {
                    $('#ajax_all_albums').click();
                }, 1500);
            }
        });
        //добавляем обработчик клика по кнопке "Все альбомы"
        /*$('a#ajax_all_albums').click(function () {
            $(this).hide();
        });*/
        $('a#ajax_all_albums').show();
    });
    //процедура прикрепления обработчика кликов к картинкам,
    //имеющимся на странице в данный момент
    function setImagesEngine () {
        //клик по фотографии
        $("[id *= photo_id]").off('click');
        $("[id *= photo_id]").click(function (e) {
            var id = $(this).context.dataset['id'];
            //асинхронно запрашиваем фото
            $.ajax({
                // url: "http://localhost/public/index.php?r=albums/get-photo",
                url: "http://localhost/index.php?r=albums/get-photo",
                type: 'POST',
                dataType: 'json',
                data: ("id=" + id),
                success: function (data, textStatus, jqXHR) {
                    //после получения ответа показываем картинку
                    var response_data = JSON.parse(data);
                    var singleImgContainer = $('#single_img_container');
                    //создаем див-контейнер для фотографии и дополнительных элементов
                    var imgDiv = $('<div>');
                    imgDiv.css('float', 'left');
                    var img = $('<img>');
                    img.attr('src', 'images/' + response_data.file_name + '_367_247.png');
                    img.appendTo(imgDiv);

                    var imgDescDiv = $('<div>');
                    imgDescDiv.attr('id', 'img_div');
                    imgDescDiv.text(response_data.description !== ""
                        ? response_data.description
                        : "Редактировать описание");
                    imgDescDiv.width(367);
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
                    //асинхронно запрашиваем удаление фото по его ID
                    imgDeleteHref.click(function () {
                        $.ajax({
                            // url: "http://localhost/public/index.php?r=albums/delete-photo",
                            url: "http://localhost/index.php?r=albums/delete-photo",
                            type: 'POST',
                            dataType: 'text',
                            data: ("id=" +id),
                            success: function (data) {
                                var deleting_response_data = JSON.parse(data);
                                console.log(deleting_response_data.newCoverFileName);
                                console.log(deleting_response_data.albumId);
                                //после получения ответа об успешном удалении показываем текст
                                // в текущей модальной форме
                                singleImgContainer.text('Фото удалено');
                                //при закрытии формы
                                $('#show_photo div.modal-header button.close').click(function () {
                                    var albumId = deleting_response_data.album_id;
                                    var newCoverFileName = deleting_response_data.new_cover_file_name;
                                    var album = $('#album' + albumId);
                                    //console.log(albumId);
                                    //console.log(data);
                                    //console.log(deleting_response_data);
                                    //console.log(deleting_response_data.newCoverFileName);
                                    //console.log(deleting_response_data.albumId);
                                    if(album.length > 0){
                                        album.val('src', newCoverFileName);
                                    }
                                    //вызываем клик по скрытой кнопке асинхронного обновления блока фото
                                    /*setTimeout(function () {
                                        $('#ajax_photos').click();
                                    }, 1000);*/
                                    /*if($('#ajax_last_albums') && $('#ajax_all_albums')){
                                        setTimeout(function () {
                                            $('#ajax_last_albums').click();
                                            setTimeout(function () {
                                                $('#ajax_all_albums').click();
                                            }, 500);
                                        }, 500);
                                    }*/
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
                                imgDescTextArea.width(367);
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
                                // url: "http://localhost/public/index.php?r=albums/save-photo-description",
                                url: "http://localhost/index.php?r=albums/save-photo-description",
                                type: 'POST',
                                dataType: 'text',
                                data: ("id=" + imgDescTextArea.attr('id').replace('img_desc_txt_area', '') + "&value=" + imgDescTextArea.val())
                            });
                        }
                    });
                }
            });
        });
        ajax_blocked = false;
    }
})(jQuery);