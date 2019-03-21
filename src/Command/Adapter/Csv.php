<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command\Adapter;

use http\Exception\UnexpectedValueException;
use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class Csv implements ProviderInterface
{
    /**
     * return an array of Scorer data from a csv file
     *
     * @param $filePath
     * @param $symfonyStyle
     * @return iterator
     */
    public function getIterator($filePath, $symfonyStyle)
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($fileExtension !== 'csv' || !file_exists($filePath)) {
            $symfonyStyle->warning('Data file path incorrect.');
            throw new FileNotFoundException('Data file path incorrect.');
        }
        $reader = Reader::createFromPath($filePath, 'r');
        $reader->setHeaderOffset(0);
        $header = $reader->getHeader();

        if ($header[0] !== 'username' || $header[1] !== 'password') {
            $symfonyStyle->warning('CSV file header incorrectly filled.');
            throw new UnexpectedValueException();
        }
        $iterator = $reader->getIterator();
        $iterator->rewind();
        return $iterator;
    }
}