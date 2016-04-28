<?php
namespace SzymanTest;

use Psr\Log\AbstractLogger;

final class TestLogger extends AbstractLogger
{
    private $messages;

    public function log($level, $message, array $context = array())
    {
        $this->messages[] = new TestLogger_Message($level, $message, $context);
    }
    
    public function __toString()
    {
        $str = "";
        foreach($this->messages as $message)
        {
            $str .= $message . "\n\n";
        }
        return $str;
    }
    
}

final class TestLogger_Message
{
    private $level, $message, $context;
    
    public function __construct($level, $message, array $context)
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }
    
    public function __toString()
    {
        $str = '[' . $this->level . '] ' . $this->message;
        foreach($this->context as $key => $value)
        {
            $str .= "\n    " . $key . ": $value";
            if ($value instanceof \Exception)
            {
                $str .= "\n" . $value->getTraceAsString();
            }
        }
        return $str;
    }
}