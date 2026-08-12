<?php

namespace Hexlet\Code;

function parse(string $content): array
{
    return json_decode($content, true);
}