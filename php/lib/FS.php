<?php
  class FS{
    public static function create_tempfile($content,$name){
        $temp_file = fopen(tempnam("/tmp",$name),"w");
        if(!$temp_file){
            Util::output_line("Error creating file");
            return false;
        }
        $write_result=fwrite($temp_file,$content);
        if(!$write_result){
            Util::output_line("Error creating file");
            return false;
        }
        return stream_get_meta_data($temp_file)['uri'];
    }

    public static function upload_file($sftp,$src,$dest){
        $remote_stream = fopen('ssh2.sftp://' . intval($sftp) . $dest, 'w');
        if(!$remote_stream){
            return false;
        }
        $local_stream = fopen($src,"r");
        if(!$local_stream){;
            return false;
        }
        $writtenBytes = stream_copy_to_stream($local_stream,$remote_stream);
        fclose($remote_stream);
        fclose($local_stream);
        return $writtenBytes;
    }
    
    public static function upload_files($sftp,$files){
        foreach ($files as $file){
            $dest=$file['dest'];
            Util::output_line("Uploading file: $dest");
            $uploadResult=FS::upload_file($sftp,$file['src'],$file['dest']);
            if(!$uploadResult){
                Util::output_line("Upload failed");
                return false;
            }
            Util::output_line("Uploaded!");
        }
        return true;
    }
  }
?>