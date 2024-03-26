<?php

        $photo = $_FILES["txt-file"];

        $photoName = $photo["name"];
        $photoSize = $photo["size"];
        $tmpName = $photo["tmp_name"];

        $imgName = mt_rand(100000,999999);
        $ext = pathinfo($photoName,PATHINFO_EXTENSION);
        $newName = time().$imgName.'.'.$ext;
        move_uploaded_file($tmpName,"./img/".$newName);
        $msg["imgName"]= $newName;
        echo json_encode($msg);
?>