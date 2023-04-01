<?php


class BracketsParser{

    private string $pattern;
    private string $extracted = '';
    private string $cleaned = '';
    private string $string;

    public function __construct(string $string, string $pattern = '/\{([^}]*)\}/') {
        $this->string = $string;
        $this->pattern = $pattern;
    }

    public function extractTextInBrackets(): object {
        preg_match($this->pattern, $this->string, $matches); // ищем текст в скобках в строке
        $this->extracted = $matches[1] ?? ''; // сохраняем текст в скобках, если он был найден
        $this->cleaned = trim(preg_replace($this->pattern, '', $this->string));
        return $this;
    }

    public function getExtracted() : string {
        return $this->extracted;
    }

    public function getCleaned() : string {
        return $this->cleaned;
    }
}