<?php
function create_new_custom_logger_file($_custom_log_msg, $_custom_log_dir){
    $label = time().".log";
    fopen($_custom_log_dir.$label, 'a');
    error_log($_custom_log_msg, 3, $_custom_log_dir.$label);
}
function custom_logger($data) {
    $_custom_log_dir = __DIR__."/includes/logs/"; // path to log dir
    $_custom_max_log_size = 1034000; // in bytes
    $_custom_log_files = [];
    // format the $data at this point if desired
    $_custom_log_msg = "Timestamp:".time()."\n".json_encode($data)."\n";

    if (is_dir($_custom_log_dir)) {
        if ($dh = opendir($_custom_log_dir)) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match("/(.*)\.log/", $file)) {
                    $_custom_log_files[] = $file;
                }
            }
            closedir($dh);
        }
    }

    if (!empty($_custom_log_files)){
        foreach($_custom_log_files as $log_file){
            $working_file = filesize($_custom_log_dir.$log_file) < $_custom_max_log_size ? $log_file : null;
        }
        if ($working_file){
            error_log($_custom_log_msg, 3, $_custom_log_dir.$working_file);
        } else {
            create_new_custom_logger_file($_custom_log_msg, $_custom_log_dir);
        }
    } else {
        create_new_custom_logger_file($_custom_log_msg, $_custom_log_dir);
    }
}
?>
