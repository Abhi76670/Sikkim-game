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
				$data[0]['id'] = 1;
				$data[0]['typeNameCode'] = 9301;
				$data[0]['categoryCode'] = 'Lottery';
				$data[0]['categoryName'] = '电子游戏';
				$data[0]['state'] = 1;
				$data[0]['sort'] = 7;
				$data[0]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_20240620140153mtqe.png';
				
				$data[1]['id'] = 2;
				$data[1]['typeNameCode'] = 9308;
				$data[1]['categoryCode'] = 'Flash';
				$data[1]['categoryName'] = '彩票';
				$data[1]['state'] = 1;
				$data[1]['sort'] = 6;
				$data[1]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_202406201400361ydn.png';
				
				$data[2]['id'] = 4;
				$data[2]['typeNameCode'] = 9304;
				$data[2]['categoryCode'] = 'Slot';
				$data[2]['categoryName'] = '体育游戏';
				$data[2]['state'] = 1;
				$data[2]['sort'] = 5;
				$data[2]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_20240620140203bwnl.png';
				
				$data[3]['id'] = 3;
				$data[3]['typeNameCode'] = 9303;
				$data[3]['categoryCode'] = 'Fish';
				$data[3]['categoryName'] = '视讯游戏';
				$data[3]['state'] = 1;
				$data[3]['sort'] = 4;
				$data[3]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_2024062014004596yn.png';
				
				$data[4]['id'] = 7;
				$data[4]['typeNameCode'] = 9307;
				$data[4]['categoryCode'] = 'Chess';
				$data[4]['categoryName'] = '棋牌游戏';
				$data[4]['state'] = 1;
				$data[4]['sort'] = 3;
				$data[4]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_20240620140121tl1j.png';
				$data[5]['id'] = 6;
				$data[5]['typeNameCode'] = 9306;
				$data[5]['categoryCode'] = 'Video';
				$data[5]['categoryName'] = '捕鱼游戏';
				$data[5]['state'] = 1;
				$data[5]['sort'] = 2;
				$data[5]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_20240620140130i8ej.png';
				$data[6]['id'] = 5;
				$data[6]['typeNameCode'] = 9305;
				$data[6]['categoryCode'] = 'Sport';
				$data[6]['categoryName'] = '小游戏';
				$data[6]['state'] = 1;
				$data[6]['sort'] = 1;
				$data[6]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_20240620140139642m.png';
				
				$data[7]['id'] = 2;
				$data[7]['typeNameCode'] = 9302;
				$data[7]['categoryCode'] = 'Popular';
				$data[7]['categoryName'] = '热门游戏';
				$data[7]['state'] = 1;
				$data[7]['sort'] = 0;
				$data[7]['categoryImg'] = 'https://ossimgsikkim.scripthubdemo.online/sikkim/gamecategory/gamecategory_202406201400247n7o.png';
				
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