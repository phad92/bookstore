<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mail extends MX_Controller
{

function __construct() {
parent::__construct();
$this->load->library('email');
}

function send($string){

    // $config['smtp_host'] = 'smtp.mail.yahoo.com.';
    $config['smtp_host'] = 'smtp-mail.outlook.com';
    // $config['smtp_host'] = 'smtp.gmail.com';
	$config['smtp_port'] = 587;
	$config['smtp_user'] = 'fadlu.haruna@hotmail.com';
	// $config['smtp_user'] = 'fadlu.haruna@gmail.com';
	// $config['smtp_user'] = 'fadl_1992@yahoo.com';
	$config['smtp_pass'] = 'june21992';//hotmail
	// $config['smtp_pass'] = 'oct71992';//gmail
	// $config['smtp_pass'] = 'June21996';//yahoo
	$config['smtp_crypto'] = 'tls'; //FIXED
	// $config['smtp_auto_tls'] = false; // Whether to enable TLS encryption automatically if a server supports it, even if `smtp_crypto` is not set to ‘tls’.
	$config['protocol'] = 'smtp'; //FIXED
	// $mail_config['mailpath'] = '/usr/bin/sendmail'; //FIXED
    $config['mailtype'] = 'html'; //FIXED
    $config['send_multipart'] = FALSE;
     $config['newline'] = "\r\n";
    //  $config['crlf'] = "\r\n";

    $this->email->initialize($config);

    $this->email->from('fadlu.haruna@hotmail.com', 'Fadlu Haruna');
    // $this->email->from('fadl_1992@yahoo.com', 'Fadlu Haruna');
    $this->email->to('fadlu.haruna1@gmail.com');
    // $this->email->cc('another@another-example.com');
    // $this->email->bcc('them@their-example.com');

    $this->email->subject('Email Test');
    $message = str_replace('{content}',$this->email_text($link),$this->email_template());
    $this->email->message($message);
    if($this->email->send()){
		echo "E-mail has been sent. Thank you.";
	}else{
		echo $this->email->print_debugger();
	}
    
}


function email_template(){
		$template = '';
		$template .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$template .= '<html xmlns="http://www.w3.org/1999/xhtml">';
		$template .= '<head>';
				$template .= '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
				$template .= '<style>';
				$template .= 'body{margin:0;padding:0;min-width:100% !important};';
				$template .= '.content{width:100%;max-width:600px};';
				$template .= '</style>';
				$template .= '</head>';
			$template .= '<body yahoo bgcolor="#ffffff">';
        	$template .= '<table width="100%" bgcolor="#ffffff" border=0 cellpadding="0" cellspacing="0">';
            	$template .= '<tr>';
                	$template .= '<td>';
                    	$template .= '<table class="content" align="center" cellpadding="0" cellspacing="0">';
                        	$template .= '<tr>';
                            	$template .= '<td>';
                                	$template .= '<table width="600" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
                                    	$template .= '<tr>';
											$template .= '<td>';
												$template .= '<table class="content" align="center" cellpadding="0" cellspacing="0" border="0">';
                                                	$template .= '<tr>';
                                                    	$template .= '<td bgcolor="#4285f4">';
                                                        	$template .= '<font color="#ffffff" face="Arial" size="4">Codeigniter Email Test</font>';
                                                    $template .= '</td>';
                                                $template .= '</tr>';
                                                $template .= '<tr>';
                                                    $template .= '<td>';
                                                        $template .= '<font color="#000000" face="Arial" size="2.5">{content}</font>';
                                                    $template .= '</td>';
                                                $template .= '</tr>';
												$template .= '<tr>';
												$template .= '<td bgcolor="#4285f4">';
                                                        $template .= '<font color="#ffffff" face="Arial" size="2">&copy; info@projectark.com '.date('Y').'</font>';
                                                    $template .= '</td>';
                                                $template .= '</tr>';
                                            $template .= '</table>';
                                        $template .= '</td>';
                                    $template .= '</tr>';
								$template .= '</table>';
							$template .= '</td>';
                        $template .= '</tr>';
                    $template .='</table>';
                $template .= '</td>';
            $template .= '</tr>';
        $template .= '</table>';
    $template .= '</body>';
	$template .= '</html>';
	return $template;			
}

function email_text($link){
	$text = '<font color="#ffffff" face="Arial" size="15">Thank You</font>';
		$text .=  '<h1>Codeigniter Email Test</h1><p>Lorem ipsum dolor sit amet consectetur, 
			adipisicing elit. Aliquam asperiores repellat eveniet. Fugiat ex natus, 
			aperiam velit maiores adipisci minus quo sequi perspiciatis ut sit iure quis, 
			dolor eaque architecto.</p> click here<a href="'.$link.'">confirmation link</a><p>Lorem ipsum dolor sit amet consectetur, 
			adipisicing elit. Aliquam asperiores repellat eveniet. Fugiat ex natus, 
			aperiam velit maiores adipisci minus quo sequi perspiciatis ut sit iure quis, 
			dolor eaque architecto.</p>';
	}   

function get($order_by) {
$this->load->model('Mdl_mail');
$query = $this->Mdl_mail->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_mail');
$query = $this->Mdl_mail->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_mail');
$query = $this->Mdl_mail->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_mail');
$query = $this->Mdl_mail->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_mail');
$this->Mdl_mail->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_mail');
$this->Mdl_mail->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_mail');
$this->Mdl_mail->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_mail');
$count = $this->Mdl_mail->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_mail');
$max_id = $this->Mdl_mail->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_mail');
$query = $this->Mdl_mail->_custom_query($mysql_query);
return $query;
}

}