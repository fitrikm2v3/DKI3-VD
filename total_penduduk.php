<html>
	<head>
	<title>Total Penduduk</title>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		<script src="/amcharts/amcharts.js" type="text/javascript"></script>
    	<script src="/amcharts/pie.js" type="text/javascript"></script>
    	<script src="/amcharts/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
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

	<script type="text/javascript">
		AmCharts.makeChart("chartdiv", {
        "type": "pie",
        "dataLoader": {
        "url": "data_tot_pen.php",
        "format": "json"
        },
        "titleField": "wilayah",
        "valueField": "penduduk",
                
        "colors" : ["#FFCC80", "#FFB74D", "#FFA726", "#FF9800", "#FB8C00", "#F57C00", "#EF6C00", "#E65100"],
        "balloonText": "[[title]]<br><span style='font-size:10px'><b>[[views]]</b> ([[percents]]%)</span>",
            "legend": {
                "useGraphSettings": true,
                "align": "left",
                "maxColumns":4,
                "align":"center",
                "markerType": "circle"
                }
        });
	</script>
	<body>
		<form method="POST">
			<div class="col-md-2">
				<label style="font-size:20px" >Wilayah:</label>
				<select name="country" id="wilayah-list" class="form-control selectpicker" onChange="getKec(this.value);">
				<option value="kosong">Select Wilayah</option>

				<?php
					$sql1="SELECT DISTINCT id_wilayah,nama_wilayah FROM datakota ORDER BY nama_wilayah";
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
								SUM(jumlah_penduduk) total_penduduk,
								SUM(jumlah_rw) jumlah_rw FROM datakota WHERE id_wilayah = '$wil' GROUP BY nama_kecamatan";
						}

						if($wil != "" && $kec != ""){
							$sql = "SELECT id_wilayah,nama_wilayah,id_kecamatan,nama_kecamatan,id_kelurahan,nama_kelurahan,
								SUM(jumlah_penduduk) total_penduduk,
								SUM(jumlah_rw) jumlah_rw FROM datakota WHERE id_wilayah = '$wil'
								AND id_kecamatan = '$kec' GROUP BY nama_kelurahan"; 
						}

						if($kec != "" && $kel != ""){
							$sql = "SELECT id_wilayah,nama_wilayah,id_kecamatan,nama_kecamatan,id_kelurahan,nama_kelurahan,
								SUM(jumlah_penduduk) total_penduduk,
								SUM(jumlah_rw) jumlah_rw FROM datakota WHERE id_kecamatan = '$kec'
								AND id_kelurahan = '$kel' GROUP BY nama_kelurahan"; 
						}

						if($wil == "kosong" && $kec == "kosong" && $kel == "kosong"){
							echo "Nothing Selected";
						}

						$query=mysqli_query($connect,$sql);							
							while($results = mysqli_fetch_array($query)) {
								// extract($results);
								if($wil != "" && $kec == ""){
									$wilayah = ['wilayah' => $results['nama_wilayah']];
									$data[] = ['kecamatan' => $results['nama_kecamatan'], 
									'penduduk' => $results['total_penduduk']];
								}
								if ($wil != "" && $kec != "") {
									$wilayah = ['wilayah' => $results['nama_wilayah'], 'kecamatan' => $results['nama_kecamatan']];
									$data[] = ['kelurahan' => $results["nama_kelurahan"], 'penduduk' => $results['total_penduduk']];
								}
								if ($wil != "" && $kec != "" && $kel != "") {
									$data = ['kelurahan' => $results["nama_kelurahan"], 'penduduk' => $results['total_penduduk']];
								}

								/* if($wil != "" && $kec != "" && $kel != ""){
								$data['kecamatan'] = $results['nama_kecamatan'];
								$data['kelurahan'] = $results['nama_kelurahan'];
								$data['penduduk'] = $results['total_penduduk'];
								} */
								//$data['jumlah rw'] = $results['jumlah_rw'];
							}
						// var_dump($data);
						// echo json_encode($data);
						$test['info'] = $wilayah;
						$test['data'] = $data;
						// echo "<pre>";
						// echo print_r($test);
						// echo "</pre>";

						echo json_encode($test);
					}

		?>
		</div>
		<div class="col-md-12">
		<h2 align="center">Total Penduduk Per Wilayah Kota Bandung</h5>
		<div id="chartdiv" style="width: 100%; height: 500px;"></div>
		</div>
	</body>
</html>
