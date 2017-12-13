<?php 
include 'connectdb.php';

$sql = "SELECT nama_wilayah, SUM(jumlah_penduduk) total_penduduk FROM datakota GROUP BY nama_wilayah";
$query=mysqli_query($connect,$sql);	
while ($results = mysqli_fetch_array($query)) {
	$data[] = ['wilayah' => $results['nama_wilayah'], 'penduduk' => $results['total_penduduk']];
}
echo json_encode($data);
?>