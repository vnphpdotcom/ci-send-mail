<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->driver('cache');
		$email = $this->input->post('email');
		if($email)
		{
			$object = new stdClass();
			$object->$email = time();
			$this->cache->file->save('send', $object, 600);
		}
		$this->load->view('mail');
	}


	/**
	 * Send mail
	 */

	public function send()
	{
		$this->load->driver('cache');
		$this->load->library('email');
		$data = (object)$this->cache->file->get('send');
		foreach($data as $key=>$value)
		{
			if(time()-$value>=30)
			{
				$this->email->from('no-reply@localhost', 'BNC Company');
				$this->email->to($key);
				$this->email->subject('Email Test');
				$this->email->message('Testing the email class.');
				$this->email->send();
				unset($data->$key);
			}
		}
		$this->cache->file->save('send', $data,3600);
	}
}
