<html>
	<head>
	<title>Usia Pendidikan Kota Bandung</title>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
	</head>

	<?php include "connectdb.php"; ?>

	<script>
		function getKec(val) {
			$.ajax(
				{
					type: "POST",
					url: "get_kec.php",
					data:'idwilayah='+val,
					success: function(data){
						$("#kecamatan-list").html(data);}
				});
			}

		function getKel(val) {
			$.ajax(
				{
					type: "POST",
					url: "get_kel.php",
					data:'idkecamatan='+val,
					success: function(data){
						$("#kelurahan-list").html(data);}
				});
			}

		function showMsg()
		{
			$("#msgC").html($("#wilayah-list option:selected").text());
			$("#msgS").html($("#kecamatan-list option:selected").text());
			$("#msgL").html($("#kelurahan-list option:selected").text());

			return false;
		}

	</script>
	<body>
		<form method="POST">
			<div class="col-md-2">
				<label style="font-size:20px" >Wilayah:</label>
				<select name="country" id="wilayah-list" class="form-control selectpicker" onChange="getKec(this.value);">
				<option value="kosong">Select Wilayah</option>

				<?php
					$sql1="SELECT DISTINCT id_wilayah,nama_wilayah FROM datakota";
			         $query=mysqli_query($connect,$sql1);
					while($results = mysqli_fetch_array($query)) {

						$wilayah = $_GET['country'];
						$kecamatan = $_GET['wilayah'];
				?>

				<option value="<?php echo $results['id_wilayah']; ?>"><?php echo $results['nama_wilayah']; ?></option>

				<?php
					}
				?>

			</select>
				<label style="font-size:20px" >Kecamatan:</label>
				<select id="kecamatan-list" name="kecamatan" class="form-control selectpicker" onchange="getKel(this.value);"
				style="margin-bottom:20px;">
				<option value="kosong">Select Kecamatan</option>
			</select>

			</select>
				<label style="font-size:20px" >Kelurahan:</label>
				<select id="kelurahan-list" name="kelurahan" class="form-control selectpicker"
				style="margin-bottom:20px;">
				<option value="kosong">Select Kelurahan</option>
			</select>

				<button value="submit" class="btn btn-info" onclick="showMsg();" style="margin-bottom:20px;">Submit</button><br />

			</div>
		</form>
		<div class="col-md-10">
		<?php

					if($_SERVER['REQUEST_METHOD']=="POST"){
						$wil = $_POST['country'];
						$kec = $_POST['kecamatan'];
						$kel = $_POST['kelurahan'];

						if($wil != ""){
							$sql = "SELECT id_wilayah,nama_wilayah,id_kecamatan,nama_kecamatan,
								SUM(usia_pendidikan_3_4) up_3_4, SUM(usia_pendidikan_5) up_5, 
								SUM(usia_pendidikan_6_11) up_6_11, SUM(usia_pendidikan_12_14) up_12_14,
								SUM(usia_pendidikan_15_17) up_15_17, SUM(usia_pendidikan_18_22) up_18_22 
								FROM datakota WHERE id_wilayah = '$wil' GROUP BY nama_kecamatan";
						}

						if($wil != "" && $kec != ""){
							$sql = "SELECT id_wilayah,nama_wilayah,id_kecamatan,nama_kecamatan,id_kelurahan,nama_kelurahan,
								SUM(usia_pendidikan_3_4) up_3_4, SUM(usia_pendidikan_5) up_5, 
								SUM(usia_pendidikan_6_11) up_6_11, SUM(usia_pendidikan_12_14) up_12_14,
								SUM(usia_pendidikan_15_17) up_15_17, SUM(usia_pendidikan_18_22) up_18_22
								FROM datakota WHERE id_wilayah = '$wil'
								AND id_kecamatan = '$kec' GROUP BY nama_kelurahan";
						}

						if($kec != "" && $kel != ""){
							$sql = "SELECT id_wilayah,nama_wilayah,id_kecamatan,nama_kecamatan,id_kelurahan,nama_kelurahan,
									SUM(usia_pendidikan_3_4) up_3_4, SUM(usia_pendidikan_5) up_5, 
									SUM(usia_pendidikan_6_11) up_6_11, SUM(usia_pendidikan_12_14) up_12_14,
									SUM(usia_pendidikan_15_17) up_15_17, SUM(usia_pendidikan_18_22) up_18_22
									FROM datakota WHERE id_kecamatan = '$kec'
									AND id_kelurahan = '$kel' GROUP BY nama_kelurahan";
						}

						if($wil == "kosong" && $kec == "kosong" && $kel == "kosong"){
							echo "Nothing Selected";
						}

						$query=mysqli_query($connect,$sql);
						while($results = mysqli_fetch_array($query)) {
							/* $data['kecamatan'] = $results['nama_kecamatan'];
							$data['kelurahan'] = $results['nama_kelurahan'];
							$data['3-4 tahun'] = $results['up_3_4'];
							$data['5 tahun'] = $results['up_5'];
							$data['6-11 tahun'] = $results['up_6_11'];
							$data['12-14 tahun'] = $results['up_12_14'];
							$data['15-17 tahun'] = $results['up_15_17'];
							$data['18-22 tahun'] = $results['up_18_22']; */
							//extract($results);
							if($wil != "" && $kec == ""){
								$wilayah = ['wilayah' => $results['nama_wilayah']];
								$data[] = ['kecamatan' => $results['nama_kecamatan'], 
								'3-4 tahun' => $results['up_3_4'], '5 tahun' => $results['up_5'],'6-11 tahun' => $results['up_6_11'],
								'12-14 tahun' => $results['up_12_14'],'15-17 tahun' => $results['up_15_17'],'18-22 tahun' => $results['up_18_22']];
							}
							if ($wil != "" && $kec != "") {
								$wilayah = ['wilayah' => $results['nama_wilayah'], 'kecamatan' => $results['nama_kecamatan']];
								$data[] = [ 'kelurahan' => $results["nama_kelurahan"], '3-4 tahun' => $results['up_3_4'], '5 tahun' => $results['up_5'],'6-11 tahun' => $results['up_6_11'],
								'12-14 tahun' => $results['up_12_14'],'15-17 tahun' => $results['up_15_17'],'18-22 tahun' => $results['up_18_22']];
							}
							if ($wil != "" && $kec != "" && $kel != "") {
								$data = ['kelurahan' => $results["nama_kelurahan"], '3-4 tahun' => $results['up_3_4'], '5 tahun' => $results['up_5'],'6-11 tahun' => $results['up_6_11'],
								'12-14 tahun' => $results['up_12_14'],'15-17 tahun' => $results['up_15_17'],'18-22 tahun' => $results['up_18_22']];
							}
						}
						// var_dump($data);
						/*echo "<pre>";
						echo print_r($wilayah);
						echo print_r($data);
						echo "</pre>";*/
						$test['info'] = $wilayah;
						$test['data'] = $data;
						echo json_encode($test);
					}

		?>
		</div>
	</body>
</html>
