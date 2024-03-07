<?php

declare(strict_types=1);

namespace Terpz710\Wild\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\world\Position;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

use Terpz710\Wild\Loader;

class WildCommand extends Command
{
    private $plugin;

    public function __construct(Loader $plugin)
    {
        parent::__construct("wild", "Teleport to a random safe location in the wilderness", null, ["w"]);
        $this->plugin = $plugin;
        $this->setPermission("wild.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $min = (int) $this->plugin->getConfig()->get("MinCoord");
            $max = (int) $this->plugin->getConfig()->get("MaxCoord");
            $worldName = $this->plugin->getConfig()->get("WildWorld");
            $worldManager = $this->plugin->getServer()->getWorldManager();

            if (!$worldManager->isWorldLoaded($worldName)) {
                $worldManager->loadWorld($worldName);
            }
            $world = $worldManager->getWorldByName($worldName);

            if ($world !== null) {
                $randomX = mt_rand($min, $max);
                $randomZ = mt_rand($min, $max);
                if (!$world->isChunkLoaded($randomX >> 4, $randomZ >> 4)) {
                    $world->loadChunk($randomX >> 4, $randomZ >> 4);
                }

                if ($world->isChunkLoaded($randomX >> 4, $randomZ >> 4)) {
                    $spawnPosition = new Position($randomX, $world->getHighestBlockAt($randomX, $randomZ) + 1, $randomZ, $world);
                    $sender->teleport($spawnPosition);
                    $sender->sendMessage("[" . TF::AQUA . "Wilderness" . TF::RESET . "] " . TF::YELLOW . " You have been teleported to a random location!");
                } else {
                    $sender->sendMessage(TF::RED . "Error: Unable to load the chunk at coordinates: §c{$randomX}, {$randomZ}");
                }
            } else {
                $sender->sendMessage(TF::RED . "Error: Unable to load the specified world: §c{$worldName}");
            }
        }
    }
}
