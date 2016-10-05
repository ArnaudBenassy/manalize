<?php

/*
 * This file is part of the Manala package.
 *
 * (c) Manala <contact@manala.io>
 *
 * For the full copyright and license information, please refer to the LICENSE
 * file that was distributed with this source code.
 */

namespace Manala\Tests\Env\Config\Variable;

use Manala\Env\Config\Variable\AppName;

class AppNameTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReplaces()
    {
        $var = new AppName('dummy-app.manala');

        $this->assertSame(['{{ app }}' => 'dummy-app.manala'], $var->getReplaces());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage This value must contain only alphanumeric characters, dots and hyphens
     */
    public function testValidateFailsForNonAlphanumericAndNonHyphenChars()
    {
        AppName::validate('dummy_app_with_underscores');
    }

    public function testValidate()
    {
        $this->assertSame('dummy-app', AppName::validate('dummy-app'));
    }
}