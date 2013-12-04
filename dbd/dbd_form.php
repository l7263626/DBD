<h2>今天菜單</h2>
<p>按圖可以放大看</p>
<ul class="dbd_list">
	<li><a href="img/b/<?php echo $dbd->b_current ?>.jpg" rel="lightbox"><img src="img/b/<?php echo $dbd->b_current ?>.jpg"></a></li>
	<li><a href="img/d/<?php echo $dbd->d_current ?>.jpg" rel="lightbox"><img src="img/d/<?php echo $dbd->d_current ?>.jpg"></a></li>	
</ul>

<div class="clear">&nbsp;</div>

<h2>要訂甚麼??</h2>

<form name="dbd_form" action="dbd.php" method="post">
<table>
	<tr>
		<th>你是誰?</th>
		<th><input type="text" name="dbd_name"></th>
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
		<th><input type="text" name="dbd[1][content]"></th>
		<th><input type="text" name="dbd[1][price]"></th>
		<th><input type="text" name="dbd[1][info]"></th>
	</tr>
	<tr>
		<th>飲料</th>
		<th><input type="text" name="dbd[2][content]"></th>
		<th><input type="text" name="dbd[2][price]"></th>
		<th><input type="text" name="dbd[2][info]"></th>
	</tr>
</table>

<input type="submit" value="寫好了~~! 記起來~" >

<input type="hidden" name="callback" value="1">
</form>

<br /><br /><br />
