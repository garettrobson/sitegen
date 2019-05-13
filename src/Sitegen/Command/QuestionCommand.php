<?php
namespace Sitegen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use RuntimeException;

class QuestionCommand extends Command
{
    public static function fromJsonDescriptor(object $descriptor)
    {
        $command = new static($descriptor->name);
        $command->setDescriptor($descriptor);
        return $command;
    }

    protected $descriptor = null;
    protected $fields = null;

    protected function setDescriptor(object $descriptor)
    {
        $this->descriptor = $descriptor;
    }


    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        if (!$this->descriptor) {
            throw new RuntimeException(
                sprintf(
                    "%s : Descriptor not set.",
                    __METHOD__
                )
            );
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $questionHelper = $this->getHelper('question');
        foreach ($this->descriptor->fields as $name => $field) {
            for ($repeat = true; $repeat;) {
                $question = new Question($field->prompt . ": ", $field->default ?? null);
                $answer = '' . $questionHelper->ask($input, $output, $question);
                $reason = $this->getApplication()->validate($field->validate ?? [], $answer, $field);
                if ($reason) {
                    $output->writeln($reason);
                } else {
                    $this->fields[$name] = $answer;
                    $repeat = false;
                }
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $data = [];
        foreach ($this->descriptor->fields as $name => $field) {
            $data[] = [
                $field->label, '"'.$this->fields[$name].'"'
            ];
        }
        $table
            ->setHeaders(['Field', 'Value'])
            ->setRows($data)
            ->render();
    }
}
