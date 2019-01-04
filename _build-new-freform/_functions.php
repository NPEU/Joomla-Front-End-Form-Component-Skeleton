<?php
/**
 * copy_dir()
 *
 * @param str $source
 * @param strin $destination
 * @return bool
 * @access public
 */
function copy_dir($source = '', $destination = '')
{
    if (!is_dir($source)) {
        trigger_error('Function \'copy_dir\' expects argument 1 to be a valid directory', E_USER_ERROR);
        return false;
    }
    if (!is_dir($destination)) {
        mkdir($destination);
    }
    if ($handle = opendir($source)) {
        $num = 0;
        while ($file = readdir($handle)) {
            if (($file != '.') && ($file != '..')) {
                $source_file = $source . '/' . $file;
                $destination_file = $destination . '/' . $file;
                if (is_file($source_file)) {
                    if (copy($source_file, $destination_file)) {
                        touch($destination_file, filemtime($source_file));
                        $num++;
                    } else {
                        trigger_error('Could not copy file: ' . $source_file . '.', E_USER_ERROR);
                    }
                } elseif (is_dir($source_file)) {
                    $num += copy_dir($source_file, $destination_file);
                }
            }
        }
        closedir($handle);
        return $num;
    } else {
        trigger_error('Unable to open directory: ' . $directory . '.', E_USER_ERROR);
        return false;
    }
}


function perform_renames($source = '', $file_rename = array(), $content_rename = array())
{

    if ($handle = opendir($source)) {
        $num = 0;
        while ($file = readdir($handle)) {
            if (($file != '.') && ($file != '..')) {
                $source_file = $source . '/' . $file;
                echo 'Performing renames for: ' . $source_file . "\n";
                if (strpos($file, $file_rename[0]) !== false) {
                    
                    $new_name = str_replace($file_rename[0], $file_rename[1], $source_file);
                    rename($source_file, $new_name);
                    $num++;
                    $source_file = $new_name;
                }
                if (!empty($content_rename) && is_file($source_file)) {
                    $t = file_get_contents($source_file);
                    foreach ($content_rename as $from => $to) {
                        $t = str_replace($from, $to, $t);
                    }
                    file_put_contents($source_file, $t);
                }
                if (is_dir($source_file)) {
                    perform_renames($source_file, $file_rename, $content_rename);
                }
                /*if (is_file($source_file)) {
                    rename($source_file, str_replace($file_rename[0], $file_rename[1], $source_file));
                } elseif (is_dir($source_file)) {
                    rename($source_file, str_replace($file_rename[0], $file_rename[1], $source_file));
                    $num += perform_renames($source_file, $file_rename, $content_rename);
                    
                }*/
            }
        }
        closedir($handle);
        return $num;
    } else {
        trigger_error('Unable to open directory: ' . $directory . '.', E_USER_ERROR);
        return false;
    }
}
?>