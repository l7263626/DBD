<?php
	session_start();
	include_once("db/db.php");
	/*
	include 'mobile_detect/Mobile_Detect.php';
	
	//平台檢查
	$detect = new Mobile_Detect();
	if($detect->isMobile() || $detect->isTablet()){
		//手機,平板
	}else{
	 * //電腦
	}
	*/
	
	//連結資料庫
	$db = new mysql(array(
		'host' => 'localhost',
		'user' => 'potson',
		'pass' => '22462885',
		'db' => 'amg_potson'
	));
	
	$dbd = new DBD($db);
	
	class DBD{
		function __construct($db){
			//初始設定
			$this->b = 19;
			$this->d = 19;
			$this->vote_limit = 10;
			$this->time_limit = 11;
			
			//值日生提醒、控制密碼
			$this->dd_remind($db);
			
			//值日生登入
			if(!empty($_REQUEST["dd_code"]) && $_REQUEST["dd_code"] == $this->dd_code){
				$_SESSION["dd_code"] = $_REQUEST["dd_code"];
				$this->dd_option = true;
			}else{
				if(!empty($_SESSION["dd_code"]) && $_SESSION["dd_code"] != $this->dd_code){
					unset($_SESSION["dd_code"]);
					header("location: dbd.php");
					return false;
				}
				
				if(!empty($_SESSION["dd_code"]) && $_SESSION["dd_code"] == $this->dd_code){
					$this->dd_option = true;
				}
				
				if(empty($_SESSION["dd_code"])){
					$this->dd_option = false;
				}
			}
			
			//收費紀錄
			if(!empty($_REQUEST["dr_callback"])){
				$this->dr_replace($db);
			}
			
			//投票儲存
			if(!empty($_REQUEST["callback_select"])){
				$this->dbd_vote_replace($db);
			}
			
			//訂單儲存
			if(!empty($_REQUEST["callback"]) && count($_REQUEST["dbd"]) > 0){
				$this->dbd_list_replace($db);
			}
			
			//讀取選票
			$this->dbd_vote($db);
			
			//讀取訂單
			if(!empty($_COOKIE["dbd_key"]) || !empty($_REQUEST["list_show"]) || $this->time_limit <= date("H")){
				$this->dbd_list($db);
				
				$this->switch = 3;
				return true;
			}
			
			//顯示頁面
			if(date("H") < $this->vote_limit || empty($this->b_current) && empty($this->d_current)){
				$this->switch = 1;
			}else{
				$this->switch = 2;
			}
		}
		
		##############################################################################################################

		function dbd_vote($db){
			$sql = $db->select(array(
				'table' => 'dbd_count',
				'fields' => '*',
				'condition' => "dc_date = '".date("Y-m-d")."'",
				//'order' => '',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
			
			if($rsnum > 0){
				while($row = $db->field($sql)){
					if(!empty($row["dc_b"])){
						$b_count[$row["dc_b"]]++;
					}
					
					if(!empty($row["dc_d"])){
						$d_count[$row["dc_d"]]++;
					}
				}
				
				if(count($b_count) > 0){
					arsort($b_count,SORT_NUMERIC);
					
					foreach($b_count as $key => $value){
						$b_key++;
						if($b_key == 1){
							$this->b_current = $key;
						}
					}
				}
				
				if(count($d_count) > 0){
					arsort($d_count,SORT_NUMERIC);
					
					foreach($d_count as $key => $value){
						$d_key++;
						if($d_key == 1){
							$this->d_current = $key;
						}
					}
				}
			}else{
				$this->b_current = "";
				$this->d_current = "";
			}
		}
		
		function dbd_list($db){
			//讀取訂單
			$sql = $db->select(array(
				'table' => 'dbd_list',
				'fields' => '*',
				'condition' => "dbd_date = '".date("Y-m-d")."'",
				'order' => 'dbd_type asc,dbd_time desc',
				//'limit' => ''
			));
		
			$rsnum = $db->num($sql);
		
			unset($this->all_value);
			if($rsnum > 0){
				while($row = $db->field($sql)){
					$this->list[] = $row;
					$this->all_value = $this->all_value + $row["dbd_price"];
					$this->type_value[$row["dbd_type"]] = $this->type_value[$row["dbd_type"]] + $row["dbd_price"];

					if($row["dbd_key"] == $_COOKIE["dbd_key"]){
						$this->mod_name = $row["dbd_name"];
						switch($row["dbd_type"]){
							case "1":
								$this->mod[1] = $row;
							break;
							case "2":
								$this->mod[2] = $row;
							break;
						}
						
						if($this->time_limit > date("H")){
							$this->list_on = true;
						}
					}
				}
			}
		}
		
		function dbd_vote_replace($db){
			if(!empty($_REQUEST["b"])){
				unset($replace_count);
				$replace_count .= " dc_id = '',";
				$replace_count .= " dc_b = '".$_REQUEST["b"]."',";
				$replace_count .= " dc_date = '".date("Y-m-d")."'";
				
				$db->replace("dbd_count",$replace_count);
			}
			
			if(!empty($_REQUEST["d"])){
				unset($replace_count);
				$replace_count .= " dc_id = '',";
				$replace_count .= " dc_d = '".$_REQUEST["d"]."',";
				$replace_count .= " dc_date = '".date("Y-m-d")."'";
				
				$db->replace("dbd_count",$replace_count);
			}
			
			header("location: dbd.php");
		}

		function dbd_list_replace($db){
			$dbd_name = $_REQUEST["dbd_name"];
			$dbd_key = substr(md5(date("Y-m-d H:i:s").$dbd_name),8,16);
			
			foreach($_REQUEST["dbd"] as $key => $value){
				if(!empty($value["id"])){
					$dbd_id = $value["id"];
				}else{
					$dbd_id = "";
				}
				$dbd_content = $value["content"];
				$dbd_price 	= $value["price"];
				$dbd_info = $value["info"];
				$dbd_type = $key;

				unset($replace_str);
				if(!empty($dbd_name) && !empty($dbd_content) && !empty($dbd_price)){
					$replace_str .= " dbd_id = '".$dbd_id."',";
					$replace_str .= " dbd_name = '".$dbd_name."',";
					$replace_str .= " dbd_content = '".$dbd_content."',";
					$replace_str .= " dbd_price = '".$dbd_price."',";
					$replace_str .= " dbd_info = '".$dbd_info."',";
					$replace_str .= " dbd_type = '".$dbd_type."',";
					$replace_str .= " dbd_date = '".date("Y-m-d")."',";
					$replace_str .= " dbd_time = '".date("H:i:s")."',";
					$replace_str .= " dbd_key = '".$dbd_key."'";
					
					$db->replace("dbd_list",$replace_str);
					$dbd_replace++;
				}
			}
			
			if(!empty($dbd_replace)){
				setcookie("dbd_key",$dbd_key,time()+3600*3);
			}
			
			header("location: dbd.php");
		}

		function dd_remind($db){
			$sql = $db->select(array(
				'table' => 'dbd_duty',
				'fields' => '*',
				'condition' => "dd_date = '".date("Y-m-d")."'",
				//'order' => '',
				//'limit' => ''
			));
		
			$rsnum = $db->num($sql);
		
			if($rsnum > 0){
				$row = $db->field($sql);
				$this->dd_code = substr(md5(date("Y-m-d").$row["dl_id_1"].$row["dl_id_2"]),8,16);
				
				if(empty($row["dd_code"])){
					$dl_mail = $this->dl_mail_get($db,$row["dl_id_1"],$row["dl_id_2"]);
					
					$update_str = "dd_code='".$this->dd_code."'";
					$update_where = "dd_date = '".date("Y-m-d")."'";
					$db->update("dbd_duty",$update_str,$update_where);
					$this->dd_mail($db,$dl_mail,$this->dd_code);
				}
			}
		}
		
		function dl_mail_get($db,$dl_id_1,$dl_id_2){
			$sql = $db->select(array(
				'table' => 'dbd_duty_list',
				'fields' => 'dl_mail',
				'condition' => "dl_id in (".$dl_id_1.",".$dl_id_2.")",
				//'order' => '',
				//'limit' => ''
			));
			
			$rsnum = $db->num($sql);
		
			if($rsnum > 0){
				while($row = $db->field($sql)){
					$dl_mail_array[] = $row["dl_mail"];
				}
				
				return $dl_mail_array;
			}else{
				return false;
			}
		}
		
		function dd_mail($db,$dl_mail,$dd_code){
	        $from_email = "dbd@allmarketing.com.tw";
	        $from_name = "訂便當系統";
	        $mail_subject = "=?UTF-8?B?".base64_encode("恭喜你~~今天是值日生!!")."?=";
			
	        //寄給送信者
	        $MAIL_HEADER   = "MIME-Version: 1.0\n";
	        $MAIL_HEADER  .= "Content-Type: text/html; charset=\"utf-8\"\n";
	        $MAIL_HEADER  .= "From: =?UTF-8?B?".base64_encode($from_name)."?= <".$from_email.">"."\n";
	        $MAIL_HEADER  .= "Reply-To: ".$from_email."\n";
	        $MAIL_HEADER  .= "Return-Path: ".$from_email."\n"; // these two to set reply address
	        $MAIL_HEADER  .= "X-Priority: 1\n";
	        $MAIL_HEADER  .= "Message-ID: <".time()."-".$from_email.">\n";
	        $MAIL_HEADER  .= "X-Mailer: PHP v".phpversion()."\n"; // These two to help avoid spam-filters
	       
	        $to_email = implode(",",$dl_mail);
			$mail_content = 
				'
					<p>你的值日生密碼 : '.$dd_code.'</p>
					<p>利用此密碼可以登入值日生專屬收費紀錄功能<br />方便你紀錄收便當的錢~~!</p>
					<p>此為系統信件，請勿直接回覆，如有問題請詢問士軒，謝謝。</p>
					<br />
					<p><a href="http://potson.allmarketing.com.tw/dbd.php?dd_code='.$dd_code.'">按此連結進入功能</a></p>
				';
			
	        for($i=0;$i<count($dl_mail);$i++){
	            if($i!=0 && $i%2==0){
	                sleep(2);
	            }
	            if($i!=0 && $i%5==0){
	                sleep(10);
	            }
	            if($i!=0 && $i%60==0){
	                sleep(300);
	            }
	            if($i!=0 && $i%600==0){
	                sleep(2000);
	            }
	            if($i!=0 && $i%1000==0){
	                sleep(10000);
	            }
	            @mail($to_email,$mail_subject,$mail_content,$MAIL_HEADER);
	        }
		}

		function dr_load($db,$dbd_id=0){
			if(!empty($dbd_id)){
				$sql = $db->select(array(
					'table' => 'dbd_record',
					'fields' => '*',
					'condition' => "dbd_id = '".$dbd_id."'",
					//'order' => '',
					//'limit' => ''
				));
				
				$rsnum = $db->num($sql);
			
				if($rsnum > 0){
					return $db->field($sql);
				}
			}
		}

		function dr_replace($db){
			if(!empty($_REQUEST["dbd_id"]) && is_array($_REQUEST["dbd_id"]) && count($_REQUEST["dbd_id"]) > 0){
				foreach($_REQUEST["dbd_id"] as $key => $ID){
					unset($replace_str);
					$replace_str .= " dbd_id = '".$ID."',";
					$replace_str .= " dr_done = '".$_REQUEST["dr_done"][$key]."',";
					$replace_str .= " dr_over = '".$_REQUEST["dr_over"][$key]."'";
					
					$db->replace("dbd_record",$replace_str);
				}
				
				header("location: dbd.php");
			}
		}
	}
?>