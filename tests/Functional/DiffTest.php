<?php

/*
 * This file is part of the Manalize project.
 *
 * (c) Manala <contact@manala.io>
 *
 * For the full copyright and license information, please refer to the LICENSE
 * file that was distributed with this source code.
 */

namespace Manala\Manalize\Tests\Functional;

use Manala\Manalize\Command\Diff;
use Manala\Manalize\Command\Setup;
use Manala\Manalize\Env\EnvEnum;
use Manala\Manalize\Handler\Diff as DiffHandler;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class DiffTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_PATCH_FILE = MANALIZE_DIR.'/tests/fixtures/Command/DiffTest/expected.patch';

    private static $cwd;

    public static function setUpBeforeClass()
    {
        $cwd = manala_get_tmp_dir('tests_diff_');
        $fs = new Filesystem();

        (new Process('composer create-project symfony/framework-standard-edition:3.1.* . --no-install --no-progress --no-interaction', $cwd))
            ->setTimeout(null)
            ->run();

        (new CommandTester(new Setup()))
            ->setInputs(['dummy.manala', "\n", "\n", "\n", "\n", "\n"])
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

        UPDATE_FIXTURES ? file_put_contents(static::EXPECTED_PATCH_FILE, $tester->getDisplay(true)) : null;

        $this->assertStringEqualsFile(static::EXPECTED_PATCH_FILE, $tester->getDisplay(true));
    }

    public static function tearDownAfterClass()
    {
        (new Filesystem())->remove(MANALIZE_TMP_ROOT_DIR);
    }
}
