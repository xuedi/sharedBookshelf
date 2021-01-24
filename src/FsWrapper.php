<?php

namespace SharedBookshelf;

// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

use SharedBookshelf\Exceptions\FsWrapperException;

class FsWrapper
{
    public function filemtime(string $fileName): int
    {
        $result = filemtime($fileName);
        if ($result === false) {
            throw new FsWrapperException("Could not get time of file '$fileName'");
        }
        return $result;
    }

    public function sha1_file(string $fileName): string
    {
        $result = sha1_file($fileName);
        if ($result === false) {
            throw new FsWrapperException("Could not get sha1 of file '$fileName'");
        }
        return $result;
    }

    public function filesize(string $fileName): int
    {
        $result = filesize($fileName);
        if ($result === false) {
            throw new FsWrapperException("Could not get size of file '$fileName'");
        }
        return $result;
    }

    public function isReadable(string $filename): bool
    {
        return is_readable($filename);
    }

    public function file_get_contents(string $fileName): string
    {
        $result = file_get_contents($fileName);
        if ($result === false) {
            throw new FsWrapperException("Could not get contents of file '$fileName'");
        }
        return $result;
    }

    public function file_put_contents(string $fileName, string $data): void
    {
        $result = file_put_contents($fileName, $data);
        if ($result === false) {
            throw new FsWrapperException("Could not put contents to file '$fileName'");
        }
    }

    public function rename(string $oldName, string $newName): bool
    {
        return rename($oldName, $newName);
    }

    public function unlink(string $filename): bool
    {
        return unlink($filename);
    }

    public function file_exists(string $file): bool
    {
        return file_exists($file);
    }

    public function copy(string $sourceFile, string $dest): bool
    {
        return copy($sourceFile, $dest);
    }
}
// @codeCoverageIgnoreEnd