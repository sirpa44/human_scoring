<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command\FormatManager;

use App\Command\Adapter\CsvManager;

class FormatFactory
{
    protected $formats;
    protected $csvManager;

    public function __construct(array $formats)
    {
        $this->formats = $formats;
        $this->csvManager = new CsvManager();
    }

    /**
     * return an instance of adapter
     *
     * @param $format
     * @return instance
     * @throws \Exception
     */
    public function getInstance($format)
    {
        $format = strtolower($format);
        if (!in_array($format, $this->formats)) {
            throw new \Exception("format invalid ");
        }
        $class =  $format . 'Manager';
        return $this->$class;
    }

}