<?php
$content = file_get_contents('app/Helpers/KuraHelpers.php');
$search = "return asset(\$path ? 'assets/images/products/' . \$path : 'assets/images/placeholder.png');";
$replace = "if (!\$path) return asset('assets/images/placeholder.png');\n        if (str_starts_with(\$path, 'http://') || str_starts_with(\$path, 'https://')) return \$path;\n        return asset('assets/uploads/products/' . ltrim(\$path, '/'));";
$content = str_replace($search, $replace, $content);
file_put_contents('app/Helpers/KuraHelpers.php', $content);
echo "Done";
