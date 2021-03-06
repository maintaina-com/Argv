<?php

namespace Horde\Argv;
use \Horde_Argv_Option;
use \Horde_Argv_IndentedHelpFormatter;
use \Horde_Cli_Color;

/**
 * @author     Chuck Hagenbuch <chuck@horde.org>
 * @author     Mike Naberezny <mike@maintainable.com>
 * @license    http://www.horde.org/licenses/bsd BSD
 * @category   Horde
 * @package    Argv
 * @subpackage UnitTests
 */

class ConflictOverrideTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->parser = new InterceptingParser(array(
            'usage' => Horde_Argv_Option::SUPPRESS_USAGE,
            'formatter' => new Horde_Argv_IndentedHelpFormatter(
                2, 24, null, true,
                new Horde_Cli_Color(Horde_Cli_Color::FORMAT_NONE)
            )
        ));
        $this->parser->setConflictHandler('resolve');
        $this->parser->addOption(
            '-n', '--dry-run',
            array(
                'action' => 'store_true',
                'dest' => 'dry_run',
                'help' => "don't do anything"
            )
        );
        $this->parser->addOption(
            '--dry-run', '-n',
            array(
                'action' => 'store_const',
                'const' => 42,
                'dest' => 'dry_run',
                'help' => 'dry run mode'
            )
        );
    }

    public function testConflictOverrideOpts()
    {
        $opt = $this->parser->getOption('--dry-run');

        $this->assertEquals(array('-n'), $opt->shortOpts);
        $this->assertEquals(array('--dry-run'), $opt->longOpts);
    }

    public function testConflictOverrideHelp()
    {
        $output = "Options:\n"
                . "  -h, --help     show this help message and exit\n"
                . "  -n, --dry-run  dry run mode\n";
        $this->assertOutput(array('-h'), $output);
    }

    public function testConflictOverrideArgs()
    {
        $this->assertParseOk(array('-n'),
                             array('dry_run' => 42),
                             array());
    }
}
