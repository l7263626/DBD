<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/dbd.css" >
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/default_box.js"></script>
</head>

<?php
	include_once("dbd/dbd_duty_function.php");
?>

<body>
	<h1>值日生</h1>
	<ul>
		<li><a href="dbd.php">回訂便當系統</a></li>
		<li><a class="toggle" href="#">顯示值日生排表</a></li>
		<li><a class="toggle" href="#">顯示管理員介面</a></li>
	</ul>
	<div class="clear">&nbsp;</div>
	
	<div class="toggle_block">
		
		<h2>值日生排表 : <?php echo date("n"); ?>月</h2>
		<table>
			<tr>
				<th>日</th>
				<th>一</th>
				<th>二</th>
				<th>三</th>
				<th>四</th>
				<th>五</th>
				<th>六</th>
			</tr>
			<?php echo $duty->month_table; ?>
		</table>
		<hr>
	</div>
	
	<div class="toggle_block">
		<div class="float">
			<h2>值日生設定</h2>
			<?php
				if($duty->duty_menage){
			?>	
			<form name="duty_setting" action="" method="post">
				<?php 
					if(is_array($duty->dl_row)){
						foreach($duty->dl_row as $key => $row){
							$key++;
				?>
				<p class="dl_row">
					<input type="hidden" name="dl_id[<?php echo $key; ?>]" value="<?php echo $row["dl_id"]; ?>" style="width: 100px;">
					名稱 : <input type="text" name="dl_name[<?php echo $key; ?>]" value="<?php echo $row["dl_name"]; ?>" style="width: 100px;">
					<span class="break"> | </span>
					E-mail : <input type="text" name="dl_mail[<?php echo $key; ?>]" value="<?php echo $row["dl_mail"]; ?>" style="width: 300px;">
				</p>
				<?php
						}
					}else{
				?>
				<p class="dl_row">
					<input type="hidden" name="dl_id[1]" value="" style="width: 100px;">
					名稱 : <input type="text" name="dl_name[1]" value="" style="width: 100px;">
					<span class="break"> | </span>
					E-mail : <input type="text" name="dl_mail[1]" value="" style="width: 300px;">
				</p>
				<?php
					}
				?>
				<input type="button" name="add_dl" value="增加值日生">
				<input type="submit" value="儲存">
				<input type="hidden" name="dl_callback" value="1">
			</form>
		</div>
		<div class="float">
			<h2>值日生排程設定</h2>
			<form name="duty_form" action="" method="post">
				<div class="float">
					這個月 : <?php echo $duty->this_month; ?> 月
					<br /><br />
					<table>
						<tr>
							<th width="15%">日期</th>
							<th>選擇值日生</th>
						</tr>
						<?php
							for($t_d=1;$t_d<=$duty->this_days;$t_d++){
								$t_date_format = date("Y-m-d",mktime(0,0,0,$duty->this_month,$t_d,$duty->this_year));
								$t_week = date("w",mktime(0,0,0,$duty->this_month,$t_d,$duty->this_year));
						?>
						<tr>
							<td><?php echo $t_d; ?></td>
							<td>
								<select name="dl_id_1[<?php echo $t_date_format ?>]">
									<option value="0"><?php echo ($t_week == 0 || $t_week == 6)?"假日":"未選擇" ?></option>
									<?php $duty->dd_option($db,$t_date_format,1); ?>
								</select>
								<br />
								<select name="dl_id_2[<?php echo $t_date_format ?>]">
									<option value="0"><?php echo ($t_week == 0 || $t_week == 6)?"假日":"未選擇" ?></option>
									<?php $duty->dd_option($db,$t_date_format,2); ?>
								</select>
								<input type="hidden" name="dd_date[]" value="<?php echo $t_date_format ?>">
							</td>
						</tr>
						<?php
							}
						?>
					</table>
				</div>
				<div class="float">
					下個月 : <?php echo $duty->next_month; ?> 月
					<br /><br />
					<table>
						<tr>
							<th width="15%">日期</th>
							<th>選擇值日生</th>
						</tr>
						<?php
							for($n_d=1;$n_d<=$duty->next_days;$n_d++){
								$n_date_format = date("Y-m-d",mktime(0,0,0,$duty->next_month,$n_d,$duty->next_year));
								$n_week = date("w",mktime(0,0,0,$duty->next_month,$n_d,$duty->next_year));
						?>
						<tr>
							<td><?php echo $n_d; ?></td>
							<td>
								<select name="dl_id_1[<?php echo $n_date_format ?>]">
									<option value="0"><?php echo ($n_week == 0 || $n_week == 6)?"假日":"未選擇" ?></option>
									<?php $duty->dd_option($db,$n_date_format,1); ?>
								</select>
								<br />
								<select name="dl_id_2[<?php echo $n_date_format ?>]">
									<option value="0"><?php echo ($n_week == 0 || $n_week == 6)?"假日":"未選擇" ?></option>
									<?php $duty->dd_option($db,$n_date_format,2); ?>
								</select>
								<input type="hidden" name="dd_date[]" value="<?php echo $n_date_format ?>">
							</td>
						</tr>
						<?php
							}
						?>
					</table>
				</div>
				<div class="clear">&nbsp;</div>
				<input type="submit" value="儲存">
				<input type="hidden" name="dd_callback" value="1">
			</form>
		</div>
		
		<div class="clear">&nbsp;</div>
		<hr>
		<form name="duty_setting" action="" method="post">
			<input type="submit" value="管理員登出" style="margin: 0;">
			<input type="hidden" name="logout" value="1">
		</form>
		<br />
	</div>
	<?php
		}else{
	?>
	<form name="duty_setting" action="" method="post">
		<input type="text" name="duty_code" value="-- 輸入管理碼 --" style="width: 100px;">
		<input type="submit" value="登入" style="margin: 0;">
	</form>
	<?php
		}
	?>
</body>
</html>

<script>
	$(function(){
		$("input[name=duty_code]").default_box({
			ACTIVE : "#666", // 註解文字顏色
			DEACTIVE : "#000", //輸入文字顏色
		});
		
		//----
		$("input[name=add_dl]").click(function(E){
			E.preventDefault();
			var DL_ROW = $(".dl_row").length - -1;
			
			$(this).before(
				'<p class="dl_row">'+
					'<input type="hidden" name="dl_id['+ DL_ROW +']" style="width: 100px;">'+
					'名稱 : <input type="text" name="dl_name['+ DL_ROW +']" style="width: 100px;">'+
					'<span class="break"> | </span>'+
					'E-mail : <input type="text" name="dl_mail['+ DL_ROW +']" style="width: 300px;">'+
				'</p>'
			);
		});
		
		//----
		$(".toggle_block:eq(<?php echo $duty->toggle_block; ?>)").slideDown();
		
		$(".toggle").click(function(E){
			E.preventDefault();
			var TOGGLE_INDEX = $(this).index(".toggle");
			
			$(".toggle_block").slideUp();
			$(".toggle_block:eq("+ TOGGLE_INDEX +")").slideDown();
		});
	});
</script>