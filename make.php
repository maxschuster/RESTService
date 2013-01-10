    <?php
    define('EXPORT_DIR', './phar');
    define('EXPORT_FILE', EXPORT_DIR.'/rest.phar');
    define('INPUT_FOLDER', 'eu');
    
    if (file_exists(EXPORT_FILE)) {
        Phar::unlinkArchive(EXPORT_FILE);
    }
    
    echo "Building RESTService...<br />\n";
    
    if (!is_dir(EXPORT_DIR))
        mkdir (EXPORT_DIR);
    $p = new Phar(EXPORT_FILE,0);
    $p->compressFiles(Phar::GZ);
    $p->setSignatureAlgorithm (Phar::SHA1);
    $p->addFile('stub.php');
    if(is_dir(INPUT_FOLDER)) {
        echo "Targetfolder exisits<br />\n";
    } else {
        die("Targetfolder does not exisit!<br />\n");
    }
    echo "Adding files:<br />\n";
    $rd = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(INPUT_FOLDER));
    $files = array();
    foreach($rd as $file) {
        $filename = $file->getFilename();
        if ($filename{0} != '.' && !isInHiddenFolder($file->getPath())) {
            $files[$file->getPath()."/".$file->getFilename()]=$file->getPath().DIRECTORY_SEPARATOR.$file->getFilename();
            echo $filePath = str_replace('\\', '/', $file->getPath()."/".$file->getFilename());
            echo '<br />';
        }
    }
    
    $p->startBuffering();
    $p->buildFromIterator(new ArrayIterator($files));
    $p->stopBuffering();
     //echo $p->createDefaultStub('stub.php');
    $p->setStub($p->createDefaultStub('stub.php'));
    $p = null;
    
    echo "Building RESTService...Finished<br />\n";
    
    if (isset($_GET['download'])) {
        echo '<p>Download will start shortly...</p>';
        echo '<meta http-equiv="refresh" content="1; URL=download.php">';
    }
    
    function isInHiddenFolder($folder) {
        $folderArr = explode(DIRECTORY_SEPARATOR, $folder);
        
        foreach ($folderArr as $f) {
            if ($f{0} == '.') {
                return true;
            }
        }
        
        return false;
    }
?>