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
/************************************************************************************************************************/

$(document).ready(function () {

    $(".checkbox").click(function () {
        if ($(this).is(':checked')) {
            document.getElementById(this.id.replace(/checkbox/g, '') + 'asrtQuantity').style.display = 'inline-block';
        } else {
            document.getElementById(this.id.replace(/checkbox/g, '') + 'asrtQuantity').style.display = 'none';
        }
    });


    $(".edit_button").click(function () {
        if (document.getElementById('id_' + this.id.replace(/edit/g, '') + 'img').style.display !== 'none') {
            document.getElementById('id_' + this.id.replace(/edit/g, '') + 'img').style.display = 'none';
            document.getElementById(this.id.replace(/edit/g, '') + 'editBlock').style.display = 'inline-block';
        } else {
            document.getElementById('id_' + this.id.replace(/edit/g, '') + 'img').style.display = 'inline-block';
            document.getElementById(this.id.replace(/edit/g, '') + 'editBlock').style.display = 'none';
        }

    });
    /* удаление товара с сайта */
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
    /* добавление категории*/
    $('.category_add').on('click', function (event) {
        let category = document.getElementById("category_add_txt").value;
        if (category != "") {
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=category_add&category=' + category,
                success: function (data) {
                    alert("Категория добавлена");
                }
            });
        } else alert("Введите категорию");
    });
    /* удаление категории*/
    $('.CategoryDell').on('click', function (event) {
        alert("");
        let category = document.getElementById("CategoryDllSelect").value;
        $.ajax({
            type: 'POST',
            url: 'operations.php',
            data: 'operation=category_dell&category=' + category,
            success: function (data) {
                alert("Категория удалена");
            }
        });
    });
    /* удаление закза из корзины*/
    $(".Product_Order_dellBtn").click(function () {
        let articul = this.id.replace(/DellBtn/g, '');
        let id = this.id;
        $.ajax({
            type: 'POST',
            url: 'operations.php',
            data: 'operation=OrderDell&articul=' + articul,
            success: function (data) {
                alert("Удалено");
                document.getElementById(articul).style.display = 'none'
            }
        });

    });
    /* добовление товара на сайт*/
    $(".button").click(function () {
        let price = document.getElementById(this.id + "txt").value;
        if (price !== '') {
            let el = document.getElementById(this.id);
            let name = document.getElementById("id_" + this.id + "txt").value;
            let category = document.getElementById(this.id + "_select").value;
            let description = document.getElementById("id_" + this.id + "description").value;

            let checks = document.getElementsByName(this.id + 'checkBoxName');
            if (checks) {
                for (i = 0; i < checks.length; i++) {
                    let asrtName = checks[i].value;
                    let asrt = checks[i].dataset.asrt;
                    let asrtQuantity = document.getElementById(checks[i].id.replace(/checkbox/g, 'asrtQuantity')).value;
                    if (checks[i].checked) {
                        $.ajax({
                            type: 'POST',
                            url: 'operations.php',
                            data: 'operation=ProductAsrtAdd&articul=' + this.id + '&name=' + asrtName + '&quantity=' + asrtQuantity + '&asrt=' + asrt,
                            success: function (data) {
                                console.log(data);
                            }
                        });
                    }
                }
            }
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=ProductAdd&articul=' + this.id + '&name=' + name + '&price=' + price + '&path=' + myfile_name + '&category=' + category + '&description=' + description,
                success: function (data) {
                    alert("Добавлено");
                    // location.reload();
                }
            });

        } else {
            alert("введите цену");
        }
    });
});

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

    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {

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


$(".test1").click(function () {

    if (document.getElementById('category').style.display !== 'none') {
        document.getElementById('category').style.display = 'none';
    } else {
        document.getElementById('category').style.display = 'inline-block';
    }
});


$('#search').on('input', function () {

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
});
/*изменение количества товара*/
$('.quantity').on('input', function () {
    // alert( this.id.replace(/quantity/g, ''));
    let element = document.getElementById(this.id);
    let quantity = element.value;
    let order_id = element.dataset.orderid;
    $.ajax({
        type: 'POST',
        url: 'operations.php',
        data: 'operation=order_product_quantity_change&articul=' + element.dataset.articul + '&order_id=' + order_id + '&quantity=' + quantity + '&asrt=' + element.dataset.asrt,
        success: function (data) {
            //alert("Добавлено " + quantity + " " + order_id + " ");
        }
    });
});
