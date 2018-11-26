<?php

require_once  'vendor/autoload.php';
require_once  'lib/NotORM.php';
require_once __DIR__ . '/lib/Core.php';
//require_once __DIR__ . '/lib/Core/Connection.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        ]
];

/****************************************************
contoh struktur query database
{
	"NamaField": "kode",
	"NamaTabel": "m_company",
	"KeyField":["kode"],
	"KeyData":["aa"],
	"Operator":["="],
	"NField":["kode"],
	"NData":["aa"],
	"KeyCondition" :["AND", "OR"],	
}

/***************************************************/



//$app = new Slim\App;
//$app = new Slim\App(['setting' => ['displayErrorDetails' => true]]);
$app = new Slim\App($config);

$container = $app->getContainer();

$container['db'] = function() {
    //$PDO= new PDO('mysql:dbname=bitsorder', 'root', '');
    //return new NotORM($PDO);
	
	//$db = DBConnection();
	return DBConnection();
};

function responEcho($statCode, $respons) {
    echo json_encode($respons);
}

$app->get('/', function ($request, $response) {
    echo "API";
	echo "asdasdas";
	
	
});



$app->get('/tesinsert', function($request, $response) {
		$data = $this->db->m_gudang()
					->insert(array(
				   "kode" => "tes aja",
				   "nama" => "ini nama"
				   ));
				   
		var_dump ($data);
});



$app->post('/nama', function($request, $response) {
		
		$has = $this->db->nama;
		echo $has;
		echo "<br>";
		$dosen["error"] = "berhasil";
		$dosen["message"] = "Berhasil mendapatkan data nama";
		foreach($has as $data){
			$dosen['semuanama'][] = array(
				'id' => $data['ID'],
				'nama' => $data['NAMA'],
				'alamat' => $data['ALAMAT']
				);
		}
		/**/
		echo json_encode($dosen);
	
});

$app->get('/showgudang', function($request, $response) {
		//$req = $request->getParsedBody('data'); 
        //$hasil = json_decode($req['data'], true);
        
		$data = $this->db->m_gudang()
					->select("*");
			
		//echo "asasdas";
		//$data = QuerySQL($this->db, $hasil);
		//echo $data;
		print_json_Query($data);
});



$app->get('/showbarang', function($request, $response) {
		//$req = $request->getParsedBody('data'); 
        //$hasil = json_decode($req['data'], true);
        
		$data = $this->db->m_item()
					->select("*")
					->order("kode");
			
		//echo "asasdas";
		//$data = QuerySQL($this->db, $hasil);
		print_json_Query($data);
});

$app->post('/showsupplier', function($request, $response) {
		//$req = $request->getParsedBody('data'); 
        //$hasil = json_decode($req['data'], true);
        
		//echo strtoupper(">=");
		
		/*$data = $this->db->m_company()
						 //->td_sales["nama"]
						 //->select("nama")
						 ->and ("KODE like ?", "C%");
					
		/**/
		
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		$has = QuerySQL($this->db, $hasil);
		//print_json_Query($has);
		
		echo $has;		
		echo "<br>";
		//echo "asasdas";
		//$data = QuerySQL($this->db, $hasil);
		//print_json_Query($data);
});

$app->post('/tespost', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		//$data = QuerySQL($this->db, $hasil);
		//print_json($data);
		echo json_encode($hasil);
		//echo json_encode($req);
});

$app->post('/showNama', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		$data = QuerySQL($this->db, $hasil);
		echo json_encode($has);
		//print_json($has);
		
		if (count($data) > 0) {
			$PJson["error"] = "berhasil";
			$PJson["message"] = "Berhasil mendapatkan data nama";
		
			foreach($data as $dt){
				$PJson['semuanama'][] = array(
					'id' => $dt['ID'],
					'nama' => $dt['NAMA'],
					'alamat' => $dt['ALAMAT']
					);
			}
		} else {
			$PJson["error"] = "berhasil";
			$PJson["message"] = "Data yang di cari tidak ada";
			$PJson['semuanama'][] = array(
					'id' => Null,
					'nama' => Null,
					'alamat' => Null
					);
		}
		echo json_encode($PJson);
});


