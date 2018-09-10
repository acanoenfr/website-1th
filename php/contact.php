<?php

	$array = array("firstname" => "", "name" => "", "email" => "", "phone" => "", "subject" => "", "message" => "", "firstnameError" => "", "nameError" => "", "emailError" => "", "phoneError" => "", "subjectError" => "", "messageError" => "", "isSuccess" => false);

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$array['firstname'] = test_input($_POST["firstname"]);
		$array['name'] = test_input($_POST["name"]);
		$array['email'] = test_input($_POST["email"]);
		$array['phone'] = test_input($_POST["phone"]);
		$array['subject'] = test_input($_POST["subject"]);
		$array['message'] = test_input($_POST["message"]);
		$array['isSuccess'] = true;
		$emailText = "";

		if(empty($array['firstname'])){
			$array['firstnameError'] = "Je veux connaître ton prénom !";
			$array['isSuccess'] = false;
		}

		if(empty($array['name'])){
			$array['nameError'] = "Et oui je veux tout savoir. Même ton nom !";
			$array['isSuccess'] = false;
		}

		if(!isEmail($array['email'])){
			$array['emailError'] = "T'essaies de me rouler ? C'est pas un email ça !";
			$array['isSuccess'] = false;
		}else{
			$emailText .= "E-mail : {$array['email']}\r\n";
		}

		if(!isPhone($array['phone'])){
			$array['phoneError'] = "Que des chiffres et des espaces, stp...";
			$array['isSuccess'] = false;
		}else{
			$emailText .= "Téléphone : {$array['phone']}\r\n";
		}

		if(empty($array['subject'])){
			$array['subjectError'] = "Tu veux que je fasse quoi ?";
			$array['isSuccess'] = false;
		}

		if(empty($array['message'])){
			$array['messageError'] = "Qu'est-ce que tu veux me dire ?";
			$array['isSuccess'] = false;
		}else{
			$emailText .= "Message : {$array['message']}";
		}

		if($array['isSuccess']){
			ini_set('SMTP', 'mail.ovh.net');
			$headers .= "From: {$array['firstname']} {$array['name']} <{$array['email']}>\r\n";
			$headers .= "Reply-To: <{$array['email']}>\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/plain; charset=UTF-8";
			@mail("contact@acanoen.fr", $array['subject'], $emailText, $headers);
		}

		echo json_encode($array);
	}

	function isPhone($phone){
		return preg_match("/^[0-9 ]*$/", $phone);
	}

	function isEmail($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

?>