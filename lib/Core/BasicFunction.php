<?php
	
	require_once  'Connection.php';
	
	class Konstanta {
		Static $CStr = 1;
		Static $CInt = 2;
	}
	
	function GetGroup($data, $query){
		if (array_key_exists ('Group', $query)) { 
            $NGroup = $query['Group'];
			$data = $data->group($query['Group']);
        }
		return $data;
	}
	
	
	function GetNData($query){
		if (array_key_exists ('NField', $query)) { 
            $NField = $query['NField'];
	        $Data = $query['NData'];
        } else {
            $NField = '';
    		$Data ='';
        }
		
		if ($NField !== '') {        
            $ct = count($NField);
            $posisi=0;
			$value = array();
			
		    while ($posisi < $ct){
				
				$value[$NField[$posisi]] = $Data[$posisi];
				$posisi++;
            }
        }
    	return $value;
	};
	
	function GetWhere($data, $query){
		if (array_key_exists ('KeyField', $query)) { 
            $keyField = $query['KeyField'];
	        $KeyData = $query['KeyData'];
			if (array_key_exists ('Operator', $query)) {
				$Op = $query['Operator'];                
			} 
			else {
				$Op = '';
			}
        } else {
            $keyField = '';
    		$KeyData ='';
            $Op = '';
        }
		
		//var_dump($Op);
		
		//kondisi di gunakan antara field 1 dan 2, 2 dan 3, dst
		if (array_key_exists ('KeyCondition', $query)) { 
            $KeyCondition = $query['KeyCondition'];
			array_unshift ($KeyCondition, 'AND');
		} else {
            $KeyCondition = '';
        }		
		
		if ($keyField !== '') {        
            $ct = count($keyField);
		    $posisi=0;
		    while ($posisi < $ct){
				if (is_array($KeyCondition)) {
					$tmpCon = strtoupper($KeyCondition[$posisi]);
				}
				else {
					$tmpCon = 'AND';
				}
				
				if (is_array($Op)) {
					$tmpOp = strtoupper($Op[$posisi]);
				}
				else {
					$tmpOp = '=';
				}						
				
				switch ($tmpCon){
						case 'AND' : {
							//$data = $data->and($keyField[$posisi] . " " . $tmpOp . " ?", $KeyData[$posisi]);
							//echo "ini masuk and           ";
				
							$data = SetAndCondition($data, $keyField[$posisi], $tmpOp, $KeyData[$posisi]);
							break;
						}
						case 'OR'  : {
							//$data = $data->or($keyField[$posisi] . " " . $tmpOp . " ?", $KeyData[$posisi]);
							$data = SetORCondition($data, $keyField[$posisi], $tmpOp, $KeyData[$posisi]);
							break;
						}
					}
						
				$posisi++;
            }
        }
		
		return $data;
	};
	
	
	function SetAndCondition($data, $LKeyField, $LOperator, $LKeyData) {
		//echo $LOperator;
		switch ($LOperator){
			case "IN" : {
				$data = $data->and($LKeyField, $LKeyData);
				break;
			} 
			case "NOT IN" : {
				$data = $data->and("NOT " . $LKeyField, $LKeyData);
				break;
			} 
			case "NULL" : {
				$data = $data->and($LKeyField, NULL);
				break;
			} 			
			default : {
				$data = $data->and(strtoupper($LKeyField) . " " . $LOperator . " ?", $LKeyData);				
				break;
			}
		}
		return $data;
	}
	
	function SetORCondition($data, $LKeyField, $LOperator, $LKeyData) {
		switch ($LOperator){
			case "IN" : {
				$data = $data->and($LKeyField, $LKeyData);
				break;
			} 
			case "NOT IN" : {
				$data = $data->and("NOT " . $LKeyField, $LKeyData);
				break;
			} 
			case "NULL" : {
				$data = $data->and($LKeyField, NULL);
				break;
			} 			
			default : {
				$data = $data->and($LKeyField . " " . $LOperator . " ?", $LKeyData);				
				break;
			}
		}
		return $data;
	}
	
	
	function Print_Json_Flexibel($data, $kolom){		
		if (count($data) > 0) {
			$PJson["Code"] = 1;
			$PJson["error"] = "berhasil";
			$PJson["message"] = "Berhasil mendapatkan data nama";
			$PJson["jumlahData"] = count($data);
			
			foreach($data as $dt){
				$PJson['semuanama'][] = array(
					'id' => $dt['ID'],
					'nama' => $dt['NAMA'],
					'alamat' => $dt['ALAMAT']
					);
			}
		} else {
			$PJson["Code"] = 0;
			$PJson["error"] = "berhasil";
			$PJson["message"] = "Data yang di cari tidak ada";
			$PJson['semuanama'][] = array(
					'id' => Null,
					'nama' => Null,
					'alamat' => Null
					);
		}
		echo json_encode($PJson);
	};
	
	function Print_Json($code, $status, $message, $jumlah, $data){	
		$PJson["Code"] = $code;
		$PJson["status"] = $status;
		$PJson["message"] = $message;
		$PJson["jumlahData"] = $jumlah;
		$PJson['isidata'] = $data;
	
		echo json_encode($PJson);		
	};
	
	function Print_Json_Query($data){	
		
		//berhasil query
		if (count($data) > 0) {
			Print_Json(1, 1, "Berhasil mendapatkan data", count($data), $data);
		} 
		//gagal query
		else {
			Print_Json(0, 0, "Data yang di cari tidak ada", count($data), $data);
		}
	};
	
	function Print_Json_Insert($data){	
		
		if ($data == true) {
			Print_Json(1, 1, "Data yang di input berhasil masuk", count($data), $data);
		}
		//gagal insert		
		else if ($data == false) {
			Print_Json(0, 0, "Data yang di input gagall masuk", count($data), $data);
		} 
	};
	
	function Print_Json_Update($data){	
		
		if ($data == true) {
			Print_Json(1, 1, "Data yang di udpate berhasil", count($data), $data);
		}
		//gagal insert		
		else if ($data == false) {
			Print_Json(0, 0, "Data yang di udpate gagall", count($data), $data);
		} 
	};
	
	function Print_Json_Delete($data){	
		
		if ($data == true) {
			Print_Json(1, 1, "Data Berhasil di Delete", count($data), $data);
		}
		//gagal insert		
		else if ($data == false) {
			Print_Json(0, 0, "Data gagal di Delete", count($data), $data);
		} 
	};
	
	function QuerySQL($db, $query) {
		$NamaTabel = $query['NamaTabel'];
        $NamaField = $query['NamaField'];
		
		if (array_key_exists ('LOrder', $query)) { 
            $LOrder = $query['LOrder']; 
        } else { $LOrder = ''; }
			
		
		$data = $db->$NamaTabel()
					->select($NamaField)
					->order($LOrder);
		$data = GetWhere($data, $query);
		$data = GetGroup($data, $query);
	
		return $data;
		/**/
	};
	
	function DeleteSQL($db, $query) {
		$NamaTabel = $query['NamaTabel'];
		$data = $db->$NamaTabel();
		$data = GetWhere($data, $query);
		if ($data) {
			$data = $data->delete();
		};
		return $data;
	};
	
	function InsertSQL($db, $query) {
		$NamaTabel = $query['NamaTabel'];
		
		$value = GetNData($query);
		$data = $db->$NamaTabel()
				   ->insert($value);
				   
		return $data;
	
	};
	
	function UpdateSQL($db, $query) {
		$NamaTabel = $query['NamaTabel'];
	
		$data = $db->$NamaTabel();
		$data = GetWhere($data, $query);
		if ($data) {
			$value = GetNData($query);
			$data = $data->update($value); 
		};
		return $data;
	};
	
	function isExist($db, $NamaTabel, $NamaField, $LKeyField, $LKeyData, $LOperator) {
		$data = $db->$NamaTabel()
					->select($NamaField);
		
		$query['KeyField'] = array($LKeyField);
		$query['KeyData'] = array($LKeyData);
		$query['Operator'] = array($LOperator);
		
		$data = GetWhere($data, $query);
		if (count($data) > 0) {
			return true;
		} else {
			return false;
		};		
	};
	
	function isExistQuery($db, $query) {
		$NamaTabel = $query['NamaTabel'];
        $NamaField = $query['NamaField'];
		
		$data = $db->$NamaTabel()
					->select($NamaField);
	
		$data = GetWhere($data, $query);
		if (count($data) > 0) {
			return true;
		} else {
			return false;
		};		
	};
	
	function CekSisaSaldo($db, $query) {
		$NamaTabel = $query['NamaTabel'];
        
		$data = $db->$NamaTabel()
					->select("Saldo");
	
		$data = GetWhere($data, $query);
		if (count($data["Saldo"]) <> 0) {
			return false;
		} else {
			return true;
		};		
	};
	
