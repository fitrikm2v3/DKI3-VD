<!DOCTYPE html>
<html>
	<head>
		<title>Data Kota</title>
	</head>
		<script type="text/javascript"></script>
	<body>
		<?php
			require_once("connectdb.php");


			$select ="SELECT * FROM kecamatan WHERE idwilayah = '" . $_POST["idwilayah"] . "' ORDER BY namakecamatan";
			$query=mysqli_query($connect,$select);

		?>
		<option value="">Select Kecamatan</option>
		<?php
			while($results = mysqli_fetch_array($query)) {
		?>
		<option value="<?php echo $results['idkecamatan']; ?>"><?php echo $results['namakecamatan']; ?></option>
		<?php
			}
		?>
	</body>
</html>
