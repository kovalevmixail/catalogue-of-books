<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Book;

class BookController extends Controller
{

    /* Формат запросов

    1. Получение списка книг, зарегистрированных в домашней библиотеке. Должна поддерживаться возможность постраничного получения данных, сортировки и  поиска записей по названию и автору
    {
    "action" : "list",
    "params" : {
        "pageNumber" : %число% | "",
        "sort" : {
            "column" : "name" | "author" | "",
            "type" : "asc" | "desc"
            }
        "searchQuery" : %строка% | ""
        }
    }

    2. Получение детальной информации о выбранной книге
    {
        "action" : "details",
        "params" : {
            "id" : %число%
        }
    }

    ....
    */


   protected $config = [
        'listQueryAttrs' => [
            'sort' => [
                'columns' => array('name', 'author', 'id'),
                'defaultColumn' => 'id',
                'types' => array('asc', 'desc'),
                'defaultType' => 'asc'
            ],

            'pageNumber' => 1,

            'booksOnPage' => 6,

            'search' => [
                'maxlength' => '50',
                'minlength' => '3'
            ]
        ]
   ];

    public function index(Request $request)
    {
        $q = json_decode($request->input('q'), true);
        if ($q == null) {
            return abort(404, 'неправильный запрос');
        }

        switch ($q['action']) {
            case 'list':
                $correctParams = $this->validateListQuery($q['params']);
                $answer = Book::getBookList($correctParams);
                break;

            case 'details':
                $answer = (new Book)->find($q['params']['id']);
                break;

            case 'save':
                $book = new Book();
                foreach ($q['params'] as $key => $value) {
                    $book->{$key} = $value;
                }
                $book = $this->validateBook($book);
                $answer = $book->save();
                break;

            case 'delete':
                $answer = (new Book)->find($q['params']['id'])->delete();
                break;

            default:
                return abort(404, 'неправильный запрос');
                break;
        }



        return response()->json($answer);
    }



    //$p (params)
    //$c (config)
    protected function validateListQuery($p) {

        $c = $this->config['listQueryAttrs'];

        $correctParams = [
            "booksOnPage" => $c['booksOnPage'],
            "pageNumber" => $c['pageNumber'],
            "sort" => [
                "column" => $c['sort']['defaultColumn'],
                "type" => $c['sort']['defaultType']
            ],
            "searchQuery" => null
        ];

        //Валидация параметров сортировки
        if (in_array($p['sort']['column'], $c['sort']['columns']))
            $correctParams['sort']['column']= $p['sort']['column'];

        if (in_array($p['sort']['type'], $c['sort']['types']))
           $correctParams['sort']['type'] = $p['sort']['type'];


        //Валидация поискового запроса
        $p['searchQuery'] = htmlspecialchars($p['searchQuery']);
        if (strlen($p['searchQuery']) >= $c['search']['minlength'])
            $correctParams['searchQuery'] =
                mb_substr($p['searchQuery'], 0, $c['search']['maxlength']);


        //Валидация номера страницы
        if ($correctParams['searchQuery'] != null) {
            $maxPageNumber = Book::getPageCountInSearchResult(
                $correctParams['searchQuery'],
                $c['booksOnPage']
            );
        }
        else
        {
            $maxPageNumber = Book::getPageCount($c['booksOnPage']);
        }

        $p['pageNumber'] = (int)$p['pageNumber'];
        if ($p['pageNumber'] > 0 && $p['pageNumber'] <= $maxPageNumber)
            $correctParams['pageNumber'] = $p['pageNumber'];

        return $correctParams;

    }

    /*
    protected function validateBook($book) {
        $maxYear = date('Y') + 10; //Запас на 10 лет вперед (вдруг книга только планируется выйти)
        $rules = [
            'id' => 'exists:books,id',
            'name' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'year' => "integer|between:0,$maxYear",
            'description' => 'required|string|max:2000',
            'cover' => 'image|size:500'
        ];

        $validator = Validator::make($book, $rules);

        dd($validator);
        return;
    }
    */
}
