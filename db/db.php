<?

class mysql {
	var $con;
	function __construct($db=array()){
		$default = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db' => 'opg'
		);
		$db = array_merge($default,$db);
		$this->con=mysql_connect($db['host'],$db['user'],$db['pass'],true) or die ('Error connecting to MySQL');
		mysql_select_db($db['db'],$this->con) or die('Database '.$db['db'].' does not exist!');
	}
	
	
	function __destruct(){
		mysql_close($this->con);
	}

	function execute($sql=''){
		mysql_query("set names utf8");
		$result = mysql_query($sql,$this->con);
		$this->db_error = mysql_error();
		return $result;
	}
	
	function select($options=0){
		/*
		$default = array (
			'table' => '',
			'fields' => '*',
			'condition' => '1',
			'order' => '1',
			'limit' => 50
		);
		$options = array_merge($default,$options);
		*/
		if($options){ // 判斷是否有輸入值
			if(empty($options['condition'])){ $condition = ""; }else{ $condition = "WHERE {$options['condition']}"; }
			if(empty($options['order'])){ $order = ""; }else{ $order = "ORDER BY {$options['order']}"; }
			if(empty($options['limit'])){ $limit = ""; }else{ $limit = "LIMIT {$options['limit']}"; }
			
			$sql = "SELECT {$options['fields']} FROM {$options['table']} 
					{$condition} 
					{$order} 
					{$limit}";
			
			return $this->execute($sql);
			
		} // if $options
		else
		{ return false; } // if $options else
		
	} // function select
	
	
	function field($sql=0){
		
		if($sql && $sql!=false){
			return mysql_fetch_array($sql);
		}else{
			 echo 'Connection false';
		}
		
	} // function field
	
	function num($sql=0){
	
		if($sql && $sql!=false){
			return mysql_num_rows($sql);
		}else{
			 echo 'Connection false';
		}
	}
	
	function replace($tbl_name=0,$value=0){
		
		if(!empty($tbl_name) && !empty($value)){
			$sql = "REPLACE INTO ".$tbl_name." SET ".$value."";
			$this->execute($sql);
		}
	}
	
	function update($tbl_name=0,$value=0,$id_str=0){
		
		if(!empty($tbl_name) && !empty($value)){
			$sql = "UPDATE ".$tbl_name." SET ".$value." WHERE ".$id_str;
			$this->execute($sql);
		}
	}
	
	function delete($tbl_name=0,$value=0){
		
		if(!empty($tbl_name) && !empty($value)){
			$sql = "DELETE FROM ".$tbl_name." WHERE ".$value."";
			$this->execute($sql);
		}
	}
	
}// class mysql

?>