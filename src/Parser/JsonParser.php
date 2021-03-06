<?php

namespace App\Parser;

use App\Kernel;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class JsonParser
{
    public static function get(string $file)
    {
        $data = \file_get_contents(sprintf('%s/../%s', \dirname(__DIR__), $file));

        return \json_decode($data, true);
    }

    public static function getFolder(string $folder)
    {
        return \array_slice(\scandir(sprintf('%s/../%s', \dirname(__DIR__), $folder)), 2);
    }
}
