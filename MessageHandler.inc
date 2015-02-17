<?php

function getMessages() {

	//Fehler & Infomeldung Überprüfung aus der Session

	$messages = '';

	if(isset($_SESSION['message']['error'])) {
		foreach($_SESSION['message']['error'] as $txt) {
			$messages .= '<div class="alert alert-dismissible alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="margin-right:0.5em;"></span>
								<span>'.$txt.'</span>
							 </div>';
		}
		unset($_SESSION['message']['error']);
	}
	if(isset($_SESSION['message']['success'])) {
		foreach($_SESSION['message']['success'] as $txt) {
			$messages .= '<div class="alert alert-dismissible alert-success" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<span class="glyphicon glyphicon-ok" aria-hidden="true" style="margin-right:0.5em;"></span>
								<span>'.$txt.'</span>
							 </div>';
		}
		unset($_SESSION['message']['success']);
	}
	if(isset($_SESSION['message']['info'])) {
		foreach($_SESSION['message']['info'] as $txt) {
			$messages .= '<div class="alert alert-dismissible alert-info" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right:0.5em;"></span>
								<span>'.$txt.'</span>
							 </div>';
		}
		unset($_SESSION['message']['info']);
	}
	if(isset($_SESSION['message']['warning'])) {
		foreach($_SESSION['message']['warning'] as $txt) {
			$messages .= '<div class="alert alert-dismissible alert-warning" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
								<span>'.$txt.'</span>
							 </div>';
		}
		unset($_SESSION['message']['warning']);
	} 

	return $messages;
}

?>