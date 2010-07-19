<?php
class Blog extends Controller 
{

	function __construct()
	{
		parent::Controller();
		$this->load->model('blog_model');
		require_once APPPATH.'libraries/MarkdownExtra.php';
		require_once APPPATH.'libraries/SmartypantsTypographer.php';
		$this->load->helper('date');
	}
	
	function index()
	{
		$data->published = $this->blog_model->GetPublished();
		if ($data->published) {
			foreach ($data->published as $value) {
				$data->entries->$value = $this->blog_model->GetEntry($value);
			}
		}
		$data->views = $this->blog_model->AddView();
		$this->load->view('blog_index', $data);
	}

	function feed()
	{
		$data->published = $this->blog_model->GetPublished();
		$data->updated = 0;
		if ($data->published) {
			foreach ($data->published as $value) {
				$data->entries->$value = $this->blog_model->GetEntry($value);
				if ($data->entries->$value->updated > $data->updated) $data->updated = $data->entries->$value->updated;
			}
		}
		header("Content-Type: application/atom+xml");
		$this->load->view('blog_feed', $data);
	}
	
	function testpublish() {
		$entry->id = "test-post";
		$entry->title = "Test Post";
		$entry->summary = "This is the summary of the test post";
		$entry->created = now();
		$entry->updated = now();
		$entry->body = 'This is the body of the test post. Woot.';
		$this->blog_model->SetEntry($entry->id, $entry);
		$this->blog_model->PublishEntry($entry->id, now());
	}
	
	function ajax($function, $id=NULL)
	{
		// This needs some security, and also needs to be validated against all blog_model methods
		if (!$id) {
			echo json_encode($this->blog_model->$function());
		} else {
			if (!$this->input->post('data')) {
		 		echo json_encode($this->blog_model->$function($id));
		 	} else {
		 		echo json_encode($this->blog_model->$function($id, $this->input->post('data')));
		 	}
 		}
	}
	
	function entry($id) 
	{
		$data->entry = $this->blog_model->GetEntry($id);
		if (!$data->entry) show_404();
		$data->views = $this->blog_model->AddView($id);
 		//$data['tags'] = $this->blog_model->GetTags($id);
 		$this->load->view('blog_entry', $data);
	}
	
	function edit($id=NULL) 
	{
/* 		$this->load->library('session'); */
		$this->load->view('blog_edit', $id);
	}
	
	function preview()
	{
		$data->id = $this->input->post('id');
		$data->title = $this->input->post('title');
		$data->body = $this->input->post('body');				
    	$data->permalink = $config['base_url']."blog/entry/".$data->id;
    	$data->views->total = 321;
		$data->views->unique = 123;
		$data->entry = $data;
		$this->load->view('blog_entry', $data);
	}
	
}

?>