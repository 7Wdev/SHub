<?php
declare(strict_types=1);
/*

  _____ _             _    _____  ______
 / ____| |           | |  |  __ \|  ____|
| (___ | |_ ___   ___| | _| |__) | |__
 \___ \| __/ _ \ / __| |/ /  ___/|  __|
 ____) | || (_) | (__|   <| |    | |____
|_____/ \__\___/ \___|_|\_\_|    |______|


                                         */
namespace StockPE\MCBE;

//pmmp libs!
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent as دخول;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerEvent;
use pocketmine\plugin\Plugin;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\command\{Command, CommandSender, defaults\VanillaCommand};
use pocketmine\event\player\PlayerQuitEvent as خروج;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\scheduler\Task;

class SHub extends PluginBase implements Listener {

	   const PERFIX_SHUB = "§6§lStockPE§r ";
	   
	   public function onEnable()
	     {
	       $this->getServer()->getPluginManager()->registerEvents($this, $this);
	       $this->getServer()->getLogger()->info("You can change your lobby system now!");
	     }

	   public function onPlayerLogin(دخول $event)
	     {
	       $event->getPlayer()->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
	     }

		 public function onPlayerLogout(خروج $event)
		   {
				  $event->getPlayer()->getInventory()->clearAll();
				  $event->getPlayer()->removeAllEffects();
				  $event->getPlayer()->getArmorInventory()->clearAll();
				}

	   public function onCommand(CommandSender $sender, Command $command, string $label, array $args)  : bool
       {
         switch ($command->getName())
           {
						 case "hub":
						 case "lobby":
             if($sender instanceof Player)
              {
                $sender->sendMessage(self::PERFIX_SHUB . "§eteleporting to hub/lobby ...");
                $this->getScheduler()->scheduleDelayedTask(new TaskWorker($sender), 60); //20 tick = 1 sec
              }
             break;
           }
         return true;
       }
}

class TaskWorker extends Task {

    public function __construct($sender)
      {
        $this->sender = $sender;
      }
	   
    public function onRun(int $currentTick) : void{
        $sender = $this->sender;
        if($sender === null || !$sender->isOnline()) return;
        $sender->teleport($sender->getServer()->getDefaultLevel()->getSafeSpawn());
        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();
        $sender->removeAllEffects();
        $sender->sendMessage("§6§lStockPE§r §aTeleported to hub/lobby!");
    }
}
?>