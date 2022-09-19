<?php
/**
 * Defines the \TraderInteractive\Util\FileTest class
 */

namespace TraderInteractive\Util;

use PHPUnit\Framework\TestCase;
use TraderInteractive\Util\File as F;

/**
 * @coversDefaultClass \TraderInteractive\Util\File
 */
final class FileTest extends TestCase
{
    private $topLevelDirPath;
    private $topLevelFilePath;
    private $subLevelDirPath;
    private $subLevelFilePath;

    private $oldErrorReporting;

    public function setUp(): void
    {
        parent::setUp();

        $this->oldErrorReporting = error_reporting();

        $this->topLevelDirPath = sys_get_temp_dir() . '/topLevelTempDir';
        $this->topLevelFilePath = "{$this->topLevelDirPath}/topLevelTempFile";
        $this->subLevelDirPath = "{$this->topLevelDirPath}/subLevelTempDir";
        $this->subLevelFilePath = "{$this->subLevelDirPath}/subLevelTempFile";

        $this->deleteTestFiles();
    }

    //this is just for convenience, DO NOT RELY ON IT
    public function tearDown(): void
    {
        error_reporting($this->oldErrorReporting);

        $this->deleteTestFiles();
    }

    private function deleteTestFiles()
    {
        if (is_dir($this->topLevelDirPath)) {
            chmod($this->topLevelDirPath, 0777);

            if (is_file($this->topLevelFilePath)) {
                unlink($this->topLevelFilePath);
            }

            if (is_dir($this->subLevelDirPath)) {
                if (is_file($this->subLevelFilePath)) {
                    unlink($this->subLevelFilePath);
                }

                rmdir($this->subLevelDirPath);
            }

            rmdir($this->topLevelDirPath);
        }
    }

    /**
     * @test
     * @covers ::deleteDirectoryContents
     */
    public function deleteDirectoryContentsNonExistentPath()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('cannot list directory \'/some/where/that/doesnt/exist\'');
        error_reporting(0);
        F::deleteDirectoryContents('/some/where/that/doesnt/exist');
    }

    /**
     * @test
     * @covers ::deleteDirectoryContents
     */
    public function deleteDirectoryContentsEmpty()
    {
        $this->assertTrue(mkdir($this->topLevelDirPath));

        F::deleteDirectoryContents($this->topLevelDirPath);

        $this->assertTrue(rmdir($this->topLevelDirPath));
    }

    /**
     * @test
     * @covers ::deleteDirectoryContents
     */
    public function deleteDirectoryContentsWithFiles()
    {
        $this->assertTrue(mkdir($this->subLevelDirPath, 0777, true));

        file_put_contents($this->topLevelFilePath, 'hello dolly !');
        file_put_contents($this->subLevelFilePath, 'hello dolly 2!');

        F::deleteDirectoryContents($this->topLevelDirPath);

        $this->assertTrue(rmdir($this->topLevelDirPath));
    }

    /**
     * @test
     * @covers ::deleteDirectoryContents
     */
    public function deleteDirectoryContentsWithProtectedFile()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode('2');
        $this->assertTrue(mkdir($this->topLevelDirPath));

        file_put_contents($this->topLevelFilePath, 'hello dolly !');

        $this->assertTrue(chmod($this->topLevelDirPath, 0555));

        error_reporting(0);
        F::deleteDirectoryContents($this->topLevelDirPath);
    }

    /**
     * @test
     * @covers ::deleteDirectoryContents
     */
    public function deleteDirectoryContentsWithProtectedDirectory()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode('1');
        $this->assertTrue(mkdir($this->subLevelDirPath, 0777, true));

        $this->assertTrue(chmod($this->topLevelDirPath, 0555));

        error_reporting(0);
        F::deleteDirectoryContents($this->topLevelDirPath);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function deleteBasic()
    {
        $this->assertTrue(mkdir($this->topLevelDirPath));
        file_put_contents($this->topLevelFilePath, 'some text');
        F::delete($this->topLevelFilePath);
        $this->assertFalse(file_exists($this->topLevelFilePath));
    }

    /**
     * @test
     * @covers ::delete
     */
    public function deleteNonExistent()
    {
        $this->assertFalse(file_exists('/path/does/not/exist'));
        F::delete('/path/does/not/exist');
    }

    /**
     * @test
     * @covers ::delete
     */
    public function deleteDirectory()
    {
        $this->expectException(\Exception::class);
        $this->assertTrue(mkdir($this->topLevelDirPath));
        error_reporting(0);
        F::delete($this->topLevelDirPath);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function deletePathIsWhitespace()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$path is not a string or is whitespace');
        F::delete('  ');
    }

    /**
     * Verify behavior of delete() with protected file.
     *
     * @test
     * @covers ::delete
     */
    public function deleteProtectedFile()
    {
        $this->expectException(\Exception::class);
        $this->assertTrue(mkdir($this->topLevelDirPath));

        file_put_contents($this->topLevelFilePath, 'hello dolly !');

        $this->assertTrue(chmod($this->topLevelDirPath, 0555));

        error_reporting(0);
        F::delete($this->topLevelDirPath);
    }

    /**
     * verify basic behavior of deletePathIfEmpty().
     *
     * @test
     * @covers ::deletePathIfEmpty
     *
     * @return void
     */
    public function deletePathIfEmpty()
    {
        $path = "{$this->topLevelDirPath}/path/to/sub/folder";
        mkdir($path, 0755, true);
        $this->assertFileExists($path, "Unable to create '{$path}'.");
        F::deletePathIfEmpty($path, $this->topLevelDirPath);
        $this->assertFileDoesNotExist($path, "Unable to delete '{$path}'.");
    }

    /**
     * verify behavior of deletePathIfEmpty() when $deletePath does not exist.
     *
     * @test
     * @covers ::deletePathIfEmpty
     *
     * @return void
     */
    public function deletePathIfEmptyNotExists()
    {
        $path = "{$this->topLevelDirPath}/path/to/sub/folder";
        $this->assertFileDoesNotExist($path, "Unable to delete '{$path}'.");
        $this->assertNull(F::deletePathIfEmpty($path));
    }

    /**
     * verify behavior of deletePathIfEmpty() when a folder in $deletePath contains a file.
     *
     * @test
     * @covers ::deletePathIfEmpty
     *
     * @return void
     */
    public function deletePathIfEmptyNotEmpty()
    {
        $path = "{$this->topLevelDirPath}/path/to/sub/folder";
        $file = "{$this->topLevelDirPath}/path/to/file.txt";
        mkdir($path, 0777, true);
        touch($file);
        $this->assertFileExists($path, "Unable to create '{$path}'.");
        $this->assertFileExists($file, 'Unable to create text file');
        F::deletePathIfEmpty($path, $this->topLevelDirPath);
        $this->assertFileDoesNotExist("{$this->topLevelDirPath}/path/to/sub/folder");
        $this->assertFileDoesNotExist("{$this->topLevelDirPath}/path/to/sub");
        $this->assertFileExists($file, "{$file} was deleted");
        unlink($file);
        $this->assertFileDoesNotExist($file, "{$file} was not deleted");
        F::deletePathIfEmpty("{$this->topLevelDirPath}/path/to", $this->topLevelDirPath);
        $this->assertFileDoesNotExist("{$this->topLevelDirPath}/path/to");
    }
}
