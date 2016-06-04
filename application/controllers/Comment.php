<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('book_model');
        $this->load->model('comment_model');
    }
    public function index()
	{
		$data['url']='blank_page';
		$data['title']='Blank Page';
		//table data
		$this->load->view('main',$data);
	}
    public function book_comment($ISBN)
	{
		$data['url']='comment/book_comment';
		$data['title']='Book Comment';
		$data['book']=$this->book_model->get_bookdata_by_ISBN($ISBN);
		$data['comment']=$this->comment_model->get_commentdata_by_ISBN($ISBN);
		$data['my_comment']=$this->comment_model->appointed_commentdata($ISBN);
		//table data
		$this->load->view('main',$data);
	}
	public function ajax_add_comment($ISBN)
    {
		$this->_ajax_add_comment_validate();
		$data=array(
			'student_number'=>$this->session->userdata('studentNumber'),
			'ISBN'=>$ISBN,
			'comment'=>$this->input->post('comment'),
			'is_anonymous'=>$this->input->post('is_anonymous')?1:0,
			); 
		// echo "<script>alert('dfsf');</script>";
		// echo json_encode(array("error"=>'is_anonymous为空!'));
		// exit();
        
		$affect=$this->comment_model->save($data);
		echo json_encode(array("status"=>TRUE));
	}
	private function _ajax_add_comment_validate()
    {
        $this->form_validation->set_rules('comment','comment','required');
		if ($this->form_validation->run()==FALSE)
        {
            echo json_encode(array("error"=>validation_errors()));
            exit();
        }
    }
}