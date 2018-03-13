<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Sort extends MX_Controller
{

function __construct() {
parent::__construct();
$this->load->module('store_books');
}

function _get_popular_books(){

    $mysql_query = "SELECT * FROM store_books WHERE ";
}

function _get_books_by_format($format,$use_limit = true){
    $mysql_query = "SELECT
                    store_books.book_title,
                    store_books.book_url,
                    store_books.book_price,
                    store_books.book_qty,
                    store_books.book_format,
                    store_books.book_description,
                    store_book_author.`name`
                    FROM
                    store_books
                    INNER JOIN store_book_assign_author ON store_books.id = store_book_assign_author.book_id
                    INNER JOIN store_book_author ON store_book_author.id = store_book_assign_author.author_id
                    WHERE
                    store_books.book_format = '$format'
                ";
    $query = $this->store_books->_custom_query($mysql_query);
    return $query;
}
    function _generate_mysql_query($use_limit,$format){
        $mysql_query = "
            SELECT * 
            FROM store_books";
        if($use_limit === TRUE){
            $limit = $this->get_limit();
            $offset = $this->get_offset(3);
            $mysql_query .= " limit ".$offset.", ".$limit;
        }
        return $mysql_query;
    }

    function get_limit(){
        $limit = 30;
        return $limit;
    }

    function get_offset($offset_value = 0){
        $offset = $this->uri->segment($offset_value);
        if(!is_numeric($offset)){
            $offset = 0;
        }
        // print($offset);die();
        return $offset;
    }

}