<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Список книг</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <script src="bower_components/angular/angular.js"></script>

</head>

<body>


    <div class="container">
        <!--       В форме должны быть поля: автор, год издания, название книги, кол-во страниц. В списке - автор и название книги.-->
        <div class="row">
            <div class="col-md-5">
                <h3 id="form-header" class="text-center">Добавление книги</h3>
                <form role="form" id='spisok-knig-form' onsubmit="bookListApp.saveBook(bookListApp.getBookFromForm()); return false;">
                    <input type="hidden" name="id" id="id-field" value="">
                    <div class="form-group">
                        <label for="author-field">Автор</label>
                        <input required maxlength="50" type="text" class="form-control" name="author" id="author-field" placeholder="Автор" value="">
                    </div>
                    <div class="form-group">
                        <label for="year-field">Год издания</label>
                        <input required type="number" min="0" max="" class="form-control" name="year" id="year-field" placeholder="Год издания" value="">
                    </div>
                    <div class="form-group">
                        <label for="name-field">Название книги</label>
                        <input required maxlength="70" type="text" class="form-control" name="name" id="name-field" placeholder="Название книги" value="">
                    </div>
                    <div class="form-group">
                        <label for="pageCount-field">Количество страниц</label>
                        <input required type="number" min="0" max="99999" class="form-control" name="pageCount" id="pageCount-field" placeholder="Количество страниц" value="">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Добавить книгу</button>
                    <button onclick="bookListApp.turnToAdd()" type="reset" class="btn btn-warning" style="float: right;">Очистить форму</button>

                </form>
            </div>
            <div class="col-md-7">
                <h3 class="text-center">Список книг</h3>
                <table class="table table-hover" style="max-width:720px;">
                    <thead>
                        <tr>
                            <th>Автор</th>
                            <th>Название книги</th>
                            <th class="icon-cell"><span class="glyphicon glyphicon-pencil"></span></th>
                            <th class="icon-cell"><span class="glyphicon glyphicon-remove"></span></th>
                        </tr>
                    </thead>
                    <tbody id="spisok-knig-table"></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        //Вспомогательные функции

        function isNumeric(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        String.prototype.replaceAll = function (search, replace) {
            return this.split(search).join(replace);
        }
    </script>

    <script type="text/javascript" src="js/Book.js"></script>
    <script type="text/javascript" src="js/BookListApp.js"></script>
    <script type="text/javascript" src="js/deafaulBooks.js"></script>

    <script>
        //активируем BookListApp
        var bookListApp = new BookListApp();
        bookListApp.init();
        bookListApp.DOMForm.year.max = new Date().getFullYear();
    </script>
</body>

</html>
