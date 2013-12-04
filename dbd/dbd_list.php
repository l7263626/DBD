<div class="float" style="width: 30%;">
	<h2>今天菜單</h2>
	<p>按圖可以放大看</p>
	<ul class="dbd_list">
		<li><a href="img/b/<?php echo $dbd->b_current ?>.jpg" rel="lightbox"><img src="img/b/<?php echo $dbd->b_current ?>.jpg"></a></li>
		<li><a href="img/d/<?php echo $dbd->d_current ?>.jpg" rel="lightbox"><img src="img/d/<?php echo $dbd->d_current ?>.jpg"></a></li>	
	</ul>
</div>

<?php
	if(!$dbd->dd_option){
?>
<div class="float">
	<h2>值日生登入</h2>
	<form name="dd_login" method="post">
		<input type="text" name="dd_code" style="width: 150px;">
		<input type="submit" value="登入" style="margin: 0;">
	</form>
</div>
<?php
	}
?>
<div class="clear">&nbsp;</div>

<h2>訂餐清單</h2>

<form name="dr_record" action="" method="post">
<table>
	<tr>
		<th width="10%">姓名</th>
		<?php if($dbd->dd_option){ ?>
		<th width="15%">收費紀錄</th>
		<?php } ?>
		<th width="10%">價格</th>
		<!--<th width="10%">類別</th>-->
		<th>訂購內容</th>
		<th width="20%">其他備註</th>
	</tr>
	<?php 
		$colspan = ($dbd->dd_option)?6:5;
		if(!empty($dbd->list) && is_array($dbd->list)){
			
			foreach($dbd->list as $key => $row){
				
				if($row["dbd_type"] == 1 && empty($type_1_count)){
					$type_1_count++;
					echo '<tr><td colspan="'.$colspan.'">&nbsp;</td></tr><tr><th colspan="'.$colspan.'" align="center">便當</th></tr>';
				}
				
				if($row["dbd_type"] == 2 && empty($type_2_count)){
					$type_2_count++;
					echo '<tr><td colspan="'.$colspan.'">&nbsp;</td></tr><tr><th colspan="'.$colspan.'" align="center">飲料</th></tr>';
				}
	?>
	<tr>
		<td><?php echo $row["dbd_name"]; ?></td>
		<?php 
			if($dbd->dd_option){
				$dr_row = $dbd->dr_load($db,$row["dbd_id"]);
		 ?>
		<td>
			<input type="checkbox" name="dr_done[<?php echo $key; ?>]" value="1" <?php echo (!empty($dr_row["dr_done"]))?"checked":"" ?>> 已收費<br />
			<input type="text" name="dr_over[<?php echo $key; ?>]" value="<?php echo $dr_row["dr_over"]; ?>" style="width: 50px;"> 需找錢
			<input type="hidden" name="dbd_id[<?php echo $key; ?>]" value="<?php echo $row["dbd_id"]; ?>">
		</td>
		<?php } ?>
		<td><?php echo $row["dbd_price"]; ?></td>
		<!--<td><?php echo ($row["dbd_type"] == 1)?"便當":"飲料"; ?></td>-->
		<td><?php echo $row["dbd_content"]; ?></td>
		<td><?php echo $row["dbd_info"]; ?></td>
	</tr>
	<?php 
			}
		}
	?>
	<tr><td colspan="<?php echo $colspan; ?>">&nbsp;</td></tr>
	<tr>
		<?php if($dbd->dd_option){ ?>
		<th>
			<input type="submit" name="dr_submit" value="紀錄收費" style="margin:0;">
			<input type="hidden" name="dr_callback" value="1">
		</th>
		<?php } ?>
		<th colspan="5" align="right">
			便當小計 : <?php echo $dbd->type_value[1]; ?><span class="break"> | </span>
			飲料小計 : <?php echo $dbd->type_value[2]; ?><span class="break"> | </span>
			總價 : <?php echo number_format($dbd->all_value); ?><span class="break"> </span>
		</th>
	</tr>
	
	<tr>
	</tr>
</table>
</form>

<br />


<?php
	if($dbd->list_on){
?>
<h2>修改訂餐</h2>

<form name="dbd_form" action="dbd.php" method="post">
<table>
	<tr>
		<th>你是誰?</th>
		<th><input type="text" name="dbd_name" value="<?php echo $dbd->mod_name; ?>"></th>
		<th></th>
		<th></th>
	</tr>
	<tr>
		<th width="10%"></th>
		<th width="30%">要訂啥?</th>
		<th width="30%">多少錢?</th>
		<th width="30%">備註啦!</th>
	</tr>
	<tr>
		<th>便當</th>
		<th><input type="text" name="dbd[1][content]" value="<?php echo $dbd->mod[1]["dbd_content"]; ?>"></th>
		<th><input type="text" name="dbd[1][price]" value="<?php echo $dbd->mod[1]["dbd_price"]; ?>"></th>
		<th><input type="text" name="dbd[1][info]" value="<?php echo $dbd->mod[1]["dbd_info"]; ?>"></th>
	</tr>
	<tr>
		<th>飲料</th>
		<th><input type="text" name="dbd[2][content]" value="<?php echo $dbd->mod[2]["dbd_content"]; ?>"></th>
		<th><input type="text" name="dbd[2][price]" value="<?php echo $dbd->mod[2]["dbd_price"]; ?>"></th>
		<th><input type="text" name="dbd[2][info]" value="<?php echo $dbd->mod[2]["dbd_info"]; ?>"></th>
	</tr>
</table>

<input type="submit" value="寫好了~~! 記起來~" >

<input type="hidden" name="dbd[1][id]" value="<?php echo $dbd->mod[1]["dbd_id"]; ?>">
<input type="hidden" name="dbd[2][id]" value="<?php echo $dbd->mod[2]["dbd_id"]; ?>">

<input type="hidden" name="callback" value="1">
</form>
<?php
	}
?>

<br /><br /><br />