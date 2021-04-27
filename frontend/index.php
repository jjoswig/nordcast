<?php
require 'vendor/autoload.php'; // include Composer's autoloader

use MicrosoftAzure\Storage\Blob\BlobRestProxy;


/* Storage Account initialization */
$connectionString = 'DefaultEndpointsProtocol=https;AccountName=nordcastvideos;AccountKey=nBz6fRxIG92qnN6iY+ULSODXTlWWlLoWerEuvLFEYzEMUH041Xnb2KOe2Fhj6rLcGlF7zkJyNiEKvjximP4+Ow==';
$blobClient = BlobRestProxy::createBlobService($connectionString);

/* CosmosDB initialization  */
$user = "ncastdb";
$pwd = "YHfwlwQTEeY3QgxcNwnWuuCvqhR4c50MNne6Uhk33CpJ7nkFoPIU758J6WPP89PYmPYMqPudLpRHT6sXjftJoA==";
$client = new MongoDB\Client("mongodb://${user}:${pwd}@ncastdb.mongo.cosmos.azure.com:10255/?ssl=true&replicaSet=globaldb&retrywrites=false&maxIdleTimeMS=120000&appName=@ncastdb@West Europe");


$collection = $client->videos->videos;

if(isset($_FILES['file'])) {
    $target_dir = "uploads/";
    $basename = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $basename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $result = $collection->insertOne( [ 
            'name' => $_POST['name'], 
            'region' => 'europe', 
            'url' => 'https://nordcastvideos.blob.core.windows.net/videos/' . $basename,
            'description' => $_POST['description']
        ] );

        try {
            //Upload blob
            $content = fopen($_FILES["file"]["tmp_name"], "r");
            $blobClient->createBlockBlob("videos", $basename, $content);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message.PHP_EOL;
        }

    }
}

$videos = $collection->find( [ 'region' => 'europe' ] );

?>
<!DOCTYPE html>


<html lang="de">

<head>
    <title>NordCast</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-7">
                <h1>NordCast Ltd. - Your streaming portal</h1>
                <h2>Current videos</h2>

                <div class="container">
                    <div class="row">
                        <?php foreach ($videos as $video) { ?>
                            <div class="col-6" style="text-align: center;">
                                <video width="280" height="200" controls>
                                <source src="<?php echo $video['url']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                                </video>
                                <div>
                                    <b><?php echo $video['name']; ?></b><br />
                                    <?php echo $video['description']; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Upload new video</h2>

        <div class="row">
            <div class="col-7">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="exampleInputVideo1" class="form-label">Select video to upload:</label>
                        <input name="file" type="file" class="form-control" id="exampleInputVideo1" aria-describedby="videoHelp">
                        <div id="videoHelp" class="form-text">Upload a video in format mp4 or similar</div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputName1" class="form-label">Video name:</label>
                        <input name="name" type="text" class="form-control" id="exampleInputName1" aria-describedby="videoNameHelp">
                        <div id="videoNameHelp" class="form-text">Give a meaningful name</div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputDescription1" class="form-label">Video description:</label>
                        <textarea name="description" class="form-control" id="exampleInputDescription1" rows="7" aria-describedby="videoDescriptionHelp"></textarea>
                        <div id="videoDescriptionHelp" class="form-text">Give a meaningful video description</div>
                    </div>

                <input type="submit" value="Upload video" name="submit">
                </form>
            </div>
        </div>
    </div>

</body>

</html>