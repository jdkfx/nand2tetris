<?php

require_once("Parser.php");
require_once("Code.php");
require_once("SymbolTable.php");

$parser = new Parser("../add/Add.asm");
// $parser = new Parser("../max/Max.asm");
// $parser = new Parser("../max/MaxL.asm");
// $parser = new Parser("../pong/Pong.asm");
// $parser = new Parser("../pong/PongL.asm");
// $parser = new Parser("../rect/Rect.asm");
// $parser = new Parser("../rect/RectL.asm");

$symbolTable = new SymbolTable();

$rom = 0;
$array = [];

while ($parser->hasMoreCommands()) {
    $binary = "";
    if (!$parser->advance()) {
        continue;
    }

    if($parser->commandType() === COMMAND_TYPE[1]) {
        $dest = $parser->dest();
        $comp = $parser->comp();
        $jump = $parser->jump();
        $binary = Code::getBinary($dest, $comp, $jump);

        $array[] = $binary;
        $rom++;
    } elseif($parser->commandType() === COMMAND_TYPE[0]) {
        if (is_numeric($parser->symbol())) {
            $tmp= decbin($parser->symbol());
            $binary = sprintf("%016d", $tmp);
            $array[] = $binary;
        } else {
            $array[] = $parser->symbol();
        }
        $rom++;
    } else {
        $symbolTable->addEntry($parser->symbol(), $rom);
    }
}

$fpWrite = fopen("../add/Add.hack", "a");
// $fpWrite = fopen("../max/Max.hack", "a");
// $fpWrite = fopen("../max/MaxL.hack", "a");
// $fpWrite = fopen("../pong/Pong.hack", "a");
// $fpWrite = fopen("../pong/PongL.hack", "a");
// $fpWrite = fopen("../rect/Rect.hack", "a");
// $fpWrite = fopen("../rect/RectL.hack", "a");

foreach($array as $value) {
    $binary = $value;
    if (!is_numeric($binary)) {
        if ($symbolTable->contains($binary)) {
            $tmp = decbin($symbolTable->symbolTable[$binary]);
            $binary = sprintf("%016d", $tmp);
        } else {
            $symbolValue = $binary;
            $tmp = decbin($symbolTable->ram);
            $binary = sprintf("%016d", $tmp);
            
            $symbolTable->count($symbolValue);
        }
    }
    fwrite($fpWrite, $binary . "\n");
}
fclose($fpWrite);