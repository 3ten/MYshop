/*
 * Author -- Матюхин Всеволод(3ten)
 *
 * Инструкция
 * 1) Подключить скрипт <script src="search.js"></script>
 * 2) у поиска прописать oninput="searchInput(this,'.exampleClass')"  параметры: this, класс элемента по которому нужен поиск
 * Для скрытия/показа иконок при нажатии на поиск нужно:
 * 1) Подключить скрипт <script src="search.js"></script>
 * 2) прописать у кнопки открытия поиска onClick="view_search(this)"
 * 3) у всех иконок которые нужно скрыть прописать класс "notSearchIco"
 */

/***************************************/

/*Поиск*/
let DocDisplay;

async function searchInput(search, block) {
    let doc_text,
        search_text = search.value;
    $(block).each(function () {
        let doc = this;
        if (getComputedStyle(doc, null).display !== 'none') {
            DocDisplay = getComputedStyle(doc, null).display;
        }
        doc_text = doc.textContent;
        if (doc_text.toUpperCase().indexOf(search_text.toUpperCase()) === -1) {
            doc.style.display = 'none';
        } else {
            doc.style.display = DocDisplay;
        }
    });
}

