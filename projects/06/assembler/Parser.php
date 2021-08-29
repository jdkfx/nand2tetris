<?php

define(
    "COMMAND_TYPE",
    [
        "A_COMMAND",
        "C_COMMAND",
        "L_COMMAND"
    ]
);

class Parser
{
    public $file;
    public $command;

    public function __construct($inputFileName)
    {
        $this->file = fopen($inputFileName, "r");
    }

    public function hasMoreCommands() : bool
    {
        $this->fileLine = fgets($this->file);
        if (!$this->fileLine) {
            return false;
            fclose($this->file);
        }
        return true;
    }

    public function advance()
    {
        $this->command = substr($this->fileLine, 0, strcspn($this->fileLine, "//"));
        $this->command = rtrim($this->command);
        $this->command = str_replace(" ", "", $this->command);

        if ($this->command === "") {
            return false;
        }
        return true;
    }

    public function commandType() : string
    {
        if ($this->command[0] === "@") {
            $key = 0;
        } elseif(strpos($this->command, "=") !== false || strpos($this->command, ";")) {
            $key = 1;
        } else {
            $key = 2;
        }

        return COMMAND_TYPE[$key];
    }

    public function symbol() : string
    {
        if($this->command[0] === "@") {
            return str_replace("@", "", $this->command); 
         }
         return str_replace(["(", ")"], "", $this->command);
    }

    public function dest() : string
    {
        if (strpos($this->command, "=")  === false) {
            return "null";
        }
        $command = $this->command;
        return explode("=", $command)[0];
    }

    public function comp() : string
    {
        $command = $this->command;
        if (strpos($command, "=") !== false && strpos($command, ";") !== false) {
            $tmp = explode("=", $command);
            $tmp = explode(";", $tmp[1]);
            $command = $tmp[0];   
        } else if (strpos($command, "=") !== false) {
            $tmp = explode("=", $command);
            $command = $tmp[1];
        } else if (strpos($command, ";") !== false) {
            $tmp = explode(";", $command);
            $command = $tmp[0];
        }

        return $command;
    }

    public function jump() : string
    {
        if (strpos($this->command, ";") === false) {
            return "null";
        }

        $command = $this->command;
        return explode(";", $command)[1];
    }
}