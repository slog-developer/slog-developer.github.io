<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member	= new Member;
	$C_funding	= new Funding;

	$err_code   = '-1001';
	if($user_info['mem_division'] != "3") {	//권한 체크
		$err_code = '-1002';
	} 

	$flag						= $_POST['flag']; 
	$mode						= $_POST['mode']; 

	if($flag == "step2"){
	
		$funding_top_memo			= $_POST['txt_top_memo'];
		$funding_img_name			= $_POST['hid_filename'];
		$funding_type				= $_POST['ddl_funding_type']; // 1: 주식형, 2: 채권형
		$funding_type_level			= $_POST['ddl_funding_invest_type']; // 투자 단계 0 : Seed, 1: Sereise A, 2: Sereise B, 3: Sereise C 

		if($mode == "U")
		{
			$funding_type				= $_POST['hid_funding_type']; // 1: 주식형, 2: 채권형
			$funding_type_level			= $_POST['hid_funding_type_level']; // 투자 단계 0 : Seed, 1: Sereise A, 2: Sereise B, 3: Sereise C 
		} 

		//Default Parameter
		$stock_share_rate			= 0;			//발행 지분율
		$stock_minimum_rate			= 0;			//최저 배당율
		$stock_tot_cnt				= 0;			//총 발행 주식수
		$stock_per_amount			= 0;			//주 당 금액
		$stock_receive_dt			= null;			//증권 입고일

		$bond_receive_dt			= null;			//채권 만기일
		$bond_account_per_amount	= 0;			//구좌 당 금액
		$bond_year_rate				= 0;			//연 이자율
		$bond_account_cnt			= 0;			//발행 구좌수

		if($funding_type == 1){
			// 주식형
			$funding_type_detail		= $_POST['ddl_funding_type_detail_stock'];		// 발행 상세 선택
			$funding_price_total		= $_POST['txt_issue_stock_price_total'];		//총 발행 금액
			$funding_invest_amount		= $_POST['txt_issue_stock_invest_amount'];		// 주식/채권 최소 투자금액
			$funding_invest_cnt			= $_POST['txt_issue_stock_invest_cnt'];			// 주식 최소 투자 주식/채권 최소 투자 구좌
			$funding_company_value		= $_POST['txt_issue_stock_company_value'];		//현재 기업가치
			$funding_start_dt			= $_POST['txt_issue_stock_startDt'];			//펀딩 시작일
			$funding_end_dt				= $_POST['txt_issue_stock_endDt'];				//펀딩 종료일
			
			$stock_share_rate			= $_POST['txt_issue_stock_share_rate'];			//발행 지분율
			$stock_minimum_rate			= $_POST['txt_issue_stock_minimum_rate'];		//최저 배당율
			$stock_tot_cnt				= $_POST['txt_issue_stock_totCnt'];				//총 발행 주식수
			$stock_per_amount			= $_POST['txt_issue_stock_per_amount'];			//주 당 금액
			$stock_receive_dt			= $_POST['txt_issue_stock_receive_dt'];			//증권 입고일
			
			//전환 청구 기간
			$stock_switch_claim_start_dt	= $_POST['txt_issue_stock_switch_claim_startDt'];
			$stock_switch_claim_end_dt		= $_POST['txt_issue_stock_switch_claim_endDt'];

			//전환 조건
			$stock_switch_condition_1		= $_POST['txt_issue_stock_switch_condition_1'];
			$stock_switch_condition_2		= $_POST['txt_issue_stock_switch_condition_2'];

			//상환 청구 기간
			$stock_payback_start_dt			= $_POST['txt_issue_stock_payback_startDt'];
			$stock_payback_end_dt			= $_POST['txt_issue_stock_payback_endDt'];

			if($mode == "U")
			{ 
				$funding_type_detail	= $_POST['hid_funding_type_detail']; 
			}

			if($funding_type_detail == "1" || $funding_type_detail == "2"){
				// 보통/우선주
				$stock_switch_claim_start_dt = "";
				$stock_switch_claim_end_dt = "";
				$stock_switch_condition_1 = 0;
				$stock_switch_condition_2 = 0;
				$stock_payback_start_dt = "";
				$stock_payback_end_dt = "";
			}
			else if($funding_type_detail == "3"){
				// 상환우선주
				$stock_switch_claim_start_dt = "";
				$stock_switch_claim_end_dt = "";
				$stock_switch_condition_1 = 0;
				$stock_switch_condition_2 = 0;
			}
			else if($funding_type_detail == "4"){
				// 전환우선주
				$stock_payback_start_dt = "";
				$stock_payback_end_dt = "";
			}

		}
		else if($funding_type == 2){
			// 채권형 
			$funding_type_detail		= $_POST['ddl_funding_type_detail_bond'];		// 발행 상세 선택
			$funding_price_total		= $_POST['txt_issue_bond_price_total'];			//총 발행 금액
			$funding_invest_amount		= $_POST['txt_issue_bond_invest_Amount'];		// 주식/채권 최소 투자금액
			$funding_invest_cnt			= $_POST['txt_issue_bond_invest_cnt'];			// 주식 최소 투자 주식/채권 최소 투자 구좌
			$funding_company_value		= $_POST['txt_issue_bond_company_value'];		//현재 기업가치
			$funding_start_dt			= $_POST['txt_issue_bond_startDt'];				//펀딩 시작일
			$funding_end_dt				= $_POST['txt_issue_bond_endDt'];				//펀딩 종료일

			$bond_receive_dt			= $_POST['txt_issue_bond_receive_dt'];			//채권 만기일
			$bond_account_per_amount	= $_POST['txt_issue_bond_account_per_amount'];	//구좌 당 금액
			$bond_year_rate				= $_POST['txt_issue_bond_year_rate'];			//연 이자율
			$bond_account_cnt			= $_POST['txt_issue_bond_account_cnt'];			//발행 구좌수
			$bond_receive_dt			= $_POST['txt_issue_bond_receive_dt'];			//채권형 만기일 

			if($mode == "U")
			{ 
				$funding_type_detail	= $_POST['hid_funding_type_detail']; 
			}
		}	

		$isTax_deduction	= 	$_POST['chk_agree'];	//소득공제혜택 대상 기업

		if($isTax_deduction == "")
		{
			$isTax_deduction = "0";
		}
		

		//사업 포인트
		for ($ii = 1; $ii <= 5; $ii++){ 
			$arr_business_point_title[$ii] = $_POST["txt_business_point_title_".$ii];
			$content	= $_POST["txt_business_point_content_".$ii];  
			$arr_business_point_content[$ii] = str_replace(array('applet','vbscript','script','javascript','meta','xml', 'java'), array('','','','','','',''), $content);
		}
		

		//펀딩이야기 & 펀딩 인터뷰
		for($ii=1;$ii<=9;$ii++){
			$content	= $_POST["bc_contents_".$ii]; 
			$arr_funding_story[$ii] = str_replace(array('applet','vbscript','script','javascript','meta','xml', 'java'), array('','','','','','',''), $content);
		} 
		  
		$data_args = array( 
						"mem_idx"=>$user_info['mem_idx'],
						"funding_top_memo"=>$funding_top_memo,
						"funding_img_name"=>$funding_img_name,
						"funding_type"=>$funding_type,
						"funding_type_detail"=>$funding_type_detail,
						"funding_type_level"=>$funding_type_level,
						"funding_price_total"=>str_replace(",", "", $funding_price_total),
						"funding_invest_amount"=>str_replace(",", "", $funding_invest_amount),
						"funding_invest_cnt"=>str_replace(",", "", $funding_invest_cnt),
						"funding_company_value"=>str_replace(",", "", $funding_company_value),
						"stock_share_rate"=>str_replace(",", "", $stock_share_rate),
						"stock_minimum_rate"=>str_replace(",", "", $stock_minimum_rate),
						"stock_tot_cnt"=>str_replace(",", "", $stock_tot_cnt),
						"stock_per_amount"=>str_replace(",", "", $stock_per_amount),
						"stock_receive_dt"=>$stock_receive_dt,
						"stock_switch_claim_start_dt"=>$stock_switch_claim_start_dt,
						"stock_switch_claim_end_dt"=>$stock_switch_claim_end_dt,
						"stock_switch_condition_1"=>$stock_switch_condition_1,
						"stock_switch_condition_2"=>$stock_switch_condition_2,
						"stock_payback_start_dt"=>$stock_payback_start_dt,
						"stock_payback_end_dt"=>$stock_payback_end_dt,
						"bond_account_per_amount"=>str_replace(",", "", $bond_account_per_amount),
						"bond_year_rate"=>$bond_year_rate,
						"bond_account_cnt"=>str_replace(",", "", $bond_account_cnt),
						"bond_receive_dt"=>$bond_receive_dt,
						"isTax_deduction"=>$isTax_deduction,
						"business_point_title"=>$arr_business_point_title,
						"business_point_content"=>$arr_business_point_content,
						"funding_story"=>$arr_funding_story
						);   

		
		if($funding_type == 1)
		{
			$data_args['remain_tot_cnt'] = str_replace(",", "", $stock_tot_cnt); 
		}
		else
		{
			$data_args['remain_tot_cnt'] = str_replace(",", "", $bond_account_cnt); 
		}

		if($mode == "U")
		{
			unset($args);
			$args['funding_issue_status']	= "1";
			$args['mem_idx']				= $user_info['mem_idx'];
			$args['list_type']				= "row";
			$funding_view = $C_funding->get_funding_issue($args);
			$funding_idx = $funding_view['funding_idx'];

			$data_args['funding_idx'] = $funding_idx; 

			$err_code = $C_funding->set_funding_issue_step2_update($data_args);
		}
		else
		{ 
			$err_code = $C_funding->set_funding_issue_step2_insert($data_args);
		}
	}
	else if($flag == "step3"){
		
		unset($args);

		$args['mem_idx']	= $user_info['mem_idx'];
		$args['list_type']	= "row";

		$funding_view = $C_funding->get_funding_issue($args);
		$funding_idx = $funding_view['funding_idx']; 

		for ($ii = 1; $ii <= 7; $ii++){ 
			
			$content	= $_POST["bc_contents_".$ii]; 
			$arr_step3_msg[$ii] = str_replace(array('applet','vbscript','script','javascript','meta','xml', 'java'), array('','','','','','',''), $content);

			$arr_step3_who[$ii] = $_POST["txt_Answer_".$ii];
		}
		 
		$step3_isCeo_msg	= 	$_POST['chk_ceo_agree']; 

		if($step3_isCeo_msg == "")
		{
			$step3_isCeo_msg = "0";
		} 

		$data_args = array( 
			"funding_idx"=>$funding_idx,
			"step3_contents_1_isChk"=>$step3_isCeo_msg,
			"step3_msg"=>$arr_step3_msg,
			"step3_who"=>$arr_step3_who
		);
		
		$err_code = $C_funding->set_funding_issue_step3($data_args);
	}
	
	else if($flag == "step4"){
		$banner_v1		= 	$_POST['banner_v1'];   
		$banner_v2		= 	$_POST['banner_v2']; 
		$banner_v3		= 	$_POST['banner_v3']; 
		
		if($banner_v1 == ""){
			$banner_v1 = "0";
		} 
		
		if($banner_v2 == ""){
			$banner_v2 = "0";
		} 
		
		if($banner_v3 == ""){
			$banner_v3 = "0";
		} 

		$template_v1	= 	$_POST['template_v1']; 
		
		unset($args);
		$args['mem_idx']	= $user_info['mem_idx'];
		$args['list_type']	= "row";
		$args['funding_issue_status'] = "1";
		$funding_view = $C_funding->get_funding_issue($args);
		$funding_idx = $funding_view['funding_idx']; 

		$data_args = array( 
			"mem_idx"=>$user_info['mem_idx'],
			"funding_idx"=>$funding_idx,
			"funding_template"=>$template_v1,
			"banner_main_isUse"=>$banner_v1,
			"banner_1dan_isUse"=>$banner_v2,
			"banner_3dan_isUse"=>$banner_v3
		);

		$err_code = $C_funding->set_funding_issue_step4($data_args);
	}
	else if($flag == "del"){
		
		$funding_issue_status = $_POST['funding_issue_status']; 
		unset($args);
		$args['mem_idx']	= $user_info['mem_idx'];
		$args['list_type']	= "row";
		$args['funding_issue_status'] = $funding_issue_status;
		$funding_view = $C_funding->get_funding_issue($args);
		$funding_idx = $funding_view['funding_idx'];  

		unset($args);
		$args['funding_idx']	= $funding_idx;
		$err_code = $C_funding->set_funding_issue_delete($args);
	}

	$rtn['err_code']		= $err_code; 
	$_SESSION['isEditor']	= 0; 
 
	echo json_encode($rtn);  
?>  