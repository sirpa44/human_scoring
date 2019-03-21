<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Command\Adapter;


interface ProviderInterface
{
    /**
     * return an array of Scorers data
     *
     * @param $filePath
     * @param $symfonyStyle
     * @return iterator
     */
    public function getIterator($filePath, $symfonyStyle);
}