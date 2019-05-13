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

    public function validate($validations, $value, $field)
    {
        foreach ($validations as $validation) {
            $validation = (array)$validation;
            $rule = array_shift($validation);
            array_unshift($validation, $value);
            $validator = $this->configurations->validators->$rule ?? false;
            if (!$validator) {
                return "WARNING: Validator {$rule} is not configured.";
            }
            $return = call_user_func_array($validator->callback, $validation);
            if (!$return) {
                $replace = static::getReplacements($field);
                return strtr($validator->message, $replace);
            }
        }
        return false;
    }

    protected static function getReplacements($from)
    {
        $array = (array)$from;
        $return = [];
        foreach ($array as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $return[":$key"] = $value;
            }
        }
        return $return;
    }

    protected static function validateHostname(string $string)
    {
        return filter_var($string, FILTER_VALIDATE_DOMAIN) ? true : false;
    }

    protected static function validateNotEmpty(string $string)
    {
        return !empty($string);
    }

    protected static function validateRegex(string $string, string $pattern)
    {
        return preg_match($pattern, $string);
    }

    protected static function validateIn(string $string, $allowed)
    {
        return in_array($string, $allowed);
    }
}
