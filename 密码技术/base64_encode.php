<?php
	
	$file = './base64.jpg';
	$data = file_get_contents($file);
	echo base64_encode($data);
	



	
