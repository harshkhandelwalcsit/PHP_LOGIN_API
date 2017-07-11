<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once('vendor/autoload.php');   
include_once 'connection.php';
	
	class User {
		
		private $db;
		private $connection;
				
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
	
		}
		public function does_user_exist($email,$encrypted_password)
		{
		//	print_r($email);
            $query = "Select * from users where email='$email' and password = '$encrypted_password' ";
			$result = mysqli_query($this->connection, $query);
			if(mysqli_num_rows($result)>0){
							$header = [
                  'typ' => 'JWT',
                  'alg' => 'HS256'
        ];
$header = json_encode($header);
	$header = base64_encode($header);
	$payload = ["email" => $email, "password" =>$encrypted_password]; 
	$payload = json_encode($header);		
	$payload = base64_encode($header); 

    $key="harsh";
    $signature = hash_hmac('sha256','$header.$payload', $key, true);

    $signature = base64_encode($signature);

			$token = "$header.$payload.$signature";


				$json['token'] = $token;
				$json['success'] = ' Welcome '.$email;
				echo json_encode($json);
				mysqli_close($this -> connection);
				
			}else{
										$json['Error'] = 'User already exists';
										
				echo json_encode($json);
			}
			
		}
		
	}
	
	
	 $user = new User();
		$data = json_decode(file_get_contents("php://input")); 

//  print_r($data);exit;
	if(isset($data->email,$data->password)) {
		$email = $data->email;
		// print_r($email);
		$password = $data->password;
		// print_r($password);
		if(!empty($email) && !empty($password)){
			// echo "hi";
			$encrypted_password = md5($password);
			// print_r($encrypted_password);
			$user-> does_user_exist($email,$encrypted_password);
			
		}else{
			echo json_encode("you must type both inputs");
		}
		
	}
	// else{
	// 	echo "error ";
	// }
	














?>