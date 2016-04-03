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

    2. Получение детальной информации о выбранной книге/удаление
    {
        "action" : "details" | "delete",
        "params" : {
            "id" : %число%
        }
    }

    3. Добавление / редактирование книги
    {
        "action" : "save",
        "params" : {
            "id" : %число% | "",
            "name" : %string%,
            "author" : %string%,
            "year" : %nubmer% | "",
            "description" : %string%,
            "cover" : %image(file)%
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

    public function save(Request $request)
    {
        $errors = $this->validateBook($request);
        if ($errors != null )
            return response()->json($errors, 500);

        if ($request->input('id') != null)
            $book = Book::find($request->input('id'));
        else
            $book = new Book();

        $book->name = $request->input('name');
        $book->author = $request->input('author');
        $book->year = $request->input('year');
        $book->description = $request->input('description');

        $book->save();

        if ($request->hasFile('cover'))
        {
            $imageName = $book->id . '.' .
                $request->file('cover')->extension();

            $request->file('cover')->move(
                base_path() . '/public/images/covers/', $imageName
            );

            $book->cover = '/images/covers/' + $imageName;
            $book->save();
        }


        return $book->id;

    }

    public function index(Request $request)
    {

        $q = json_decode($request->input('q'), true);
        if ($q == null) {
            return response()->json('отсутствует параметр q', 404);
        }
        if (!isset($q['action']) )
            return response()->json('отсутствует параметр action', 404);

        $answer = ''; $status = 200;

        switch ($q['action']) {
            case 'list':
                $correctParams = $this->validateListQuery($q['params']);
                $answer = Book::getBookList($correctParams);
                break;

            case 'details':
                $answer = $this->validateBookId($q['params']);
                if ($answer != null ) {
                    $status = 404;
                    break;
                }

                $answer = (new Book)->find($q['params']['id']);
                break;

            case 'delete':
                 $answer = $this->validateBookId($q['params']);
                if ($answer != null ) {
                    $status = 404;
                    break;
                }

                $answer = (new Book)->find($q['params']['id'])->delete();
                break;

            default:
                return response()->json('неправильный параметр action', 404);
                break;
        }

        return response()->json($answer, $status);
    }


    protected function validateBook($req) {
        $maxYear = date('Y') + 10; //Запас на 10 лет вперед (вдруг книга только планируется выйти)
        $rules = [
            'id' => 'exists:books,id',
            'name' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'year' => "integer|between:0,$maxYear",
            'description' => 'required|string|max:2000',
            'cover' => 'image|max:500'
        ];

        $validator = Validator::make($req->all(), $rules);

        if ($validator->fails())
            return $validator->errors()->toArray();
        else
            return null;
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

      protected function validateBookId($params) {
         $maxYear = date('Y') + 10; //Запас на 10 лет вперед (вдруг книга только планируется выйти)
         $rules = [
             'id' => 'required|integer|exists:books,id',
         ];
         $validator = Validator::make($params, $rules);

        if ($validator->fails())
            return $validator->errors()->all();
        else
            return null;
     }


}
