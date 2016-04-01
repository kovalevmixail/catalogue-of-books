<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public static function getBookList($params) {

        if ( $params['searchQuery'] == null)
            return self::
                orderBy($params['sort']['column'], $params['sort']['type'])->
                paginate(
                    $params['booksOnPage'],
                    array('id', 'name', 'author', 'cover', 'year'),
                    'page',
                    $params['pageNumber']
                );


        return self::fullTextSearch($params['searchQuery'])->
            orderBy($params['sort']['column'], $params['sort']['type'])->
    		paginate(
                $params['booksOnPage'],
                array('id', 'name', 'author', 'cover', 'year'),
                'page',
                $params['pageNumber']
            );
    }

    public static function fullTextSearch($query) {
        return self::
            whereRaw("MATCH(name,author) AGAINST('$query*' IN BOOLEAN MODE)");
    }

    public static function getPageCountInSearchResult($searchQuery, $booksOnPage) {
        $count = self::fullTextSearch($searchQuery)->count();
        return (int)ceil( $count / $booksOnPage);
    }

    public static function getPageCount($booksOnPage) {
        $count = self::count();
        return (int) ceil($count / $booksOnPage);
    }
}
