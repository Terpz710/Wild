<?php

declare(strict_types=1);

namespace Terpz710\Wild;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\Wild\Command\WildCommand;

class Loader extends PluginBase
{
    /** @var Config */
    private $config;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML); // Initialize $config
        $this->getServer()->getCommandMap()->register("wild", new WildCommand($this));
    }

    public function getConfig(): Config {
        return $this->config;
    }
}
