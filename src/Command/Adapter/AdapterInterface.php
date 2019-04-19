<?php declare(strict_types=1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command\Adapter;


interface AdapterInterface
{
    /**
     * return an array of Scorers data
     *
     * @param $filePath
     * @return \Iterator
     */
    public function getIterator($filePath);
}