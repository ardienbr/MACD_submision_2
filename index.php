<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
$connectionString = DefaultEndpointsProtocol=https;AccountName=latihanazure;AccountKey=TclVzvkk34gjpJuo2lPZdTqc27Twza5LVSh3azq1A5tYHeoUfdPy+VC2hTeKL/woLLCISCDMaQklubidYULwEQ==;EndpointSuffix=core.windows.net;
$blobClient = BlobRestProxy::createBlobService($connectionString);
$containerName = "photocompetition";
	
if (isset($_POST['submit'])) {
	$fileToUpload = $_FILES["fileToUpload"]["name"];
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	echo fread($content, filesize($fileToUpload));
		
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}	
	
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>


<html>
<head>
<Title>Upload Foto</Title>
</head>
<body>
<h1>Upload Foto Lomba</h1>
 <p>Silahkan upload foto yang ingin anda lakukan penjurian</p>
 <form method="post" action="index.php" enctype="multipart/form-data" >
       <input type="file" name="fileToUpload" accept=".jpeg,.jpg" required="" />
       <input type="submit" name="submit" value="Upload" />
 </form>
 <br>
 <br>
 <br>
 <table>
	<thead>
	</thead>
	<tbody>
	<?php
				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="detailgambar.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Detail Gambar">
								</form>
							</td>
						</tr>
						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
	</tbody>
 </table>
</body>
</html>

