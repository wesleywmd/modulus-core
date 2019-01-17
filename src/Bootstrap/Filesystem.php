<?php
namespace Modulus\Bootstrap;

class Filesystem
{
    public function getIsWindows()
    {
        return strncasecmp(PHP_OS, "WIN", 3) === 0;
    }

    /**
     * @param string $start
     * @param array $pieces
     * @return string
     */
    public function getLocation($start, $pieces)
    {
        foreach( $pieces as $subdirectory ) {
            $start .= DIRECTORY_SEPARATOR . $subdirectory;
        }
        return $start;
    }

    /**
     * @return mixed
     * @throws FilesystemException
     */
    public function getHomeRoot()
    {
        if( isset($_SERVER["HOME"]) ) {
            return $_SERVER["HOME"];
        } elseif($_SERVER["HOMEPATH"] ) {
            return $_SERVER["HOMEPATH"];
        }
        throw new FilesystemException("No home path found.");
    }
}