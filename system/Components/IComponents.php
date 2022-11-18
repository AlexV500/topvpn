<?php

interface IComponents
{

    public function init( array $atts = []);

    public function render();

    public function show();
}