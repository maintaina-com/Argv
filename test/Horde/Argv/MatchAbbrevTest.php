<?php

namespace Horde\Argv;
use Horde_Argv_TestCase as TestCase;
use \Horde_Argv_Parser;

require_once __DIR__ . '/TestCase.php';

/**
 * @author     Chuck Hagenbuch <chuck@horde.org>
 * @author     Mike Naberezny <mike@maintainable.com>
 * @license    http://www.horde.org/licenses/bsd BSD
 * @category   Horde
 * @package    Argv
 * @subpackage UnitTests
 */

class MatchAbbrevTest extends TestCase
{
    public function testMatchAbbrev()
    {
        $this->assertEquals(Horde_Argv_Parser::matchAbbrev("--f",
            array("--foz" => null,
                  "--foo" => null,
                  "--fie" => null,
                  "--f"   => null)),
            '--f');
    }

    public function testMatchAbbrevError()
    {
        $s = '--f';
        $wordmap = array("--foz" => null, "--foo" => null, "--fie" => null);

        $this->expectException('Horde_Argv_AmbiguousOptionException');

        Horde_Argv_Parser::matchAbbrev($s, $wordmap);
    }
}
