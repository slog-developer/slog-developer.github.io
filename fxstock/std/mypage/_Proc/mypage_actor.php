<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Encrypt.lib.php";			//암호화 관련 클래스

	$C_member	= new Member;
	$C_encrypt	= new Encrypt;

	$mode	= v3chk($_POST['mode'], "", 3);
	
	$mem_postnum		= v3chk($_POST['sample4_postcode'], "", 6);
	$mem_addr1			= v3chk($_POST['sample4_roadAddress'], "", 400);
	$mem_addr2			= v3chk($_POST['sample4_jibunAddress'], "", 255);
	$mem_invest_type	= v3chk($_POST['mem_invest_type'], "", 2); 
	$mem_sms_yn			= v3chk($_POST['mem_sms_yn'], "int", 1);	//SMS 수신동의
	$mem_mail_yn		= v3chk($_POST['mem_mail_yn'], "int", 1);   //이메일 수신동의  

	$mem_industry_code	= v3chk($_POST['mem_industry_code'], "", 5);
	$mem_issuer_tel		= v3chk($_POST['txt_mem_issuer_tel'], "", 20);
	$mem_issuer_website	= v3chk($_POST['mem_issuer_website'], "", 100);
	$mem_issuer_peopleCnt	= v3chk($_POST['mem_issuer_peopleCnt'], "int", 5);    
	$mem_establishment_dt	= v3chk($_POST['mem_establishment_dt'], "", 10);

	
	$inobiz_yn			= v3chk($_POST['inobiz_yn'], "int", 1);
	$mainbiz_yn			= v3chk($_POST['mainbiz_yn'], "int", 1);
	$venture_yn			= v3chk($_POST['venture_yn'], "int", 1);

	$hid_filename_1		= v3chk($_POST['hid_filename_1'], "", 200);
	$hid_filename_2		= v3chk($_POST['hid_filename_2'], "", 200);
	$hid_filename_3		= v3chk($_POST['hid_filename_3'], "", 200);
	$hid_filename_4		= v3chk($_POST['hid_filename_4'], "", 200);
	$hid_filename_5		= v3chk($_POST['hid_filename_5'], "", 200);
	$hid_filename_6		= v3chk($_POST['hid_filename_6'], "", 200); 
	$hid_filename_7		= v3chk($_POST['hid_filename_7'], "", 200); 
	$hid_filename_8		= v3chk($_POST['hid_filename_8'], "", 200); 
	
	$mem_job_type		= v3chk($_POST['ddl_mem_job_type'], "", 30);

	$mem_interest_industry	= $_POST['interest_type'];
	$mem_issuer_introduce	= $_POST['txt_mem_issuer_introduce'];
 
	
	$txt_mem_hp			= null;
	if(v3chk($_POST['txt_mem_hp_2'], "", 4) and v3chk($_POST['txt_mem_hp_3'], "", 4))
	{
		$txt_mem_hp			= v3chk($_POST['txt_mem_hp_1'], "", 3)."-".v3chk($_POST['txt_mem_hp_2'], "", 4)."-".v3chk($_POST['txt_mem_hp_3'], "", 4);
	}

	$txt_mem_pwd		= null;
	if(v3chk($_POST['txt_mem_pwd'], "", 50))
	{
		$txt_mem_pwd		= v3chk($_POST['txt_mem_pwd'], "", 50); 
	}

	$mem_sms_yn		= ($mem_sms_yn != "") ? 1 : 0;
	$mem_mail_yn	= ($mem_mail_yn != "") ? 1 : 0;
	$inobiz_yn		= ($inobiz_yn != "") ? 1 : 0;
	$mainbiz_yn		= ($mainbiz_yn != "") ? 1 : 0;
	$venture_yn		= ($venture_yn != "") ? 1 : 0;
 
	$err_code	= "-1001";

	unset($args);
	$args['mode']		= $mode;  
	$args['mem_idx']	= $user_info['mem_idx'];

	if($txt_mem_hp)
	{
		$args['mem_hp']				= $txt_mem_hp;
	}

	if($txt_mem_pwd)
	{
		$args['mem_pwd']			= $C_encrypt->hash_encrypt(strtolower($txt_mem_pwd));
	}

	$args['mem_postnum']			= $mem_postnum;
	$args['mem_addr1']				= $mem_addr1;
	$args['mem_addr2']				= $mem_addr2;
	$args['mem_invest_type']		= $mem_invest_type; //투자유형 (0: 발행, 1:일반, 2:적격, 3:전문투자자)
	$args['sms_yn']					= $mem_sms_yn;  //SMS수신동의
	$args['mail_yn']				= $mem_mail_yn; //이메일수신동의
	$args['mem_industry_code']		= $mem_industry_code; // 업종코드
	$args['inobiz_yn']				= $inobiz_yn; // 이노비즈인증
	$args['mainbiz_yn']				= $mainbiz_yn; // 메인비즈인증
	$args['venture_yn']				= $venture_yn; // 업벤처기업인증코드 
	$args['mem_issuer_tel']			= $mem_issuer_tel; // 발행기업 대표번호 
	$args['mem_interest_industry']	= $mem_interest_industry; //관심업종 선택
	$args['mem_issuer_website']		= $mem_issuer_website; // 발행기업 웹사이트 
	$args['mem_issuer_peopleCnt']	= $mem_issuer_peopleCnt; // 발행기업 인원수
	$args['mem_establishment_dt']	= $mem_establishment_dt; // 발행기업 인원수
	$args['mem_job_type']			= $mem_job_type; // 개인회원 직종
	$args['mem_issuer_introduce']	= $mem_issuer_introduce; // 발행기업 회사소개	

	$args['mem_fileupload1']	= $hid_filename_1; 
	$args['mem_fileupload2']	= $hid_filename_2; 
	$args['mem_fileupload3']	= $hid_filename_3; 
	$args['mem_fileupload4']	= $hid_filename_4; 
	$args['mem_fileupload5']	= $hid_filename_5; 
	$args['mem_fileupload6']	= $hid_filename_6; 
	$args['mem_fileupload7']	= $hid_filename_7; 
	$args['mem_fileupload8']	= $hid_filename_8; 

	$err_code = $C_member->set_userinfo_update($args);

	$return_msg_array['err_code']	= $err_code; 

	echo json_encode($return_msg_array); 
?>
