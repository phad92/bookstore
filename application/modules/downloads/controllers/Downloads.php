<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Downloads extends MX_Controller
{

function __construct() {
parent::__construct();
}

function show_books(){
    $this->load->helper('download');
    $this->load->module('store_basket');
    $this->load->module('site_security');
    $shopper_id = $this->uri->segment(3);
    // $shopper_id = strlen($shopper_id);
    // echo $shopper_id;die();

     if(is_numeric($shopper_id)){
        // echo 'false';die();
         redirect('site_security/not_allowed');
         // die("<h1>Sorry you are not allowed here</h1>");
     }

    $token =(int) $this->site_security->_get_token($shopper_id);
    
    $query = $this->store_basket->get_where_custom('shopper_id',$token);
    
    $data['query'] = $query;
    // $data['download'] = $this->_download_book($book_id);
    $data['view_file'] = 'downloads';
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

function test($book_id= null){
    $this->load->helper('download');
    $this->load->module('store_books');
    if(!is_numeric($book_id)){
        die();
    }
    $query = $this->store_books->fetch_data_from_db($book_id);
     print_r($query);die();
    $format = $query['book_format'];
    $book = $query['book'];
    if($format === 'audio'){
        $book_path = 'public/books/audio/'.$book;
    }elseif($format === 'digital'){
        $book_path = 'public/books/files/'.$book;
    }
   
    if(!file_exists($book_path)){
        echo 'file does not exist';die();
    }
    force_download($book_path, NULL);
}

function download_book($session_token){
    $this->load->helper('download');
    $this->load->library('zip');
    $this->load->module('store_books');
    $this->load->module('store_shoppertrack');
    $this->load->module('store_basket');
    $this->load->module('checkout');

    $this->load->module('site_security');
    $session_id = $this->site_security->_get_token($session_token);
    $shopper_id = $this->checkout->_get_shopper_id();
    
    $basket = $this->store_shoppertrack->get_where_custom('session_id',$session_id);
    // $basket = $this->store_basket->get_where_custom('shopper_id',$shopper_id);
    $query = $basket->result();
    $num_rows = $basket->num_rows();

    if($num_rows > 0){        
        foreach($query as $row){
            $this->zip->add_data($row->book);
            // $zip_file = $this->zip->get_zip();
            if($row->format == 'audio'){
                $book_path = 'public/books/audio/'.$row->book;
            }elseif($row->format == 'digital'){
                $book_path = 'public/books/files/'.$row->book;
                
            }
            if($row->book == '' OR !file_exists($book_path)){
                echo 'file does not exist';die();
            }

            $book[] = $book_path;
        }
        
        $this->zip->clear_data();
        foreach($book as $b){
            $this->zip->read_file($b); // Read the file's contents

        }

        $this->zip->download('myphoto.zip');

    }










}


































}