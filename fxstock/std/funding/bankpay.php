<?php 
	header("Content-type:text/html; charset=euc-kr");

	/*
	 * Do not cache this page.
	 */
	header("cache-control:no-cache");
	header("expires:-1");
	header("pragma:no-cache");
	 
 ?>
<?php
	/*
 	*	Get the form variable and HTTP header
 	*/
	$hd_msg_code			= $_POST["hd_msg_code"];
	$hd_msg_type			= $_POST["hd_msg_type"];
	$hd_approve_no			= $_POST["hd_approve_no"];
	$hd_serial_no			= $_POST["hd_serial_no"];
	$hd_firm_name			= $_POST["hd_firm_name"];
	$hd_item_name			= $_POST["hd_item_name"];
	$tx_amount				= $_POST["tx_amount"];
	 
	$tx_bill_yn				= $_POST["tx_bill_yn"];
	$tx_vat_yn				= $_POST["tx_vat_yn"];
	$tx_bill_vat			= $_POST["tx_bill_vat"];
	$tx_svc_charge			= $_POST["tx_svc_charge"];
	$tx_bill_tax			= $_POST["tx_bill_tax"];
	$tx_bill_deduction 		= $_POST["tx_bill_deduction"];
	$tx_age_check			= $_POST["tx_age_check"];
 
	$returnURL				= $_POST["returnURL"];
	$bankpayURL 			= $_POST["bankpayURL"];

	$tx_guarantee			= $_POST["tx_guarantee"];
	$tx_user_key			= $_POST["tx_user_key"];
	$hd_default_institution = $_POST["hd_default_institution"];
	$tx_receipt_acnt 		= $_POST["tx_receipt_acnt"];


	if (strcmp($hd_msg_code, null) 			== 0)	$hd_msg_code 		= "0200";
	if (strcmp($hd_msg_type, null) 			== 0)	$hd_msg_type 		= "EFT";
	if (strcmp($hd_approve_no, null) 		== 0)	$hd_approve_no 		= "";
	if (strcmp($hd_serial_no, null) 		== 0)	$hd_serial_no 		= "";
	if (strcmp($hd_firm_name, null) 		== 0)	$hd_firm_name 		= "";
	if (strcmp($hd_item_name, null) 		== 0)	$hd_item_name 		= "";
	if (strcmp($tx_amount, null) 			== 0)	$tx_amount 			= "";
	
	if (strcmp($tx_bill_yn, null) 			== 0)	$tx_bill_yn 		= "N";
	if (strcmp($tx_vat_yn, null) 			== 0)	$tx_vat_yn 			= "";
	if (strcmp($tx_bill_vat, null) 			== 0)	$tx_bill_vat 		= ""; // 미입력시 자동계산
	if (strcmp($tx_svc_charge, null) 		== 0)	$tx_svc_charge 		= "";
	if (strcmp($tx_bill_tax, null) 			== 0)	$tx_bill_tax 		= "";
	if (strcmp($tx_bill_deduction, null) 	== 0)	$tx_bill_deduction 	= "";
	if (strcmp($tx_age_check, null) 		== 0)	$tx_age_check 		= "";
	
	if (strcmp($returnURL, null) 			== 0)	$returnURL 			= "pay.php";
	if (strcmp($bankpayURL, null) 			== 0)	$bankpayURL 		= "https://www.bankpay.or.kr:7443/StartBankPay.do";
	
	if (strcmp($tx_guarantee, null) 		== 0)	$tx_guarantee 		= "N";
	if (strcmp($tx_user_key, null)			== 0)	$tx_user_key 		= "";
	if (strcmp($hd_default_institution, null) == 0)	$hd_default_institution	="";
	if (strcmp($tx_receipt_acnt, null) == 0)		$tx_receipt_acnt	="";
	
	$ret = $_SERVER["HTTP_REFERER"];
	$ret = substr($ret, 0, strrpos($ret, "/"));
	$termUrl 	= $ret . "/auth_host.php";

	$auth_msg 	= "";
	$result 	= false;
			
	if(strcmp($hd_approve_no, "") == 0){
		$auth_msg = "hd_approve_no is null";
	} else if(strcmp($hd_serial_no, "") == 0){
		$auth_msg = "hd_serial_no is null";
	} else if(strcmp($hd_firm_name, "") == 0){
		$auth_msg = "hd_firm_name is null";
	} else if(strcmp($tx_amount, "") == 0){
		$auth_msg = "tx_amount is null";
	} else {
		$result = true; 
	}
		
	// Operation succeeded 
	if($result) 
	{
?>	

<html>
	<head>
		<title>금융결제원 뱅크페이</title>
	</head>
	<body OnLoad="onLoadProc();">
		<Script Language="JavaScript">
			var cnt 		= 0;
			var timeout 	= 600;
			var k_timeout 	= 1;
			var processed 	= false;
			var goon 		= false;
			var childwin 	= null;
    	
			function closePopup()
			{
				if( childwin ) 
				{
					childwin.close();
				}
			}
    	
			function popupIsClosed()
			{
				if(childwin) {
					if(childwin.closed) {
						if( !goon ) {
							if( !processed ) {
								processed = true;
								self.setTimeout("popupIsClosed()", 2000);
							} //else popError("인증처리 중 문제가 발생하였습니다.(1)");
						}
					} else {
						cnt++;
						if(cnt > timeout) {
							popError("작업시간이 초과되었습니다.");
						} else {
							self.setTimeout("popupIsClosed()", 1000);
						}
					}
				} else if ( childwin == null ) {
					cnt++;
					if ( cnt > k_timeout ) {
						popError("팝업창이 차단되었습니다. 팝업 차단을 해제해 주십시오.");
					} else {
						self.setTimeout("popupIsClosed()", 1000);
					}
					
				} else {
					popError("인증처리 중 문제가 발생하였습니다.(2)");
				}
			}

			function popError(arg)
			{
				if( cnt < 1 ) {
					onPopupKiller();
					return;
				}
				if( childwin ) {
					childwin.close();
				}
				alert(arg);
// 				proceed();  // 팝업창을 닫았을 때는 완료 페이지 호출 못하게 막음
			}

			function onLoadProc() 
			{		
				leftPosition = (screen.width-720)/2-10;
				topPosition = (screen.height-600)/2-50;
				childwin = window.open('about:blank','BANKPAYPOPUP', 'top='+topPosition+',left='+leftPosition+',height=600,width=720,status=no,dependent=no,scrollbars=no,resizable=no');
				document.postForm.target = 'BANKPAYPOPUP';
				document.postForm.submit();
				popupIsClosed();
			}
		
			
			function paramSet(hd_pi, hd_ep_type)
			{
				var frm = document.frmRet;
				frm.hd_pi.value = hd_pi;
				frm.hd_ep_type.value = hd_ep_type;
			}

			function proceed() {
				var frm = document.frmRet;
				goon = true;
				frm.submit();
			}
		</Script>

		<form name="postForm" action="<?=$bankpayURL?>" method="POST">
			<input type=hidden name=hd_msg_code   	value="<?=$hd_msg_code?>">
			<input type=hidden name=hd_msg_type 	value="<?=$hd_msg_type?>">
			<input type=hidden name=hd_approve_no   value="<?=$hd_approve_no?>">
			<input type=hidden name=hd_serial_no    value="<?=$hd_serial_no?>">
			<input type=hidden name=hd_firm_name    value="<?=$hd_firm_name?>">
			<input type=hidden name=hd_item_name    value="<?=$hd_item_name?>">
			<input type=hidden name=tx_amount    	value="<?=$tx_amount?>">
			<!-- 현금영수증 관련 파라미터 -->
			<input type=hidden name=tx_bill_yn      value="<?=$tx_bill_yn?>">
			<input type=hidden name=tx_vat_yn      	value="<?=$tx_vat_yn?>">
			<input type=hidden name=tx_bill_vat     value="<?=$tx_bill_vat?>">
			<input type=hidden name=tx_svc_charge   value="<?=$tx_svc_charge?>">
			<input type=hidden name=tx_bill_tax     value="<?=$tx_bill_tax?>">
			<input type=hidden name=tx_bill_deduction  	value="<?=$tx_bill_deduction?>">
			
			<input type=hidden name=tx_age_check   	value="<?=$tx_age_check?>">
			
			<input type=hidden name=termURL      	value="<?=$termUrl?>">
			
			<input type=hidden name=tx_guarantee	value="<?=$tx_guarantee?>">
			<input type=hidden name=tx_user_key		value="<?=$tx_user_key?>">
			<input type=hidden name=hd_default_institution	value="<?=$hd_default_institution?>">
		</form>
		
		
		<form name=frmRet method=post action="<?=$returnURL?>">
			<!--  암호화된 결제정보 파라미터 -->
			<input type=hidden name=hd_pi      		value="">
			<input type=hidden name=hd_ep_type      value="">
			
			<input type=hidden name=hd_msg_code   	value="<?=$hd_msg_code?>">
			<input type=hidden name=hd_msg_type 	value="<?=$hd_msg_type?>">
			
			<input type=hidden name=hd_approve_no   value="<?=$hd_approve_no?>">
			<input type=hidden name=hd_serial_no    value="<?=$hd_serial_no?>">
			<input type=hidden name=hd_firm_name    value="<?=$hd_firm_name?>">
			<input type=hidden name=hd_item_name    value="<?=$hd_item_name?>">
			<input type=hidden name=tx_amount    	value="<?=$tx_amount?>">
			<!-- 현금영수증 관련 파라미터 -->
			<input type=hidden name=tx_bill_yn      value="<?=$tx_bill_yn?>">
			<input type=hidden name=tx_vat_yn      	value="<?=$tx_vat_yn?>">
			<input type=hidden name=tx_bill_vat     value="<?=$tx_bill_vat?>">
			<input type=hidden name=tx_svc_charge   value="<?=$tx_svc_charge?>">
			<input type=hidden name=tx_bill_tax     value="<?=$tx_bill_tax?>">
			<input type=hidden name=tx_bill_deduction  	value="<?=$tx_bill_deduction?>">
			
			<input type=hidden name=tx_age_check   	value="<?=$tx_age_check?>">
			<input type=hidden name=hd_timeout      value="<?=$hd_timeout?>">
			
			<input type=hidden name=tx_guarantee    value="<?=$tx_guarantee?>">
			<input type=hidden name=tx_user_key		value="<?=$tx_user_key?>">
			<input type=hidden name=tx_receipt_acnt	value="<?=$tx_receipt_acnt?>">
		</form>
	</body>
</html>

<?php
		return;
	}
?>

<html>
	<script language=javascript>
		function onLoadProc()
		{
			if( "<?=$auth_msg?>" != "" ) {
				alert("<?=$auth_msg?>");
			}      
		}
	</script>
	<body onload="javascript:onLoadProc();"> 
	</body>
</html>


