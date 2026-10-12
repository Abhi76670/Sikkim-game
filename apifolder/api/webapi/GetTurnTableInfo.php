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
                $data = [
                    "rewardList" => [
                        ["rewardType" => 2, "rewardSetting" => "iPhone 15", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240912134058ei75.png"],
                        ["rewardType" => 1, "rewardSetting" => "58", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240912134759n2hk.png"],
                        ["rewardType" => 1, "rewardSetting" => "1888", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_202409102307446q6o.png"],
                        ["rewardType" => 1, "rewardSetting" => "18", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240910230748e4a3.png"],
                        ["rewardType" => 1, "rewardSetting" => "1", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_202409102307515b9v.png"],
                        ["rewardType" => 1, "rewardSetting" => "188", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240910230757q154.png"],
                        ["rewardType" => 1, "rewardSetting" => "888", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240910230801awjs.png"],
                        ["rewardType" => 1, "rewardSetting" => "8", "prizePicturesUrl" => "https://ossimgsikkim.scripthubdemo.online/sikkim/other/turntable_20240910230805j3hx.png"]
                    ],
                    "taskList" => [
                        ["taskType" => 1, "targetAmount" => 500.00, "rotateNum" => 1],
                        ["taskType" => 1, "targetAmount" => 1000.00, "rotateNum" => 1],
                        ["taskType" => 1, "targetAmount" => 2000.00, "rotateNum" => 2],
                        ["taskType" => 1, "targetAmount" => 5000.00, "rotateNum" => 6],
                        ["taskType" => 1, "targetAmount" => 10000.00, "rotateNum" => 5],
                        ["taskType" => 1, "targetAmount" => 20000.00, "rotateNum" => 5],
                        ["taskType" => 1, "targetAmount" => 50000.00, "rotateNum" => 10],
                        ["taskType" => 1, "targetAmount" => 100000.00, "rotateNum" => 20]
                    ],
                    "vipRating" => "0,1,2,3,4,5,6,7,8,9,10",
                    "memberGroup" => "0,1",
                    "bindingType" => 0
                ];
                
                $res['data'] = $data;
                $res['code'] = 0;
                $res['msg'] = 'Succeed';
                $res['msgCode'] = 0;
                http_response_code(200);
                echo json_encode($res);
            } else {
                $res['code'] = 5;
                $res['msg'] = 'Wrong signature';
                $res['msgCode'] = 3;
                http_response_code(200);
                echo json_encode($res);
            }
        } else {
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
