<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/dbd.css" >
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/fix_box.js"></script>
<script type="text/javascript" src="js/form_box.js"></script>

<link type="text/css" rel="stylesheet" href="js/jquery-lightbox/jquery.lightbox-0.5.css" >
<script type="text/javascript" src="js/jquery-lightbox/jquery.lightbox-0.5.min.js"></script>

<?php
	include_once("dbd/dbd_function.php");
?>

<h1>訂便當系統</h1>
	<ul>
		<li><a href="dbd_duty.php">值日生排表</a></li>
		<li><a href="dbd.php?list_show=1">按此直接顯示訂單</a></li>
		<li><a href="dbd.php">回訂便當系統</a></li>
	</ul>
	<div class="clear">&nbsp;</div>

<?php
	if($_GET["admin_pass"]){
		include_once("dbd/dbd_vote.php");
	}else{
		switch($dbd->switch){
			case "1":
				include_once("dbd/dbd_vote.php");
			break;
			case "2":
				include_once("dbd/dbd_form.php");
			break;
			case "3":
				include_once("dbd/dbd_list.php");
			break;
		}
	}
?>


<script>
	$(function(){
		$(".dbd_list li").fix_box();
		
		$('a[rel=lightbox]').lightBox({
			overlayBgColor: '#000',
			overlayOpacity: 0.6,
			imageLoading: 'js/jquery-lightbox/images/lightbox-ico-loading.gif',
			imageBtnClose: 'js/jquery-lightbox/images/lightbox-btn-close.gif',
			imageBtnPrev: 'js/jquery-lightbox/images/lightbox-btn-prev.gif',
			imageBtnNext: 'js/jquery-lightbox/images/lightbox-btn-next.gif',
			imageBlank: 'js/jquery-lightbox/images/lightbox-blank.gif',
			containerResizeSpeed: 400,
			txtImage: 'Imagem',
			txtOf: 'de'
		});
	});
</script>