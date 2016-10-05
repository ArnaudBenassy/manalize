<?php

/*
 * This file is part of the Manala package.
 *
 * (c) Manala <contact@manala.io>
 *
 * For the full copyright and license information, please refer to the LICENSE
 * file that was distributed with this source code.
 */

namespace Manala\Tests\Functional;

use Manala\Command\Diff;
use Manala\Command\Setup;
use Manala\Env\EnvEnum;
use Manala\Handler\Diff as DiffHandler;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class DiffTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_PATCH_FILE = MANALA_DIR.'/tests/fixtures/Command/DiffTest/expected.patch';

    private static $cwd;

    public static function setUpBeforeClass()
    {
        $cwd = sys_get_temp_dir().'/Manala/tests/diff';
        $fs = new Filesystem();

        if ($fs->exists($cwd)) {
            $fs->remove($cwd);
        }

        $fs->mkdir($cwd);

        (new Process('composer create-project symfony/framework-standard-edition:3.1.* . --no-install --no-progress --no-interaction', $cwd))
            ->setTimeout(null)
            ->run();

        (new CommandTester(new Setup()))
            ->setInputs(['manala', 'dummy'])
            ->execute(['cwd' => $cwd]);

        // Tweak project files:
        $fs->remove($cwd.'/ansible/deploy.yml');
        file_put_contents($cwd.'/Makefile', " \n This line is expected in the patch", FILE_APPEND);

        self::$cwd = $cwd;
    }

    public function testExecute()
    {
        $tester = new CommandTester(new Diff());
        $tester->execute(['cwd' => static::$cwd, '--env' => EnvEnum::SYMFONY]);

        if (DiffHandler::EXIT_SUCCESS_DIFF !== $tester->getStatusCode()) {
            echo $tester->getDisplay();
        }

        $this->assertSame(DiffHandler::EXIT_SUCCESS_DIFF, $tester->getStatusCode());
        // Uncomment the following line in order to update the expected diff:
        // /!\ Don't commit blindly the diff !
        //file_put_contents(static::EXPECTED_PATCH_FILE, $tester->getDisplay(true));
        $this->assertStringEqualsFile(static::EXPECTED_PATCH_FILE, $tester->getDisplay(true));
    }

    public static function tearDownAfterClass()
    {
        (new Filesystem())->remove(self::$cwd);
    }
}
