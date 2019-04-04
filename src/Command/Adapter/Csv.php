<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command\Adapter;

use League\Csv\Reader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Csv implements AdapterInterface
{
    /**
     * return an array of Scorer data from a csv file
     *
     * @param $filePath
     * @return \Iterator
     */
    public function getIterator($filePath)
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($fileExtension !== 'csv' || !file_exists($filePath)) {
            throw new FileNotFoundException('Data file path incorrect.');
        }
        $reader = Reader::createFromPath($filePath);
        $reader->setHeaderOffset(0);
        $header = $reader->getHeader();

        if ($header[0] !== 'username' || $header[1] !== 'password') {
            throw new \UnexpectedValueException('CSV file header incorrectly filled.');
        }
        $iterator = $reader->getIterator();
        $iterator->rewind();
        return $iterator;
    }
}