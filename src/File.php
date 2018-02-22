<?php
/**
 * Defines the \TraderInteractive\Util\File class.
 */

namespace TraderInteractive\Util;

/**
 * Class of static file utility functions.
 */
final class File
{
    /**
     * Recursively deletes directory contents
     *
     * @param string $directoryPath absolute path of directory
     *
     * @return void
     *
     * @throws \Exception if file cannot be deleted
     * @throws \Exception if directory cannot be deleted
     * @throws \Exception if $directoryPath cannot be listed
     */
    public static function deleteDirectoryContents(string $directoryPath)
    {
        $paths = scandir($directoryPath);
        if ($paths === false) {
            throw new \Exception("cannot list directory '{$directoryPath}'");
        }

        foreach ($paths as $path) {
            if ($path === '.' || $path === '..') {
                continue;
            }

            $fullPath = "{$directoryPath}/{$path}";

            if (is_dir($fullPath)) {
                self::deleteDirectoryContents($fullPath);//RECURSIVE CALL
                if (!rmdir($fullPath)) {
                    throw new \Exception("cannot delete '{$fullPath}'", 1);
                }
            } else {
                if (!unlink($fullPath)) {
                    throw new \Exception("cannot delete '{$fullPath}'", 2);
                }
            }
        }
    }

    /**
     * Deletes the given file specified by $path
     *
     * @param string $path path to the file to be deleted
     *
     * @return void
     *
     * @throws \InvalidArgumentException if $path is whitespace
     * @throws \Exception if unlink returns false
     */
    public static function delete(string $path)
    {
        if (trim($path) === '') {
            throw new \InvalidArgumentException('$path is not a string or is whitespace');
        }

        if (!file_exists($path)) {
            return;
        }

        try {
            if (unlink($path) === false) {
                throw new \Exception("unlink returned false for '{$path}'");
            }
        } catch (\Exception $e) {
            if (file_exists($path)) {
                throw $e;
            }
        }
    }

    /**
     * Recursively deletes the given directory path until a non-empty directory is found or the $stopAtPath is reached.
     *
     * @param string $deletePath The empty directory path to delete.
     * @param string $stopAtPath The point at which the deletion should stop. Defaults to /.
     *
     * @return void
     */
    public static function deletePathIfEmpty(string $deletePath, string $stopAtPath = '/')
    {
        if (!file_exists($deletePath)) {
            return;
        }

        if (realpath($deletePath) === realpath($stopAtPath)) {
            return;
        }

        $handle = dir($deletePath);
        for ($entry = $handle->read(); $entry !== false; $entry = $handle->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            //dir not empty
            $handle->close();
            return;
        }

        rmdir($deletePath);
        $handle->close();

        //RECURSION!!!
        self::deletePathIfEmpty(dirname($deletePath), $stopAtPath);
    }
}
