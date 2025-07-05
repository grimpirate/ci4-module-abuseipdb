<?php

namespace Modules\AbuseIpdb\Publisher;

use CodeIgniter\Publisher\ContentReplacer;

class Publisher
{
    public function __construct(private readonly ContentReplacer $replacer = new ContentReplacer())
	{
	}

    public function replace(string $file, array $replaces): bool
    {
        $content = file_get_contents($file);

        $newContent = $this->replacer->replace($content, $replaces);

        $return = file_put_contents($file, $newContent);

        return $return !== false;
    }

    public function addLineAfter(string $file, string $line, string $after): bool
    {
        $content = file_get_contents($file);

        $result = $this->replacer->addAfter($content, $line, $after);

        if ($result !== null) {
            $return = file_put_contents($file, $result);

            return $return !== false;
        }

        return false;
    }

    public function addLineBefore(string $file, string $line, string $before): bool
    {
        $content = file_get_contents($file);

        $result = $this->replacer->addBefore($content, $line, $before);

        if ($result !== null) {
            $return = file_put_contents($file, $result);

            return $return !== false;
        }

        return false;
    }
}