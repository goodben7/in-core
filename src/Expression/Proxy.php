<?php

namespace goodben\banking\Core\Expression;

use goodben\banking\Core\Operation;

/**
 * Class Proxy.
 * 
 * @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
 */
class Proxy  
{

    public function __construct(private Operation $subject) {
        $this->subject = $subject;
    }

    public  function __get($name) {
        $getter = "get".ucfirst($name);
        if(method_exists($this->subject, $getter)) {
            return $this->subject->$getter();
        }
        else if (property_exists($this->subject, $name)) {
            return $this->subject->$name;
        }
        else
            throw new \BadMethodCallException("Undefined method '$getter'");
    }

    public function __set($name, $value) {
        $setter = "set".ucfirst($name);
        if(method_exists($this->subject, $setter)) {
            $this->subject->$setter($value);
        }
        else if (property_exists($this->subject, $name)) {
            return $this->subject->$name = $value;
        }
        else
            throw new \BadMethodCallException("Undefined method '$setter'");
    }
}
