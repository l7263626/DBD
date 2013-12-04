<form action="dbd.php" method="post">
	<h2>選擇今天的菜單</h2>
	<p class="red">10 點前票選今天的菜單，11 點截止訂餐。</p>
	<h3>便當</h3>
	<ul class="dbd_list">
		<?php 
			for($b_num=1;$b_num<=$dbd->b;$b_num++){
				if($b_num == 14){
					echo "<li>可以不要再太師傅了嗎??</li>";
				}else{
		 ?>
		<li><input type="radio" name="b" value="<? echo $b_num ?>"><a href="img/b/<? echo $b_num ?>.jpg" rel="lightbox"><img src="img/b/<? echo $b_num ?>.jpg"></a></li>
		<?php
				}
			}
		?>
	</ul>
	
	<div class="clear">&nbsp;</div>
	
	<h3>飲料</h3>
	<ul class="dbd_list">
		<?php 
			for($d_num=1;$d_num<=$dbd->d;$d_num++){
		 ?>
		<li><input type="radio" name="d" value="<? echo $d_num ?>"><a href="img/d/<? echo $d_num ?>.jpg" rel="lightbox"><img src="img/d/<? echo $d_num ?>.jpg"></a></li>
		<?php
			}
		?>
	</ul>
	
	<div class="clear">&nbsp;</div>
	
	<input type="submit" value="選好了"><br /><br /><br />
	
	<input type="hidden" name="callback_select" value="1">
</form>
