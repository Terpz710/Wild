<?php

declare(strict_types=1);

namespace Terpz710\Wild;

use pocketmine\plugin\PluginBase;

use Terpz710\Wild\Command\WildCommand;

class Loader extends PluginBase
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("wild", new WildCommand($this));
    }
}