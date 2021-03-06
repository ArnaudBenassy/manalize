<?php

/*
 * This file is part of the Manalize project.
 *
 * (c) Manala <contact@manala.io>
 *
 * For the full copyright and license information, please refer to the LICENSE
 * file that was distributed with this source code.
 */

namespace Manala\Manalize\Env;

use Manala\Manalize\Env\Config\Config;

/**
 * Manala Env.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class Env
{
    /** @var string */
    private $name;

    /** @var Config[] */
    private $configs = [];

    public function __construct($name, Config ...$configs)
    {
        $this->name = $name;
        $this->configs = $configs;
    }

    /**
     * @return Config[]
     */
    public function getConfigs()
    {
        return $this->configs;
    }
}
