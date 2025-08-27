<?php
echo "<pre>";
echo "vendor/phpmailer/phpmailer exists? ";
var_dump(is_dir(__DIR__ . '/../vendor/phpmailer/phpmailer'));
echo "\nList vendor/: \n";
@print_r(scandir(__DIR__ . '/../vendor'));
echo "\nList vendor/phpmailer/: \n";
@print_r(@scandir(__DIR__ . '/../vendor/phpmailer'));
echo "\nList vendor/composer/: \n";
@print_r(@scandir(__DIR__ . '/../vendor/composer'));
