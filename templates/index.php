
<?php

// Assuming 'data.json' is the name of your JSON file located in the same directory as this script.
$json_file = 'article.json';
$output_file = 'output.php';

// Read the JSON file
$json_data = file_get_contents($json_file);
if ($json_data === false) {
    die('Error reading JSON file.');
}

// Decode the JSON data to an associative array
$article_arr = json_decode($json_data, true);
if ($article_arr === null) {
    die('Error decoding JSON data.');
}

// Start the output buffer to capture generated code
ob_start();

echo "<?php\n\n";

foreach ($article_arr as $key => $value) {
    // Generate the PHP code for each key
    echo "/**\n";
    echo " * Article schema {$key} key\n";
    echo " */\n";
    echo "\$$key = null;\n";
    echo "if (isset(\$article_arr['$key']) && !empty(\$$key)) {\n";
    echo "    \$article_arr['$key'] = \$$key;\n";
    echo "}\n\n";
}

// Get the generated code from the output buffer
$generated_code = ob_get_clean();

// Write the generated code to the output PHP file
file_put_contents($output_file, $generated_code);

echo 'Generated code has been written to ' . $output_file;
?>