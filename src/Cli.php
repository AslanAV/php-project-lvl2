<?php


namespace Hexlet\Code\Cli;

use Docopt;

function genHelp()
{
    $doc = <<<DOC
    gendiff -h
    Generate diff
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
DOC;
    //require('/path/to/src/docopt.php');
    $args = Docopt::handle($doc, ['version' => '0.0.1']);
    foreach ($args as $k => $v) {
        echo $k . ': ' . json_encode($v) . PHP_EOL;
    }
}
genHelp();