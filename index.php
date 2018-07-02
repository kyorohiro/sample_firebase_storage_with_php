<!DOCTYPE html>
<html>
<body>
<form action="#" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<?php
    require __DIR__.'/vendor/autoload.php';
    use Kreait\Firebase\Factory;
    use Kreait\Firebase\ServiceAccount;
    
    $serviceAccount = 
        ServiceAccount::fromJsonFile('/home/ec2-user/environment/c/test-92dd1-firebase-adminsdk-y7hsj-164ad4ae47.json');

    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->create();
    $storage = $firebase->getStorage();
    $bucket = $storage->getBucket();
//    $filesystem = $storage->getFilesystem();

    if(isset($_FILES['fileToUpload']['tmp_name'])) {
        $bucket->upload(
            file_get_contents($_FILES['fileToUpload']['tmp_name']), [
                'name' => $_FILES['fileToUpload']['name'],
                'predefinedAcl' => 'publicRead'
            ]
        );

        // https://firebase-php.readthedocs.io/en/latest/index.html
        // https://github.com/GoogleCloudPlatform/google-cloud-php
        // https://github.com/GoogleCloudPlatform/google-cloud-php-storage
        // https://packagist.org/packages/google/cloud-storage
        $object = $bucket->object($_FILES['fileToUpload']['name']);
        $timestamp = (new DateTime('tomorrow'))->getTimestamp();
        $url = $object->signedUrl($timestamp);
        echo "URL : ". $url;
    }
?>
</body>
</html>
