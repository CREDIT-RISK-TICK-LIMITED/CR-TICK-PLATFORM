<?php
	if(preg_match('/local/', $_SERVER['HTTP_HOST'])){
		// die('in');
    	echo file_get_contents('https://tickconsulting.co.ke');
	}else{
		// echo 'No landing page on local please go to login.';
		redirect(site_url('login'));
	}
?>