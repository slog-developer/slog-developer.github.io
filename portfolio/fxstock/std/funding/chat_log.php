<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";
		
	$C_funding	= new Funding;
	
	$funding_idx	= $_REQUEST['funding_idx'];
	$search_type	= $_POST['search_type']; 
	$chat			= $_POST['chat'];
	
	if ($funding_idx != "")
	{
		if($search_type == "I")
		{ 
			if ($chat != "")
			{
				unset($args);
				$args['search_type']	= $search_type; 
				$args['mem_name']		= $user_info['mem_name'];
				$args['mem_idx']		= $user_info['mem_idx'];
				$args['funding_idx']	= $funding_idx;
				$args['chat']			= filterwords($chat); 

				$rtn	= $C_funding->set_chat_log($args); 

				$return_msg_array['err_code'] = 1;
				echo json_encode($return_msg_array); 

				exit;
			}
		}
		else
		{
			$_list_array = array();
			$args['funding_idx']	= $funding_idx;
			$list	= $C_funding->set_chat_log($args); 
			
			for ($i=0; $i<count($list); $i++){
				$_list_array[$i] = array(
										'mem_name' => $list[$i]['mem_name'], 
										'regdate' => $list[$i]['regdate'], 
										'chat' => $list[$i]['chat']
										);
			}
			
			echo json_encode($_list_array);
			exit;
		}
	}
?>