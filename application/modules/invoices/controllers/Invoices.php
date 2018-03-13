<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Invoices extends MX_Controller
{

function __construct() {
parent::__construct();
}

function test() {	
        // Load all views as normal
        $data['name'] = "Fadlu Haruna";
		$this->load->view('test',$data);
		// Get output html
		$html = $this->output->get_output();
		// Load library
		$this->load->library('dompdf_gen');
		// Convert to PDF
		$this->dompdf->load_html($html);
        $this->dompdf->render();
        $data['Attachment'] = FALSE;
		$this->dompdf->stream("welcome.pdf",$data);
		
	}
}