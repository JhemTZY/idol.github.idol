<?php 
    function is_allowed_type($file_name){
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_types = array("jpg","png","gif","jpeg");
        if(in_array(strtolower($file_ext), $allowed_types)) {
            return true;
        }
        return false;
    };
    
    $uploaded_array=array();
    $path_to_upload="assets/uploads/";
    $max_file_size  = 20 * 1024 * 1024; //Max upload size 20MB
    if(isset($_FILES['files']) && !empty(array_filter($_FILES['files']['name']))){
        $i=0;
        foreach ($_FILES['files']['tmp_name'] as $key => $value) {
            if(empty($_FILES['files']['tmp_name'][$key]))
                continue;
            $file_tmpname = $_FILES['files']['tmp_name'][$key]; 
            $file_name = $_FILES['files']['name'][$key]; 
            $file_size = $_FILES['files']['size'][$key];
            $filepath = $path_to_upload."/".$file_name;
            if($file_size > $max_file_size){
                $output = array(
                    'status' => 'error',
                    'message' => "File {$file_name} exceeds uploading limit."
                );
                echo json_encode($output);
                return;
            }
            if(is_allowed_type($file_name)){ 
                if(file_exists($filepath)) { 
                    $filepath = $path_to_upload."/".time().$file_name;                      
                    if( move_uploaded_file($file_tmpname, $filepath)) {
                        $uploaded_array[$i++]=$filepath;
                    }else {
                        $output = array(
                            'status' => 'error',
                            'message' => "Some error occurred while uploadings {$file_name} {$filepath}"
                        );
                        echo json_encode($output);
                        return;  
                    } 
                }else {
                    if( move_uploaded_file($file_tmpname, $filepath)) { 
                        $uploaded_array[$i++]=$filepath; 
                    }else {
                        $output = array(
                            'status' => 'error',
                            'message' => "Some error occurred while uploading {$filepath}"
                        );
                        echo json_encode($output);
                        return;  
                    } 
                }
            }else{
                $output = array(
                    'status' => 'error',
                    'message' => 'Sorry, only images are accepted'
                );
                echo json_encode($output);
                return;
            }
        }
        if(count($uploaded_array)>0){
                $output = array(
                    'status' => 'success',
                    'files' => $uploaded_array
                );
                echo json_encode($output);
                return;
        }
    }else{
        $output = array(
            'status' => 'error',
            'message' => 'No files uploaded.'
        );
        echo json_encode($output);
    }
?>