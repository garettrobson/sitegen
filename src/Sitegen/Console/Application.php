<?php
namespace Sitegen\Console;

use Symfony\Component\Console\Application as App;
use Sitegen\Command\QuestionCommand;
use PHPUtils\JsonObject;
use \stdClass;

class Application extends App
{
    protected $configurations;

    public function __construct(string $name = 'Sitegen', string $version = "v0.1")
    {
        parent::__construct($name, $version);
        $this->configurations = new stdClass;
        JsonObject::loadPath($this->configurations, __CONFDIR__, true, '/\.conf\.json$/i');
        $commands = JsonObject::get($this->configurations, "commands", []);
        foreach ($commands as $command) {
            $type =  $command->type ?? $this->configurations->question->default;
            $commandType = $this->configurations->question->type->$type;
            $command = $commandType::fromJsonDescriptor($command);
            $this->add($command);
        }
    }
}
