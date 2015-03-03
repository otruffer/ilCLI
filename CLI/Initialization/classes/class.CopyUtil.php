<?php namespace Installer\Util;

class CopyUtil {

    /**
     * copied from: http://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
     *
     * Makes a recursive copy of a folder.
     *
     * @param $source string
     * @param $destination string
     */
    public function deepCopy($source, $destination){
        $dir = opendir($source);
        @mkdir($destination);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($source . '/' . $file) ) {
                    $this->deepCopy($source . '/' . $file,$destination . '/' . $file);
                }
                else {
                    copy($source . '/' . $file,$destination . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}