$app->post('/showItem', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		$data = QuerySQL($this->db, $hasil);
		print_json_Query($data);
});

////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


$app->post('/show', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		$has = QuerySQL($this->db, $hasil);
		print_json_Query($has);
});


$app->post('/ins', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        		
		$extractNData = GetNData($hasil);
		$exist = isExist($this->db, $hasil['NamaTabel'], '*', 'KODE', $extractNData['KODE'], '=');
		
		if ($exist) {
			//echo 'sudah ada data';
			Print_Json(0, 2, "Data yang di input Sudah ada", 0, false);
		} else {
			$has = InsertSQL($this->db, $hasil);
			print_json_Insert($has);
			//echo 'belum ada data';
		}
		
		/**
		NB : 
		Code 0 = gagal, 1 = berhasil;
		status 0 = gagal karena ada error, 1 = berhasil 2 = sudah ada data;
		/**/
});

$app->post('/insSupplier', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        
		$extractNData = GetNData($hasil);
		$exist = isExist($this->db, $hasil['NamaTabel'], '*', 'KODE', $extractNData['Kode'], '=');
		
		if ($exist) {
			//echo 'sudah ada data';
			Print_Json(0, 2, "Data yang di input Sudah ada", 0, false);
		} else {
			$has = InsertSQL($this->db, $hasil);
			print_json_Insert($has);
			//echo 'belum ada data';
		}
		
		/**
		NB : 
		Code 0 = gagal, 1 = berhasil;
		status 0 = gagal karena ada error, 1 = berhasil 2 = sudah ada data;
		/**/
});


$app->post('/update', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
        

		//$extractNData = GetNData($hasil);
		//var_dump ($hasil);
		//$exist = isExistQuery($this->db, $hasil['NamaTabel'], '*', 'KODE', $extractNData['kode'], '=');
		$hasil['NamaField'] = "*";
		$exist = isExistQuery($this->db, $hasil);
		
		if ($exist) {
			//echo 'sudah ada data';
			$has = UpdateSQL($this->db, $hasil);
			print_json_Insert($has);
			
		} else {
			Print_Json(0, 2, "Data yang di Diupdate tidak ada", 0, false);
		}
		
		
		//$has = UpdateSQL($this->db, $hasil);
		//echo json_encode($has);
		//print_json_Insert($has);
});

$app->post('/delete', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
      
		
		$hasil['NamaField'] = "*";
		$exist = isExistQuery($this->db, $hasil);
		
		if ($exist) {
			//echo 'sudah ada data';
			$has = DeleteSQL($this->db, $hasil);
			print_json_Delete($has);
		} else {
			Print_Json(0, 2, "Data yang di Delete tidak ada", 0, false);
		}
		
		//$has = DeleteSQL($this->db, $hasil);
		//print_json_Insert($has);
		//echo json_encode($has);
		/**/
});

$app->post('/deleteCompany', function($request, $response) {
		$req = $request->getParsedBody('data'); 
        $hasil = json_decode($req['data'], true);
      
		
		$hasil['NamaField'] = "*";
		$exist = isExistQuery($this->db, $hasil);
		
		if ($exist) {
			//echo 'sudah ada data';
			if (CekSisaSaldo($this->db, $hasil)) {
				$has = DeleteSQL($this->db, $hasil);
				print_json_Delete($has);
			}
			else {
				Print_Json(0, 3, "Data yang mau di hapus masih memiliki Hutang / Piutang", 0, false);
			}
		} else {
			Print_Json(0, 2, "Data yang di Delete tidak ada", 0, false);
		}
		
		//$has = DeleteSQL($this->db, $hasil);
		//print_json_Insert($has);
		//echo json_encode($has);
		/**/
});
	

$app->run();




?>