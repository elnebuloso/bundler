<?php
// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
require_once 'vendor/autoload.php';

use Bundler\JavascriptMarkup;
use Bundler\StylesheetMarkup;

$stylesheetMarkup = new StylesheetMarkup();
$stylesheetMarkup->setYaml('.bundler/stylesheet.yaml');
$stylesheetMarkup->setHost('/');
$stylesheetMarkup->setPublic('public/css');
$stylesheetMarkup->setMinified(true);
$stylesheetMarkup->setDevelopment(false);

$javascriptMarkup = new JavascriptMarkup();
$javascriptMarkup->setYaml('.bundler/javascript.yaml');
$javascriptMarkup->setHost('/');
$javascriptMarkup->setPublic('public/js');
$javascriptMarkup->setMinified(true);
$javascriptMarkup->setDevelopment(false);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>elnebuloso/bundler</title>

    <!-- Bootstrap -->
    <?php echo $stylesheetMarkup->get('package-yuicompressor'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h1>bundler
        <small>elnebuloso</small>
    </h1>
</div>

<?php echo $javascriptMarkup->get('package-google-closure-compiler'); ?>
</body>
</html>