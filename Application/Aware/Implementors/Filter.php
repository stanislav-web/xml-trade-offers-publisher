<?php
namespace Application\Aware\Implementors;

/**
 * Class Filter
 *
 * @package Application\Aware\Implementors
 */
trait Filter {

    /**
     * Filtering request URI
     *
     * @param $url
     * @return array ['path', 'query']
     */
    public function filterRequestUrl($url) {

        // parse path
        $path = trim(parse_url($url, PHP_URL_PATH), '/');
        $path_chunks = preg_split('/\/+/', $path);
        $chunks = [];

        for($i = 0, $count = count($path_chunks); $i < $count; $i += 2) {
            $chunks[$path_chunks[$i]] = trim(strip_tags($path_chunks[$i + 1]));
        }
        // parse query string
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        return [
            'path' => $chunks,
            'query'=> array_map(function($v) {
                return trim(strip_tags($v));
            },$query)
        ];
    }

    /**
     * Get date timestamp
     *
     * @param string | int $date
     * @return string
     * @throws \Exception
     */
    public function dateFilterTimestamp($date) {

        if($date != null) {

            try {
                if(is_numeric($date) === false) {
                    $date = (new \DateTime($date))->getTimestamp();
                }
                return (int)$date;

            } catch (\RuntimeException $e) {
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }
    }
}