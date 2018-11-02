//0 - окно закрыто; 1  - окно открыто;
var popupStatus = 0;

// Показываем (открываем) окно, используя эффекты jQuery
function loadPopup() {
    // Открываем окно только если оно закрыто
    if (popupStatus == 0) {
        $("#backgroundPopup").css({
            "opacity": "0.7"
        });
        $("#backgroundPopup").fadeIn("slow");
        $("#popupContact").fadeIn("slow");
        popupStatus = 1;
    }
}

// Скрываем (закрываем) окно, используя эффекты jQuery
function disablePopup() {
    // Закрываем окно только если оно открыто
    if (popupStatus == 1) {
        $("#backgroundPopup").fadeOut("slow");
        $("#popupContact").fadeOut("slow");
        popupStatus = 0;
    }
}

// Окно будет расположено в центре страницы
function centerPopup() {
    // ширина и высота окна браузера
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $("#popupContact").height();
    var popupWidth = $("#popupContact").width();
    // размещаем окно в центре страницы
    $("#popupContact").css({
        "position": "absolute",
        "top": windowHeight / 2 - popupHeight / 2,
        "left": windowWidth / 2 - popupWidth / 2
    });
    // только для MS IE 6
    $("#backgroundPopup").css({
        "height": windowHeight
    });
}

// Обработчики событий
$(document).ready(function () {
    // ОТКРЫТИЕ ОКНА
    // Событие - щелчек по кнопке
    $("#button").click(function () {
        $("body").css("overflow","hidden");
        // размещаем окно в центре страницы
        centerPopup();
        // открываем окно
        loadPopup();

    });

    // ЗАКРЫТИЕ ОКНА
    // Событие - щелчок по "x"
    $("#popupContactClose").click(function () {
        // закрываем окно
        disablePopup();
    });
    // Событие - щелчок за пределами окна
    $("#backgroundPopup").click(function () {
        // закрываем окно
        disablePopup();
    });
    // Событие - нажата клавиша Escape
    $(document).keypress(function (e) {
        if (e.keyCode == 27 && popupStatus == 1) {
            // закрываем окно
            disablePopup();
        }
    });
});