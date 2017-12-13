<!DOCTYPE html>
<html>
	<head>
		<title>Data Kota</title>
	</head>
		<script type="text/javascript"></script>
	<body>
		<?php
			require_once("connectdb.php");


			$select ="SELECT * FROM kelurahan WHERE idkecamatan = '" . $_POST["idkecamatan"] . "' ORDER BY namakelurahan";
			$query=mysqli_query($connect,$select);

		?>
		<option value="">Select Kelurahan</option>
		<?php
			while($results = mysqli_fetch_array($query)) {
		?>
		<option value="<?php echo $results['idkelurahan']; ?>"><?php echo $results['namakelurahan']; ?></option>
		<?php
			}
		?>
	</body>
</html>
