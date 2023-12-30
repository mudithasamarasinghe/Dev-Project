<!DOCTYPE html>
<html>
    <head>
        <title>PHP: Upload resumé</title>
        <meta charset='utf-8' />
    </head>
    <body>
        <form method='post' enctype='multipart/form-data'>
            <input type='file' name='img' />
            <input type='submit' />
        </form>
        <?php
		
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/avatar-'.'1'.'.png';
			$dir_path =base_app. $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png','image/jpeg');
			if(!in_array($type,$allowed)){
				$resp['msg'].=" But Image failed to upload due to invalid file type.";
			}else{
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending( $t_image, false );
				imagesavealpha( $t_image, true );
				$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				if($gdImg){
						if(is_file($dir_path))
						unlink($dir_path);
						$uploaded_img = imagepng($t_image,$dir_path);
						imagedestroy($gdImg);
						imagedestroy($t_image);
				}else{
				$resp['msg'].=" But Image failed to upload due to unkown reason.";
				}
			}
			/*
			if(isset($uploaded_img)){
				$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
				if($id == $this->settings->userdata('id')){
						$this->settings->set_userdata('avatar',$fname);
				}
			}
			*/
		}	
		
			
			
		}
		
		/*
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$resp['msg'].=" I am in ifn.";
			$fname = 'uploads/avatar-'.$id.'.png';
			$dir_path =base_app. $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png','image/jpeg');
			if(!in_array($type,$allowed)){
				$resp['msg'].=" But Image failed to upload due to invalid file type.";
			}else{
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending( $t_image, false );
				imagesavealpha( $t_image, true );
				$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				if($gdImg){
						if(is_file($dir_path))
						unlink($dir_path);
						$uploaded_img = imagepng($t_image,$dir_path);
						imagedestroy($gdImg);
						imagedestroy($t_image);
				}else{
				$resp['msg'].=" But Image failed to upload due to unkown reason.";
				}
			}
			if(isset($uploaded_img)){
				$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
				if($id == $this->settings->userdata('id')){
						$this->settings->set_userdata('avatar',$fname);
				}
			}
		}
		*/
	
		/*
            if( $_SERVER['REQUEST_METHOD']=='POST' ){

                $directory='../uploads/';
                if( !file_exists( $directory ) ) mkdir( $directory, 0777, true );

                $random=uniqid();
                $status=false;

                if( !empty( $_FILES['cv'] ) ){

                    $obj=(object)$_FILES['cv'];
                    $name=$obj->name;
                    $tmp=$obj->tmp_name;
                    $ext=strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );
                    $allowed=array('gif','png','jpeg');



                    if( is_uploaded_file( $tmp ) && in_array( $ext, $allowed ) ){

                        $filename=basename( $name ) . $random . '_cv.' . $ext;

                        $filepath=$directory . '/' . $filename;

                        $status=move_uploaded_file( $tmp, $filepath );
                    }

                    echo $status ? 'moved' : 'not moved';
                }
            }
			*/
        ?>
</body>
</html>