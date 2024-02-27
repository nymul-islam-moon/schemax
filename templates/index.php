<?php


/**
 * this is the update method part
 */

// Assuming the JSON file name is provided without the extension.
$schemaName = 'audio'; // This could be dynamic based on your requirements
$json_file = $schemaName . '.json';
$output_file = $schemaName . '_output.php';

// Read the JSON file
$json_data = file_get_contents($json_file);
if ($json_data === false) {
    die('Error reading JSON file.');
}

// Decode the JSON data into an associative array
$article_arr = json_decode($json_data, true);
if ($article_arr === null) {
    die('Error decoding JSON data.');
}

// Start the output buffer to capture the generated code
ob_start();

echo "<?php\n\n";

foreach ($article_arr as $key => $value) {
    // Generate the PHP code for each key using the specified pattern
    echo "/**\n";
    echo " * " . ucfirst($schemaName) . " schema $key key\n";
    echo " */\n";
    echo "if (isset(\$article_arr['$key'])) {\n";
    echo "    \$$key = \$this->$key();\n";
    echo "    if (!empty(\$$key)) {\n";
    echo "        \$article_arr['$key'] = \$$key;\n";
    echo "    } else {\n";
    echo "        unset(\$article_arr['$key']);\n";
    echo "    }\n";
    echo "}\n\n";
}

// Get the generated code from the output buffer
$generated_code = ob_get_clean();

// Write the generated code to the output PHP file
file_put_contents($output_file, $generated_code);

echo "Generated code has been written to $output_file.";

/**
 * this the all method conversion part
 */

//
//// Assuming the JSON file name is provided without the extension.
//$schemaName = 'video'; // This can be dynamic based on your requirements.
//$json_file = $schemaName . '.json';
//$output_file = $schemaName . '_methods.php';
//
//// Read the JSON file.
//$json_data = file_get_contents($json_file);
//if ($json_data === false) {
//    die('Error reading JSON file.');
//}
//
//// Decode the JSON data into an associative array.
//$article_arr = json_decode($json_data, true);
//if ($article_arr === null) {
//    die('Error decoding JSON data.');
//}
//
//// Start the output buffer to capture the generated code.
//ob_start();
//
//echo "<?php\n\n";
//
//foreach ($article_arr as $key => $value) {
//    // Generate the PHP method for each key using the specified pattern
//    echo "/**\n";
//    echo " * Get " . ucfirst($key) . "\n";
//    echo " *\n";
//    echo " * @return mixed|void|null\n";
//    echo " */\n";
//    echo "protected function " . $key . "() {\n";
//    echo "    \$$key = get_the_title(\$this->post_id);\n\n";
//    echo "    if (!empty(\$$key)) {\n";
//    echo "        return apply_filters(\"schemax_{this->schema_type}_" . $key . "\", \$$key);\n";
//    echo "    }\n";
//    echo "}\n\n";
//}
//
//// Get the generated code from the output buffer.
//$generated_code = ob_get_clean();
//
//// Write the generated code to the output PHP file.
//file_put_contents($output_file, $generated_code);
//
//echo "Generated code has been written to $output_file.";