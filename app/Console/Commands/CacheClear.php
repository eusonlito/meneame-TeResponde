<?php
namespace App\Console\Commands;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Illuminate\Console\Command;

class CacheClear extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cache:clear';

    /**
     * @var string
     */
    protected $description = 'Clear cache folder';

    /**
     * @var array
     */
    private $folders = ['curl', 'html'];

    /**
     * @var integer
     */
    private $older = 0;

    /**
     * @return void
     */
    public function handle()
    {
        $this->info('START: '.date('Y-m-d H:i:s'));

        foreach ($this->folders as $folder) {
            $folder = storage_path('cache/'.$folder);

            $this->deleteFiles($folder);
            $this->deleteEmptyFolders($folder, false);
        }

        $this->info('END: '.date('Y-m-d H:i:s'));
    }

    /**
    * @param string $folder
    *
    * @return void
    */
    private function deleteFiles($folder)
    {
        if (!is_dir($folder)) {
            return false;
        }

        foreach ($this->getContentsRecursive($folder) as $file) {
            if ($this->isOldFile($file)) {
                unlink($file);
            }
        }
    }

    /**
    * @param string  $folder
    * @param boolean $current
    *
    * @return boolean
    */
    private function deleteEmptyFolders($folder, $current = false)
    {
        $empty = true;

        foreach (glob($folder.'/*') as $file) {
            if (!is_dir($file) || !$this->deleteEmptyFolders($file, true)) {
                $empty = false;
            }
        }

        if ($empty && $current) {
            rmdir($folder);
        }

        return $empty;
    }

    /**
    * @param string  $folder
    *
    * @return array
    */
    private function getContentsRecursive($folder)
    {
        if (!is_dir($folder)) {
            return array();
        }

        $iterator = new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS);

        return array_keys(iterator_to_array(new RecursiveIteratorIterator($iterator)));
    }

    /**
    * @param string  $file
    *
    * @return boolean
    */
    private function isOldFile($file)
    {
        return is_file($file) && (filemtime($file) < strtotime('-'.$this->older.' days'));
    }
}
