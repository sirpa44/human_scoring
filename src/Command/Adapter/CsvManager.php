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

class CsvManager
{


    /**
     * return an array of Scorer data from a csv file
     *
     * @param $filePath
     * @param $input
     * @param $output
     * @return array
     */
    public function getData($filePath, $input, $output)
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileExtension = substr(strrchr($filePath, '.'), 1);
        if ($fileExtension !== 'csv' || !file_exists($filePath)) {
            $output->writeln('Data file path incorrect.');
            throw new FileNotFoundException('Data file path incorrect.');
        }
        $reader = Reader::createFromPath($filePath, 'r');
        $reader->setHeaderOffset(0);
        $header = $reader->getHeader();

        if ($header[0] !== 'username' || $header[1] !== 'password') {
            $output->writeln('CSV file header incorrectly filled.' );
            throw new UnexpectedValueException();
        }
        $iterator = $reader->getIterator();
        $iterator->rewind();
//        while ($iterator->valid()) {
//            $scorer = $iterator->current();
//            if ($scorer['password'] === null) {
//                $output->writeln('The scorer ' . $scorer['username'] . ' can\'t be add to the database' );
//            } else {
//                $data[] = $scorer;
//
//            }
//            $iterator->next();
//        }
        return $iterator;
    }
}