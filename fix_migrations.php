<?php
foreach (glob(__DIR__ . '/database/migrations/*.php') as $file) {
    $content = file_get_contents($file);
    $newContent = str_replace("->integer('id');", "->id();", $content);
    file_put_contents($file, $newContent);
    echo "Processed: " . basename($file) . "\n";
}
echo "Done replacing ->integer('id') with ->id().\n";
