var files_path;
var myfile_name;


(function ($) {

    var files; // переменная. будет содержать данные файлов

// заполняем переменную данными файлов, при изменении значения file поля
    $('input[type=file]').on('change', function () {
        files = this.files;
        myfile_name = this.value;
    });

    $('.button').on('click', function (event) {

        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        // ничего не делаем если files пустой
        if (typeof files == 'undefined') return;

        // создадим данные файлов в подходящем для отправки формате
        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });

        // добавим переменную идентификатор запроса
        data.append('my_file_upload', 1);

        // AJAX запрос
        $.ajax({
            url: './imgeadd.php',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData: false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType: false,
            // функция успешного ответа сервера
            success: function (respond, status, jqXHR) {
                // Ок
                if (typeof respond.error === 'undefined') {

                }
                // error
                else {
                    console.log('ОШИБКА: ' + respond.error);
                }
            },
            // функция ошибки ответа сервера
            error: function (jqXHR, status, errorThrown) {
                console.log('ОШИБКА AJAX Запроса: ' + status, jqXHR);
            }
        });
    });

})(jQuery);
/*******************************************************************************/

$(document).ready(function () {
    $('.dell').on('click', function (event) {
        $.ajax({
            type: 'POST',
            url: 'operations.php',
            data: 'operation=ProductDell&articul=' + this.id,
            success: function (data) {
                alert("Удалено");
                location.reload();
            }
        });
    });
    //document.documentElement.clientHeight
    //document.getElementById('id_00002').offsetHeight

    let elementSum = ~~(document.documentElement.clientHeight / 500) + 2;
    let children = $('#main').children();
    let i;
    if (children.length > elementSum) {
        elementSum = elementSum * 3;
    } else {
        elementSum = children.length;
    }

    for (i = 0; i < elementSum; i++) {
        children.eq(i).css({'display': 'inline'});
    }


    window.onscroll = function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {

            if (children.length > (i + elementSum)) {
                elementSum += i;
            } else {
                elementSum = children.length;
            }

            for (i; i < elementSum; i++) {

                let currentElementId = children.eq(i).attr('id').replace(/id_/g, '');
                let currentElement = document.getElementById(currentElementId);

                let name = currentElement.dataset.name;
                let searchText = document.getElementById('search').value;
                if (name.toUpperCase().indexOf(searchText.toUpperCase()) === -1) {

                } else {
                    children.eq(i).css({'display': 'inline'});
                }

            }
        }
    };

    $(".button").click(function () {
        let el = document.getElementById(this.id);
        // let name = el.dataset.name;
        let name = document.getElementById("id_" + this.id + "txt").value;
        let price = document.getElementById(this.id + "txt").value;
        if (price !== '') {
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=ProductAdd&articul=' + this.id + '&name=' + name + '&price=' + price + '&path=' + myfile_name,
                success: function (data) {
                    alert("Добавлено");
                    location.reload();
                }
            });
        } else {
            alert("введите цену");
        }
    });
});


$('#search').on('input', function () {

    /********************************************/
    let children = $('#main').children();
    let currentElement, currentElementId, name, searchText, counter = 0;
    let elementSum = ~~(document.documentElement.clientHeight / 500) + 2;

    for (let i = 0; i < children.length; i++) {
        currentElementId = children.eq(i).attr('id').replace(/id_/g, '');
        currentElement = document.getElementById(currentElementId);
        if (currentElement) {
            name = currentElement.dataset.name;
            searchText = document.getElementById('search').value;
            if (name.toUpperCase().indexOf(searchText.toUpperCase()) === -1) {
                children.eq(i).css('display', 'none');
            } else {

                if (counter <= elementSum * 3) {
                    children.eq(i).css({'display': 'inline'});
                    counter++;
                }
            }
        }

    }
    /********************************************/
});
