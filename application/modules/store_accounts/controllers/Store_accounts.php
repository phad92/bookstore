<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_accounts extends MX_Controller
{
    
    function __construct() {
        parent::__construct();
    }

    function _generate_guest_account(){
        //customer clicked no thanks bottom
        $this->load->module('store_accounts');
        $ref = $this->site_security->generate_random_string(4);
        $customer_session_id = $this->session->session_id;
        // $customer_session_id = $this->_get_session_id_from_token($checkout_token);
        //create guest acount
        $data['username'] = 'Guest-'.$ref;
        $data['firstname'] = "Guest";
        $data['lastname'] = "Account";
        $data['date_made'] = time();
        $data['pword'] = 'password';

        $this->store_accounts->_insert($data);
        $new_account_id = $this->store_accounts->get_max();

        //update existing store_basket table
        $mysql_query = "UPDATE store_basket SET shopper_id = '$new_account_id' WHERE session_id = '$customer_session_id'";
        $query = $this->store_accounts->_custom_query($mysql_query);
    }

    // function submit_choice(){
    //     $submit = $this->input->post('submit',TRUE);
    //     $this->load->module('store_accounts');
    //     $checkout_token = $this->input->post('checkout_token',TRUE);
    //     if($submit == 'No'){
    //         $this->store_accounts->_generate_guest_account($checkout_token);
    //         redirect('checkout/index/'.$checkout_token);
    //     }else{
    //         redirect('your_account/start');
    //     }
    // }

    function _get_shopper_address($update_id,$delimiter){
        //returns the address of the shopper
        $data = $this->fetch_data_from_db($update_id);
        $address = '';

        if($data['address1'] != ''){
            $address .= $data['address1'];
            $address .= $delimiter;
        }

        if($data['address2'] != ''){
            $address .= $data['address2'];
            $address .= $delimiter;
        }
        if($data['town'] != ''){
            $address .= $data['town'];
            $address .= $delimiter;
        }
        if($data['country'] != ''){
            $address .= $data['country'];
            $address .= $delimiter;
        }
        if($data['postcode'] != ''){
            $address .= $data['postcode'];
            $address .= $delimiter;
        }

        return $address;
    }

    function _make_sure_delete_allowed($update_id){
        //returns TRUE OR FALSE

        //DO NOT ALLOWED DELETE IF SHOPPER HAS ITEMS IN BASKET OR IN SHOPPER_TRACK TABLE
        $mysql_query = "SELECT * FROM store_basket WHERE shopper_id = $update_id";
        $query = $this->_custom_query($mysql_query);
        $num_rows = $query->num_rows();

        if($num_rows > 0){
            return FALSE; //delete not allowed since has items in basket
        }else{
            $mysql_query = "SELECT * FROM store_shoppertrack WHERE shopper_id = $update_id";
            $query = $this->_custom_query($mysql_query);
            $num_rows = $query->num_rows();

            if($num_rows > 0){
                return FALSE; //SHOPPER MUST HAVE MADE A PURCHASE
            }
        }
    
        return TRUE;
    }

    function check_accounts_for_user($shopper_id){
        
        if(!is_numeric($shopper_id)){
            die();
        }
        if($shopper_id == 0){
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => FALSE)));
        }
        
        $mysql_query = "SELECT * FROM store_accounts WHERE id = ?";
        $query = $this->db->query($mysql_query, array($shopper_id));
        $user = $query->row();

        if(empty($user->email)){
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => FALSE)));
            
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => TRUE)));
        
    }
    
    function deleteconf($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }

        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        $submit = $this->input->post('submit');
        if($submit == 'Cancel'){
            redirect('store_accounts/create/'.$update_id);
        }
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        

        // $data['view_module'] = "blog";
        $data['view_file'] = "deleteconf";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function delete($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit');
        if($submit == 'Cancel'){
            redirect('store_accounts/create/'.$update_id);
        }else {
            $allowed = $this->_make_sure_delete_allowed($update_id);
            if($allowed == FALSE){
                $flash_msg = "You are not allowed to Delete this Account";
                $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
                $data['flash'] = $this->session->flashdata('item',$value);
                redirect('store_accounts/manage');
            }
            $this->_delete($update_id);
            
            $flash_msg = "Store Account Entry Delete Successfull";
            $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
            $data['flash'] = $this->session->flashdata('item',$value);
            redirect('store_accounts/manage');
        }
    }

    function _generate_token($update_id){
        $data = $this->fetch_data_from_db($update_id);
        $date_made = $data['date_made'];
        $last_login = $data['last_login'];
        $pword = $data['pword'];

        $pword_length = strlen($pword);
        $start_point = $pword_length-6;
        $last_six_chars = substr($pword,$start_point,6);

        if(($pword_length > 5) AND ($last_login > 0)){
            $token = $last_six_chars.$date_made.$last_login;
        }else{
            $token = "";
        }

        return $token;
    }

    function _get_customer_id_from_token($token){
        $last_six_chars = substr($token, 0, 6);//last six chars from stored pword
        $date_made = substr($token, 6, 10);
        $last_login = substr($token, 16, 10);
        $mysql = "SELECT * FROM store_accounts WHERE date_made = ? AND pword LIKE ? AND last_login = ?";
        $query = $this->db->query($mysql, array($date_made, '%'.$last_six_chars,$last_login));
        foreach($query->result() as $row){
            $customer_id = $row->id;
        }

        if(!isset($customer_id)){
            $customer_id = 0;
        }

        return $customer_id;
    }

    function _get_customer_name($update_id,$optional_customer_data = null){
        if(isset($optional_customer_data)){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data['firstname'] = $optional_customer_data['firstname'];
            $data['lastname'] = $optional_customer_data['lastname'];
            $data['company'] = $optional_customer_data['company'];
        }
        if($data = ""){
            $customer_name = "Unknown";
        }else{
            $data = $this->fetch_data_from_db($update_id);
            // print_r($data);die();
            $firstname = trim(ucfirst($data['firstname']));
            $lastname = trim(ucfirst($data['lastname']));
            $company = trim(ucfirst($data['company']));

            $company_length = strlen($company);
            if($company_length > 2){
                $customer_name = $company;
            }else{
                $customer_name = $firstname.' '.$lastname;
            }
        }
        return $customer_name;
    }
    
    
    function create(){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        
        $submit = $this->input->post('submit',true);
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[240]');
            $this->form_validation->set_rules('firstname', 'First Name', 'required|max_length[240]');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                if(is_numeric($update_id)){
                    //update the item detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Account updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_accounts/create/'.$update_id);
                }else{
                    // inser new item
                    $data['date_made'] = time();
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new item
                    $flash_msg = "Account Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('store_accounts/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('store_accounts/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            $data['big_pic'] = '';
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Account";
        }else{
            $data['headline'] = "Update Account Details";
        }
        
        
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }
    
    function update_pword($update_id){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $submit = $this->input->post('submit');
        if(!is_numeric($update_id)){
            redirect('store_accounts/manage');
        }elseif($submit == "Cancel"){
            //redirect to create account page
            redirect('store_accounts/create/'.$update_id);
        }
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('pword', 'Password', 'required|min_length[6]|max_length[35]');
            $this->form_validation->set_rules('repeat_pword', 'Repeat Password', 'required|matches[pword]');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                
                $pword = $this->input->post('pword');
                $this->load->module('site_security');
                $data['pword'] = $this->site_security->_hash_string($pword);
                //update the item detail
                $this->_update($update_id,$data);
                $flash_msg = "Account successfully updated";
                $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                $this->session->set_flashdata('item', $value);
                redirect('store_accounts/create/'.$update_id);
                
            }
        }
        $data['update_id'] = $update_id;
        $data['headline'] = "Update Account Password";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "update_pword";
        $this->load->module('templates');
        $this->templates->admin($data);
    }
    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $data['query'] = $this->get('lastname');
        
        //$data['view_module'] = "store_items";
        $data['headline'] = "Manage Account";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }


    function fetch_data_from_post(){
        $data['username'] = $this->input->post('username');
        $data['firstname'] = $this->input->post('firstname');
        $data['lastname'] = $this->input->post('lastname');
        $data['company'] = $this->input->post('company');
        $data['address1'] = $this->input->post('address1');
        $data['address2'] = $this->input->post('address2');
        $data['town'] = $this->input->post('town');
        $data['country'] = $this->input->post('country');
        $data['postcode'] = $this->input->post('postcode');
        $data['telnum'] = $this->input->post('telnum');
        $data['email'] = $this->input->post('email');
        return $data;
    }

    function _get_shopper_by_email($email){
        $mysql_query = "SELECT * FROM store_accounts WHERE email = $email";
        $query = $this->_custom_query($mysql_query);
        $user = $query->row();
        return $user;
    }

    function autogen(){
        $mysql_query = "SHOW columns FROM store_accounts";
        $query = $this->_custom_query($mysql_query);
        foreach($query->result() as $row){
            $column = $row->Field;
            if($column != "id"){
                $html = '<div class="control-group">
                  <label class="control-label" for="'.$column.'">'.ucfirst($column).'</label>
                  <div class="controls">
                    <input type="text" class="span6" id="'.$column.'" name="'.$column.'" value="<?php echo $'.$column.'?>">
                  </div>
                </div>';
                echo htmlentities($html);
                echo '</br>';
            }
        }
    }

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data["username"] = $row->username;
            $data["firstname"] = $row->firstname;
            $data["lastname"] = $row->lastname;
            $data["company"] = $row->company;
            $data["address1"] = $row->address1;
            $data["address2"] = $row->address2;
            $data["town"] = $row->town;
            $data["country"] = $row->country;
            $data["postcode"] = $row->postcode;
            $data["telnum"] = $row->telnum;
            $data["email"] = $row->email;
            $data['date_made'] = $row->date_made;
            $data['pword'] = $row->pword;
            $data["last_login"] = $row->last_login;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_store_accounts');
        $query = $this->Mdl_store_accounts->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_store_accounts');
        $query = $this->Mdl_store_accounts->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_store_accounts');
        $query = $this->Mdl_store_accounts->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_store_accounts');
        $query = $this->Mdl_store_accounts->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_store_accounts');
        $this->Mdl_store_accounts->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_store_accounts');
        $this->Mdl_store_accounts->_update($id, $data);
    }

    function _delete($id) {
    $this->load->model('Mdl_store_accounts');
    $this->Mdl_store_accounts->_delete($id);
    }

    function count_where($column, $value) {
    $this->load->model('Mdl_store_accounts');
    $count = $this->Mdl_store_accounts->count_where($column, $value);
    return $count;
    }

    function get_max() {
    $this->load->model('Mdl_store_accounts');
    $max_id = $this->Mdl_store_accounts->get_max();
    return $max_id;
    }

    function _custom_query($mysql_query) {
    $this->load->model('Mdl_store_accounts');
    $query = $this->Mdl_store_accounts->_custom_query($mysql_query);
    return $query;
    }

}