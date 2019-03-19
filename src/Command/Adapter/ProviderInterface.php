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
     * @param $filePath
     * @return mixed
     */
    public function getIterator($filePath, $symfonyStyle);
}