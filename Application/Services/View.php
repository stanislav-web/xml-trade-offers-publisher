<?php
namespace Application\Services;

use Application\Exceptions\NotFoundException;
use Application\Aware\Implementors\Cache;

/**
 * Class View
 *
 * @package Application\Services
 */
class View {

    /**
     * File source template
     *
     * @var string $file
     */
    protected $file;

    /**
     * FileType
     *
     * @var string $fileType
     */
    protected $fileType;

    /**
     * Output header
     *
     * @var string $header
     */
    protected $header;

    /**
     * Setting values
     *
     * @var array $values
     */
    protected $values = [];

    use Cache;

    /**
     * Setup current template from request params
     *
     * @param array   $config
     * @param string  $shop
     * @param string  $fileType
     */
    public function __construct(array $config, $shop, $fileType) {

        // view cache setup
        $this->isCacheEnabled   = $config['cache']['enable'];
        $this->cacheTtl         = $config['cache']['ttl'];
        $this->cacheDirectory   = $config['cache']['directory'].DIRECTORY_SEPARATOR.$shop;

        $this->fileType         = $fileType;
        $this->file             = $config['templates'][$this->fileType];
        $this->header           = $config['headers'][$this->fileType];

    }

    /**
     * Is template cached
     *
     * @return boolean
     */
    public function isCached() {

        if($this->isCacheEnabled === true) {

            $this->cacheFile = $this->cacheDirectory.DIRECTORY_SEPARATOR.basename($this->file);

            if (file_exists($this->cacheFile) === false) {

                // create cache directory
                $this->createCacheDirectory();
                return $this->isViewCached();
            }
            // check file time
            return $this->isCacheActual();
        }

        return false;
    }

    /**
     * Setup variables
     *
     * @param string $key
     * @param string $value
     * @return View
     */
    public function set($key, $value) {

        $this->values[$key] = $value;
        return $this;
    }

    /**
     * Get template variables (if non exist will create new)
     */
    public function __get($name) {
        if(isset($this->values[$name])) return $this->values[$name];
        return '';
    }

    /**
     * Output content
     *
     * @return mixed|string
     */
    public function output() {

        if(file_exists($this->file) === false) {
            throw new NotFoundException('Template '.$this->file.' does not found');
        }

        header($this->header);

        if(file_exists($this->cacheFile)) {
            $this->file = $this->cacheFile;
        }
        ob_start();
        include $this->file;
        $data = ob_get_contents();
        return $data;
    }
}