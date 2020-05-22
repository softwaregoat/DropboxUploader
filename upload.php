<?php
require "vendor/autoload.php";
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;

//Configure Dropbox Application
function UploadFile($filename, $filepath, $folder = '')
{
    $app = new DropboxApp("yurm53g72r2mw8r", "vid9hfk6lxwkc96", "u2Kux-L2TNAAAAAAAAFPpUfPL5ZKs-v7JZWKbX-HXfKArWoUi5ZZPE7JObaHzcnq");
    $dropbox = new Dropbox($app);
    try {

        $dropboxFile = new DropboxFile($filepath);
        if ($folder != '') {
            try {
                $dropbox->createFolder('/attachments/' . $folder);
            } catch (DropboxClientException $e) {
                echo $e->getMessage();
            }
        }

        // Upload the file to Dropbox
        $uploadedFile = $dropbox->upload($dropboxFile, "/attachments/$folder/" . $filename, ['autorename' => true]);


        // File Uploaded
        echo $uploadedFile->getPathDisplay();
    } catch (DropboxClientException $e) {
        echo $e->getMessage();
    }
}


$strJsonFileContents = file_get_contents("json.json");
$array = json_decode($strJsonFileContents, true);

foreach ($array as $item) {
    $folder = $item['ts'];
    $attach = $item['msg']['attachments'];
    foreach ($attach as $i) {
        $file_name = $i['name'];
        $file_type = $i['type'];
        $file_content = $i['content'];
        $image_base64 = base64_decode($file_content);
        file_put_contents($file_name, $image_base64);
        UploadFile($file_name, $file_name, $folder);
    }
}
