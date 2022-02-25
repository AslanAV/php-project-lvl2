<?php

/**
 * Parses and verifies the doc comments for files.
 *
 * PHP version 5
 *
 * @category  Parser
 * @package   Cli_Parser
 * @author    Aslan Autlev <autlevaslan@gmail.com>
 * @copyright 2021-2022 Home
 * @license   github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace Hexlet\Code\Cli;

use Docopt;

use function Hexlet\Code\Differ\gendiff;

/**
 * Function genHelp()  echo help for gendiff
 *
 * @return string
 */
function genHelp(): string
{
    $doc = <<<DOC
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: stylish]
    DOC;

    $args = Docopt::handle($doc, ['version' => 'gendiff v: 0.0.1']);

    $firstFile = $args['<firstFile>'];
    $secondFile = $args['<secondFile>'];
    $format = $args['--format'];

    return genDiff($firstFile, $secondFile, $format);
}
