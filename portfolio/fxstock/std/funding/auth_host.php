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
	 *	Get the form elements
	 */
	$hd_ep_type	= $_POST["hd_ep_type"];
	$hd_pi		= $_POST["hd_pi"];
	$errCode 	= "";
	$authMsg 	= "";

	if (strcmp($hd_pi, null) == 0) {
		$authMsg = "인증실패. 인증결과를 넘겨주지 않았습니다.";
	}
?>
<html>
<head>
<title>뱅크페이 결제 서비스 진행중</title>
<script type="text/javascript">
	function unload_me()
	{
		if( "<?=$authMsg?>" != "" ) {
			alert("<?=$authMsg?>");
		}
		
		top.opener.paramSet("<?=$hd_pi?>", "<?=$hd_ep_type?>");
		top.opener.proceed();
 		parent.close();

	}
</script>
</head>
<body onload="unload_me();">
</body>
</html>
