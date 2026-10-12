<?php
	include "../../conn.php";
			
	header('Content-Type: application/json; charset=utf-8');
	header('Strict-Transport-Security: max-age=31536000');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
	header('Access-Control-Allow-Credentials: true');
	$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
	header('Access-Control-Allow-Origin: ' . $origin);
	header('vary: Origin');
	
	date_default_timezone_set("Asia/Kolkata");
	$shnunc = date("Y-m-d H:i:s");
	$res = [
		'code' => 11,
		'msg' => 'Method not allowed',
		'msgCode' => 12,
		'serviceNowTime' => $shnunc,
	];
	$shonubody = file_get_contents("php://input");
	$shonupost = json_decode($shonubody, true);
	if ($_SERVER['REQUEST_METHOD'] != 'GET') {		
		if (isset($shonupost['language']) && isset($shonupost['random']) && isset($shonupost['signature']) && isset($shonupost['timestamp'])) {
			$language = htmlspecialchars(mysqli_real_escape_string($conn, $shonupost['language']));
			$random = htmlspecialchars(mysqli_real_escape_string($conn, $shonupost['random']));
			$signature = htmlspecialchars(mysqli_real_escape_string($conn, $shonupost['signature']));
			$shonustr = '{"language":'.$language.',"random":"'.$random.'"}';
			$shonusign = strtoupper(md5($shonustr));
			if($shonusign == $signature){
				$data[0]['url'] = '';
				$data[0]['bannerUrl'] = 'https://shubhamclub.site/banner/Aviator.jpg';
				
				$data[1]['url'] = '';
				$data[1]['bannerUrl'] = 'https://shubhamclub.site/banner/Aviator.png';
				
				$data[2]['url'] = '';
				$data[2]['bannerUrl'] = 'https://shubhamclub.site/banner/bann1.jpg';
				
				$data[3]['url'] = '';
				$data[3]['bannerUrl'] = 'https://shubhamclub.site/banner/bann.png';
				
				$data[4]['url'] = '';
				$data[4]['bannerUrl'] = 'https://shubhamclub.site/banner/Chart.jpg';
				
				$data[5]['url'] = '';
				$data[5]['bannerUrl'] = 'https://shubhamclub.site/banner/Team-leader.jpg';
				
				$data[6]['url'] = '';
				$data[6]['bannerUrl'] = 'https://shubhamclub.site/banner/Checkin.jpg';
				
				$data[7]['url'] = '';
			    $data[7]['bannerUrl'] = 'https://shubhamclub.site/banner/CONTENT.jpg';
			    
				$data[8]['url'] = '';
				$data[8]['bannerUrl'] = 'https://shubhamclub.site/banner/bann1.jpg';
			
				$data[9]['url'] = '';
				$data[9]['bannerUrl'] = 'https://shubhamclub.site/banner/Invite.png';
			
			
			
			
			
				
				
				$res['data'] = $data;
				$res['code'] = 0;
				$res['msg'] = 'Succeed';
				$res['msgCode'] = 0;
				http_response_code(200);
				echo json_encode($res);
			}
			else{
				$res['code'] = 5;
				$res['msg'] = 'Wrong signature';
				$res['msgCode'] = 3;
				http_response_code(200);
				echo json_encode($res);
			}
		}
		else{
			$res['code'] = 7;
			$res['msg'] = 'Param is Invalid';
			$res['msgCode'] = 6;
			http_response_code(200);
			echo json_encode($res);
		}		
	} else {		
		http_response_code(405);
		echo json_encode($res);
	}
?>