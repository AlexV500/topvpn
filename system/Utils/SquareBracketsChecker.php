<?php


class SquareBracketsChecker{

    private string $string;
    private bool $matched;
    private bool $specialMatched;

    public function __construct(string $string) {
        $this->string = $string;
        $this->matched = false;
        $this->specialMatched = false;
    }

    public function removeSquareBrackets(): void {
        if (strpos($this->string, '[') !== false && strpos($this->string, ']') !== false){
            $this->matched = true;
            $this->string = str_replace(['[', ']'], '', $this->string);
            $this->string = trim($this->string);

    //        echo $this->string[0].' => '.strpos($this->string, '!').'<br/>';
            if (strpos($this->string, '!') === 0) {
                $this->string = substr($this->string, 1);
                $this->specialMatched = true;
            }
        }
    }

    public function getString(): string {
        return $this->string;
    }

    public function getMatched(): bool {
        return $this->matched;
    }

    public function getSpecialMatched(): bool {
        return $this->specialMatched;
    }

}