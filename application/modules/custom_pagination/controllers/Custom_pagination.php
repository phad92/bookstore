<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Custom_pagination extends MX_Controller
{

    function __construct() {
    parent::__construct();
    }

    function _generate_pagination($data){
        $this->load->library('pagination');
        
        $target_base_url = $data['base_url'];
        
        $total_rows = $data['total_rows'];
        $offset_segment = $data['offset_segment'];
        
         print('offset: '.$offset_segment);
         print('total rows: '.$total_rows);
         print('<br>total rows: '.$offset_segment);
         print('<br>url: '.$target_base_url);
        $limit = $data['limit'];
        if($data['template'] == "public_bootstrap"){
            $settings = $this->_get_settings_for_public_bootstrap();
            
        }else{
            $settings = $this->_get_settings_for_admin();
        }
        $config['base_url'] = $target_base_url;
        $config['uri_segments'] = $offset_segment;
        $config['total_rows'] = $total_rows;
        // $config['uri_segment'] =  10;

        $config['per_page'] =  $limit;
        $config['num_links'] =  $settings['num_links'];

        $config['full_tag_open']  = $settings['full_tag_open'];
        $config['full_tag_close']  = $settings['full_tag_close'];

        $config['cur_tag_open']  = $settings['cur_tag_open'];
        $config['cur_tag_close']  = $settings['cur_tag_close'];

        $config['num_tag_open']  = $settings['num_tag_open'];
        $config['num_tag_close']  = $settings['num_tag_close'];
        
        $config['first_link']  = $settings['first_link'];
        $config['first_tag_open']  = $settings['first_tag_open'];
        $config['first_tag_close']  = $settings['first_tag_close'];

        $config['last_link']  = $settings['last_link'];
        $config['last_tag_open']  = $settings['last_tag_open'];
        $config['last_tag_close']  = $settings['last_tag_close'];

        $config['prev_link']  = $settings['prev_link'];
        $config['prev_tag_open']  = $settings['prev_tag_open'];
        $config['prev_tag_close']  = $settings['prev_tag_close'];

        $config['next_link']  = $settings['next_link'];
        $config['next_tag_open']  = $settings['next_tag_open'];
        $config['next_tag_close']  = $settings['next_tag_close'];
                
        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        // $pagination = $new;
        // echo json_encode($pagination);
        return $pagination;
    }
    

    // function test($data){
    //     $this->load->library('pagination');
    //     $target_base_url = base_url('store_books/all_books');
    //     // $target_base_url = $data['target_base_url'];
    //     $total_rows = $data['total_rows'];
    //     $offset_segment = $data['offset_segment'];
    //     $limit = $data['limit'];
    //         $config['num_links'] = 2;
	// 		$config['base_url'] = $target_base_url;
    //         $config['uri_segments'] = $offset_segment;
    //         $config['total_rows'] = $total_rows;
            
            
	// 		$config['use_page_numbers'] = TRUE;
    //         $config['reuse_query_string'] = TRUE;	
            
	// 		$config['full_tag_open'] = '<div class="pagination">';
	// 		$config['full_tag_close'] = '</div>';	
	// 		$config['first_link'] = 'First Page';
	// 		$config['first_tag_open'] = '<span class="firstlink">';
	// 		$config['first_tag_close'] = '</span>';	
	// 		$config['last_link'] = 'Last Page';
	// 		$config['last_tag_open'] = '<span class="lastlink">';
	// 		$config['last_tag_close'] = '</span>';	
	// 		$config['next_link'] = 'Next Page';
	// 		$config['next_tag_open'] = '<span class="nextlink">';
	// 		$config['next_tag_close'] = '</span>';

	// 		$config['prev_link'] = 'Prev Page';
	// 		$config['prev_tag_open'] = '<span class="prevlink">';
	// 		$config['prev_tag_close'] = '</span>';

	// 		$config['cur_tag_open'] = '<span class="curlink">';
	// 		$config['cur_tag_close'] = '</span>';

	// 		$config['num_tag_open'] = '<span class="numlink">';
    //         $config['num_tag_close'] = '</span>';
            
    //         $this->pagination->initialize($config);

    //         $pagination = $this->pagination->create_links();
    //         return $pagination;
    // }
    function _get_settings_for_public_bootstrap(){
            // $settings['per_page'] = 30;
            $settings['num_links'] = 5;
    
            // $settings['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
            $settings['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
            $settings['full_tag_close'] = '</ul></nav>';
    
            $settings['cur_tag_open'] = '<li class="disabled"><a href="#">';
            $settings['cur_tag_close'] = '</a></li>';
    
            $settings['num_tag_open'] = '<li>';
            $settings['num_tag_close'] = '</li>';
            
            $settings['first_link'] = 'First';
            $settings['first_tag_open'] = '<li>';
            $settings['first_tag_close'] = '</li>';
            
            $settings['last_link'] = 'Last';
            $settings['last_tag_open'] = '<li>';
            $settings['last_tag_close'] = '</li>';

            $settings['prev_link'] = '<span aria-hidden="true">&laquo</span>';
            $settings['prev_tag_open'] = '<li>';
            $settings['prev_tag_close'] = '</li>';

            $settings['next_link'] = '<span aria-hidden="true">&raquo</span>';
            $settings['next_tag_open'] = '<li>';
            $settings['next_tag_close'] = '</li>';
            return $settings;
    }

    



    
    function get_showing_statement($data){
    
    }
    
    function _get_settings_for_admin(){
        $settings['per_page'] = 20;
        $settings['num_links'] = '';
    
        $settings['full_tag_open'] = '';
        $settings['full_tag_close'] = '';
    
        $settings['cur_tag_open'] = '';
        $settings['cur_tag_close'] = '';
    
        $settings['num_tag_open'] = '';
        $settings['num_tag_close'] = '';
            
        $settings['first_link'] = '';
        $settings['first_tag_open'] = '';
        $settings['first_tag_close'] = '';
            
        $settings['last_link'] = '';
        $settings['last_tag_open'] = '';
        $settings['last_tag_close'] = '';
            
        $settings['prev_link'] = '';
        $settings['prev_tag_open'] = '';
        $settings['prev_tag_close'] = '';
            
        $settings['next_link'] = '';
        $settings['next_tag_open'] = '';
        $settings['next_tag_close'] = '';
        return $settings;
    }
}
// function get_settings_for_public_bootstrap(){
//         $settings['per_page'] = 20;
//         $settings['num_links'] = '';

//         $settings['full_tag_open'] = '';
//         $settings['full_tag_close'] = '';

//         $settings['cur_tag_open'] = '';
//         $settings['cur_tag_close'] = '';

//         $settings['num_tag_open'] = '';
//         $settings['num_tag_close'] = '';
        
//         $settings['first_link'] = '';
//         $settings['first_tag_open'] = '';
//         $settings['first_tag_close'] = '';
        
//         $settings['last_link'] = '';
//         $settings['last_tag_open'] = '';
//         $settings['last_tag_close'] = '';
        
//         $settings['prev_link'] = '';
//         $settings['prev_tag_open'] = '';
//         $settings['prev_tag_close'] = '';
        
//         $settings['next_link'] = '';
//         $settings['next_tag_open'] = '';
//         $settings['next_tag_close'] = '';
//         return $settings;
// }