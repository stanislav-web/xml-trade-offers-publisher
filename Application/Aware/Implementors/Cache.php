<?php
namespace Application\Aware\Implementors;

use Application\Exceptions\InternalServerErrorException;
use Application\Exceptions\NotFoundException;

/**
 * Class Cache
 *
 * @package Application\Aware\Implementors
 */
trait Cache {

    /**
     * @var int $cacheDirPermission
     */
    private $cacheDirPermission = 0777;

    /**
     * @var int $cacheFilePermission
     */
    private $cacheFilePermission = 0666;

    /**
     * Default cache status
     *
     * @var bool $isCacheEnabled
     */
    protected $isCacheEnabled = false;

    /**
     * Cache lifetime
     *
     * @var int $cacheTtl
     */
    protected $cacheTtl = 0;

    /**
     * Cache directory
     *
     * @var string $cacheDirectory
     */
    protected $cacheDirectory = '';

    /**
     * Cache file
     *
     * @var string $cacheFile
     */
    protected $cacheFile = '';

    /**
     * Create cache directory
     *
     * @throws InternalServerErrorException
     */
    protected function createCacheDirectory() {

        if(file_exists($this->cacheDirectory) === false) {
            if(mkdir($this->cacheDirectory, $this->cacheDirPermission, true) === false) {
                throw new InternalServerErrorException('Could not create cache directory :'.$this->cacheDirectory, InternalServerErrorException::CODE);
            }
        }
    }

    /**
     * Check view in cache
     *
     * @reurn boolean
     */
    protected function isViewCached() {

        if(file_exists($this->cacheFile) === true) {

            return $this->isCacheActual();
        }
        return false;
    }

    /**
     * Remove file from cache
     *
     * @throws NotFoundException
     */
    public function cacheRemove() {
        if(file_exists($this->cacheFile) === false) {
            throw new NotFoundException('Can not destroy expires cached file: '.$this->cacheFile, NotFoundException::CODE);
        }

        unlink($this->cacheFile);
    }

    /**
     * Check cache actuality
     *
     * @return bool
     */
    public function isCacheActual() {

        if((filemtime($this->cacheFile) > (time() - $this->cacheTtl))) {
            return true;
        }

        // remove from cache if expires
        $this->cacheRemove();
        return true;
    }

    /**
     * Cache saving
     *
     * @return bool
     * @throws InternalServerErrorException
     */
    public function cache($content) {

        $result = file_put_contents($this->cacheFile, $content, LOCK_EX);

        if($result === false) {
            throw new InternalServerErrorException('Can not store cache content to file: '.$this->cacheFile, InternalServerErrorException::CODE);
        }
        return true;
    }
}