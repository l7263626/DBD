<form action="dbd.php" method="post">
	<h2>選擇今天的菜單</h2>
	<p class="red">10 點前票選今天的菜單，11 點截止訂餐。</p>
	<h3>便當</h3>
	<ul class="dbd_list">
		<?php 
			for($b_num=1;$b_num<=$dbd->b;$b_num++){
				switch($b_num){
					case 14:
						echo "<li>可以不要再太師傅了嗎??</li>";
					break;
					case 17:
						echo "<li>這家說我們太遠了他不送!!</li>";
					break;
					default:
		 ?>
		<li><input type="radio" name="b" value="<?=$b_num?>"><a href="img/b/<?=$b_num?>.jpg" rel="lightbox"><img src="img/b/<?=$b_num?>.jpg"></a></li>
		<?php
					break;
				}
			}
		?>
	</ul>
	
	<div class="clear">&nbsp;</div>
	
	<h3>飲料</h3>
	<ul class="dbd_list">
		<?php 
			for($d_num=1;$d_num<=$dbd->d;$d_num++){
				switch($d_num){
					case 1:
						echo "<li>這家搬走了!</li>";
					break;
					default:
		 ?>
		<li><input type="radio" name="d" value="<?=$d_num?>"><a href="img/d/<?=$d_num?>.jpg" rel="lightbox"><img src="img/d/<?=$d_num?>.jpg"></a></li>
		<?php
					break;
				}
			}
		?>
	</ul>
	
	<div class="clear">&nbsp;</div>
	
	<?php
		if(empty($_REQUEST["admin_pass"])){
	?>
	<input type="submit" value="選好了"><br /><br /><br />
	<?php
		}
	?>
	
	<input type="hidden" name="callback_select" value="1">
</form>
