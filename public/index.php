<?php
use Bundler\Markup\JavascriptMarkup;
use Bundler\Markup\StylesheetMarkup;

// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
/** @noinspection PhpIncludeInspection */
require_once 'vendor/autoload.php';

$stylesheetMarkup = new StylesheetMarkup();
$stylesheetMarkup->setDevelopment(true);
$stylesheetMarkup->setMinified(true);
$stylesheetMarkup->setVersionized(true);

$javascriptMarkup = new JavascriptMarkup();
$javascriptMarkup->setDevelopment(true);
$javascriptMarkup->setMinified(true);
$javascriptMarkup->setVersionized(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>elnebuloso/bundler</title>

    <!-- Bootstrap -->
    <?php echo $stylesheetMarkup->getMarkup('stylesheetFoo'); ?>

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

<?php echo $javascriptMarkup->getMarkup('javascriptFoo'); ?>
</body>
</html>