<?
include_once getenv("DOCUMENT_ROOT")."/_common.php";
include_once FX_PATH."/CLASS/Member.lib.php";
include_once FX_PATH."/CLASS/Message.lib.php";

$C_member	= new Member;
$C_message	= new Message;
 
$rsq_type				= v3chk($_POST['rsq_type'], "", 1);
$auth_type				= v3chk($_POST['auth_type'], "", 5);
$mem_id					= v3chk($_POST['mem_id'], "", 40);
$auth_num				= v3chk($_POST['auth_num'], "", 10);
$auth_target_compare	= v3chk($_POST['auth_target_compare'], "", 128);
$msg_code				= v3chk($_POST['msg_code'], "", 2);
$receive_phone			= v3chk($_POST['receive_phone'], "", 30);
$receive_country		=  "82";  //v3chk($_POST['receive_country'], "", 6);
$sms_body				= v3chk($_POST['sms_body'], "", 100);
$isJoin					= v3chk($_POST['isJoin'], "", 1);	//2018-10-11 jPark ADD
 
$return_msg_array		= array();

	//인증 번호를 요청할 때 인증용 key를 생성
	if ($rsq_type == "R")
	{
		switch ($auth_type)
			{
				case "email":		//이메일 인증일 경우 영문자,숫자 조합 6자리
					$pwd_value	= array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
					for($i = 0; $i < 6; $i++){
						$rand_num	= array_rand($pwd_value);
						$new_pwd	.= $pwd_value[$rand_num];
					}
					break;

				case "phone":		//핸드폰 인증일 경우 숫자 6자리
					$new_pwd		= mt_rand(111111, 999999);
					break;

				default:
					$new_pwd		= "0";
					break;
			}
	}

	//인증을 하거나(C), 인증번호를 요청할 때(R)
	switch ($rsq_type)
	{
		case "R":

			unset($args);
			$args['auth_target_compare']	= $auth_target_compare;
			$args['mem_id']					= $mem_id;
			$args['auth_type']				= $auth_type;
			$args['new_pwd']				= $new_pwd;
			$args['receive_country']		= $receive_country;

			//메시지코드가 6일땐 메일 중복 체크
			if ($msg_code == "6")
			{
				//2018-10-11 jPark Modify		
				$args['search_type']	= "email"; 
				$args['mem_id']			= $auth_target_compare; 

				$cnt	= $C_member->get_user_chk($args);

				if ($cnt > 0)
				{
					$return_msg_array['result']	= "fail";
					$return_msg_array['msg']	= "이미 사용중인 email 주소입니다.";

					echo json_encode($return_msg_array);

					break;
					exit;
				}
			}

			//2018-10-12 jPark : 인증번호 발송 카운트 - 3회까지
			if ($isJoin == 1 && $auth_type == "phone")
			{
				$rstCnt = $C_member->get_user_join__authcnt($args); 

				if(intval($rstCnt) == 3)
				{
					$return_msg_array['result']	= "fail";
					$return_msg_array['msg']	= "인증 횟수 제한을 초과하였습니다.";
					echo json_encode($return_msg_array);
					break;
				}
			}

			//인증번호 삽입
			$rst = $C_member->auth_request($args); 

			if ($msg_code != "" && $auth_type == "email")
			{
				//메시지코드가 16이 아니면서 메일발송시
				if ($msg_code != "16")	$_dp_message_args['mail_yn']= "Y";

				$send_date		= date("Y년 m월 d일 H:i");
				$auth_avail_date= date("Y년 m월 d일 H:i",strtotime("+1 hour", strtotime(date("Y-m-d H:i"))));
				$_dp_message_args['msg_code']		= $msg_code;
				$_dp_message_args['receive_mem_id'] = $args['mem_id'];
				$_dp_message_args['receive_email']	= $args['auth_target_compare'];
				$_dp_message_args['hot_key'] = array("##auth_num##"=>$args['new_pwd'],"##send_date##"=>$send_date,"##auth_avail_date##"=>$auth_avail_date);
				@$C_message->set_message_sending($_dp_message_args);

			}
			elseif ($auth_type == "phone")
			{ 
				$_login_message_args['receive_mem_id'] = $args['mem_id'];
				$_login_message_args['msg_code'] = $msg_code;
				$_login_message_args['hot_key'] = array("##auth_num##"=>$args['new_pwd']);
				$_login_message_args['sms_yn'] = "Y";
				if ($receive_country) $_login_message_args['receive_country'] = $receive_country;
				if ($receive_phone) $_login_message_args['receive_phone'] = $receive_phone;
				if ($sms_body) $_login_message_args['sms_body'] = $sms_body;
				@$C_message->set_message_sending($_login_message_args);
			}
			
			if ($rst > 0)
			{
				$return_msg_array['result']	= "success";
				$return_msg_array['msg']	= "인증번호가 발송되었습니다.[".$new_pwd."]";
			}
			else
			{
				//2018-10-02 jPark Modify 
				$return_msg_array['result']	= "fail";
				if ($rst == "-1001")
				{
					$return_msg_array['msg']	= "인증 횟수 제한을 초과하였습니다.";
				}
				else
				{
					$return_msg_array['msg']	= "인증에 실패하였습니다.";
				}
			}
			echo json_encode($return_msg_array);
			break;

		case "C":

			unset($args);
			$args['auth_target_compare']	= $auth_target_compare;
			$args['mem_id']					= $mem_id;
			$args['auth_type']				= $auth_type;
			$args['auth_num']				= $auth_num;

			$rst							= $C_member->auth_compare($args);
			
			if ($rst > 0)
			{
				$args['auth_idx']			= $rst;
				$rst						= $C_member->auth_complete($args);
 
				if ($isJoin == 1 and $auth_type == "email")
				{
					$_SESSION["REG_MEM_ID"] = $auth_target_compare;
				}
				if ($isJoin == 1 and $auth_type == "phone")
				{
					$_SESSION["REG_MEM_HP"] = $auth_target_compare;
				} 

				$return_msg_array['result']	= "success";
				$return_msg_array['msg']	= "인증이 완료 되었습니다.";
			}
			else
			{
				$return_msg_array['result']	= "fail";
				$return_msg_array['msg']	= "인증에 실패하였습니다.\n인증번호를 다시 확인해주세요.";
			}
			echo json_encode($return_msg_array);

			break;

		default:
			break;
	}
?>