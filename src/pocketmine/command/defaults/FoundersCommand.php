<?php
/*
 *
 * 
 *
 * @author HydrolicServers Team
 * 
 *
 *
*/
declare(strict_types=1);
namespace pocketmine\command\defaults;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;
class HelpCommand extends VanillaCommand{
	public function __construct(string $name){
		parent::__construct(
			$name,
			"%pocketmine.command.founders.description",
			"%commands.founders.usage",
			["?"]
		);
		$this->setPermission("pocketmine.command.founders");
	}
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		if(count($args) === 0){
			$command = "";
			$pageNumber = 1;
		}elseif(is_numeric($args[count($args) - 1])){
			$pageNumber = (int) array_pop($args);
			if($pageNumber <= 0){
				$pageNumber = 1;
			}
			$command = implode(" ", $args);
		}else{
			$command = implode(" ", $args);
			$pageNumber = 1;
		}
		$pageHeight = $sender->getScreenLineHeight();
		if($command === ""){
			/** @var Command[][] $commands */
			$commands = [];
			foreach($sender->getServer()->getCommandMap()->getCommands() as $command){
				if($command->testPermissionSilent($sender)){
					$commands[$command->getName()] = $command;
				}
			}
			ksort($commands, SORT_NATURAL | SORT_FLAG_CASE);
			$commands = array_chunk($commands, $pageHeight);
			$pageNumber = (int) min(count($commands), $pageNumber);
			if($pageNumber < 1){
				$pageNumber = 1;
			}
			$sender->sendMessage(new TranslationContainer("commands.help.header", [$pageNumber, count($commands)]));
			if(isset($commands[$pageNumber - 1])){
				foreach($commands[$pageNumber - 1] as $command){
					$sender->sendMessage(TextFormat::DARK_GREEN . "/" . $command->getName() . ": " . TextFormat::WHITE . $command->getDescription());
				}
			}
			return true;
		}else{
			if(($cmd = $sender->getServer()->getCommandMap()->getCommand(strtolower($command))) instanceof Command){
				if($cmd->testPermissionSilent($sender)){
			$sender->sendMessage(TextFormat::RED . "MrDevCat and Dogeee " . strtolower($command));
			return true;
		}
	}
}
