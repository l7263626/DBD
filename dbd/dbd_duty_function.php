<?php
	session_start();
	include_once("db/db.php");
	
	//連結資料庫
	$db = new mysql(array(
		'host' => 'localhost',
		'user' => 'potson',
		'pass' => '22462885',
		'db' => 'amg_potson'
	));
	
	$duty = new DUTY($db);
	
	class DUTY{
		function __construct($db){
			// SETTING
			$this->duty_code = substr(md5("evonne@allmarketing.com.tw"),8,16);
			
			// DUTY_CALENDAR
			$this->duty_calendar($db);
						
			// MENAGE UI
			if(!empty($_REQUEST["duty_code"]) && $this->duty_code == $_REQUEST["duty_code"] || $_SESSION["duty_code"] == $this->duty_code){
				$this->duty_menage = ture;
				$this->toggle_block = "1";
				$_SESSION["duty_code"] = $this->duty_code;
				
				$this->dl_list($db);
				$this->dd_list($db);
			}else{
				$this->duty_menage = false;
				$this->toggle_block = "0";
			}

			// DL_REPLACE
			if(!empty($_REQUEST["dl_callback"])){
				$this->dl_replace($db);
			}
			
			//DD_REPLACE
			if(!empty($_REQUEST["dd_callback"])){
				$this->dd_replace($db);
			}
			
			// LOGOUT
			if(!empty($_REQUEST["logout"])){
				unset($_SESSION["duty_code"]);
				header("location: dbd_duty.php");
			}
		}
		
		##############################################################################################################
		
		function duty_calendar($db){
			$month_f_week = date("w",mktime(0,0,0,date("m"),1,date("Y")));
			$month_days = date("t",mktime(0,0,0,date("m"),1,date("Y")));
			
			unset($month_table);
			for($w=1;$w<=6;$w++){
				$month_table .= '<tr>';
				
				for($d=0;$d<=6;$d++){
					if($month_f_week == $d && empty($date_row) || !empty($date_row) && $month_days > $date_row){
						$date_row++;
						$duty_array = $this->dd_duty_value($db,date("Y-m-d",mktime(0,0,0,date("m"),$date_row,date("Y"))));
						
						if(count($duty_array) > 0){
						$dl_value_1 = $this->duty_load($db,$duty_array[1]);
						$dl_value_2 = $this->duty_load($db,$duty_array[2]);
							
							$today = (date("d") == $date_row)?'class="today"':"";
							$month_table .= '<td '.$today.'><b>'.$date_row.'</b> | '.$dl_value_1["dl_name"].' / '.$dl_value_2["dl_name"].'</td>';
						}else{
							$month_table .= '<td>'.$date_row.'</td>';
						}
					}else{
						$month_table .= '<td>&nbsp;</td>';
					}
				}
				
				$month_table .= '</tr>';
			}
			
			$this->month_table = $month_table;
		}
		
		function duty_load($db,$dl_id=0){
			$sql = $db->select(array(
				'table' => 'dbd_duty_list',
				'fields' => '*',
				'condition' => "dl_id = '".$dl_id."'",
				//'order' => '',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
			
			if($rsnum > 0){
				return $db->field($sql);
			}
		}
		
		function dl_list($db){
			$sql = $db->select(array(
				'table' => 'dbd_duty_list',
				'fields' => '*',
				//'condition' => '',
				'order' => 'dl_id asc',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
			
			if($rsnum > 0){
				while($row = $db->field($sql)){
					$this->dl_row[] =  $row;
				}
			}else{
				$this->dl_row = true;
			}
		}
		
		function dl_replace($db){
			if(!empty($_REQUEST["dl_id"]) && is_array($_REQUEST["dl_id"])){
				foreach($_REQUEST["dl_id"] as $key => $ID){
					$dl_name = $_REQUEST["dl_name"][$key];
					$dl_mail = $_REQUEST["dl_mail"][$key];
					
					unset($replace_count);
					if(!empty($dl_name) && !empty($dl_mail)){
						$replace_count .= " dl_id = '".$ID."',";
						$replace_count .= " dl_name = '".$dl_name."',";
						$replace_count .= " dl_mail = '".$dl_mail."'";
						
						$db->replace("dbd_duty_list",$replace_count);
					}else{
						$db->delete("dbd_duty_list","dl_id = '".$ID."'");
					}
				}
			}
			
			header("location: dbd_duty.php");
		}

		function dd_list($db){
			$this->this_year = date("Y");
			$this->next_year = date("Y",mktime(0,0,0,date("m") + 1,1,date("Y")));
			
			$this->this_month = date("n");
			$this->next_month = date("n",mktime(0,0,0,date("m") + 1,1,date("Y")));
			
			$this->this_days = date("t",mktime(0,0,0,date("m"),1,date("Y")));
			$this->next_days = date("t",mktime(0,0,0,date("m") + 1,1,date("Y")));
		}
		
		function dd_option($db,$date=0,$dl_switch=0){
			$sql = $db->select(array(
				'table' => 'dbd_duty_list',
				'fields' => '*',
				//'condition' => '',
				'order' => 'dl_id asc',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
			
			if($rsnum > 0){
				while($row = $db->field($sql)){
					$value_array = $this->dd_duty_value($db,$date);
					$option_current = ($row["dl_id"] == $value_array[$dl_switch])?'selected':"";
					$option_array[] = '<option value="'.$row["dl_id"].'" '.$option_current.'>'.$row["dl_name"].'</option>';
				}
				
				if(count($option_array) > 0){
					echo implode("",$option_array);
				}
			}
		}
		
		function dd_duty_value($db,$date=0){
			$sql = $db->select(array(
				'table' => 'dbd_duty',
				'fields' => '*',
				'condition' => "dd_date = '".$date."'",
				//'order' => '',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
			
			if($rsnum > 0){
				$row = $db->field($sql);
				
				return array(1 => $row["dl_id_1"], 2 => $row["dl_id_2"]);
			}
		}
		
		function dd_replace($db){
			if(!empty($_REQUEST["dd_date"]) && is_array($_REQUEST["dd_date"])){
				foreach($_REQUEST["dd_date"] as $key => $date){
					$dl_id_1 = $_REQUEST["dl_id_1"][$date];
					$dl_id_2 = $_REQUEST["dl_id_2"][$date];
					
					unset($replace_count);
					if(!empty($dl_id_1) && !empty($dl_id_2)){
						$replace_count .= " dd_date = '".$date."',";
						$replace_count .= " dl_id_1 = '".$dl_id_1."',";
						$replace_count .= " dl_id_2 = '".$dl_id_2."'";
						
						$db->replace("dbd_duty",$replace_count);
					}else{
						$db->delete("dbd_duty","dd_date = '".$date."'");
					}
				}
			}
			
			header("location: dbd_duty.php");
		}
	}
?>
