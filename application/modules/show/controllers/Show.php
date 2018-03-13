<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Show extends MX_Controller
{

    function __construct() {
        parent::__construct();
    }

    function book(){
        //find item id
        // echo 'hello';die();
        $book_url = $this->uri->segment(3);
        $this->load->module('store_books');
        $book_id = $this->store_books->_get_book_id_from_book_url($book_url);
        // echo $cat_id;die();
        $this->store_books->view($book_id);
    } 

    
    
    function category(){
        //find item id
        // echo 'hello';die();
        $cat_url = $this->uri->segment(3);
        $this->load->module('store_book_categories');
        $cat_id = $this->store_book_categories->_get_cat_id_from_cat_url($cat_url);
        // echo $cat_id;die();
        $this->store_book_categories->view($cat_id);
    }

    function author(){
        //find item id
        // echo 'hello';die();
        $author_url = $this->uri->segment(3);
        $this->load->module('store_book_author');
        $cat_id = $this->store_book_categories->_get_author_id_from_author_url($author_url);
        // echo $cat_id;die();
        $this->store_book_categories->view($cat_id);
    }
}