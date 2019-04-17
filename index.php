<?php
/**
 * Created by PhpStorm.
 * User: wuzhc
 * Date: 19-4-17
 * Time: 下午2:33
 */

include 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$category = $_GET['c'] ?: 'php';
$bashPath = getenv('DIR');
$categories = getDirs($bashPath);

if ($category == 'me') {
    $files = [];
    $parser = new HyperDown\Parser;
    $html = $parser->makeHtml(file_get_contents('me.md'));
} else {
    $files = getFiles($bashPath . '/' . $category);
    if ($file = $_GET['f']) {
        $parser = new HyperDown\Parser;
        $html = $parser->makeHtml(file_get_contents($bashPath . '/' .$file));
    } else {
        $html = '';
    }
}
?>

<html>
<head>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/styles/an-old-hope.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/highlight.min.js"></script>
    <script >hljs.initHighlightingOnLoad();</script>
    <style>
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px dotted #333333;
        }
        .header span{
            margin-right: 10px;
        }
        .nav,.content {
            display: inline-block;
            vertical-align: top;
        }
        .nav {
            width: 28%;
        }
        .content{
            width: 70%;
        }
    </style>
</head>
<body>
<div id="container">
    <div class="header">
        <?php foreach ($categories as $cg) { ?>
            <span><a href="index.php?c=<?php echo $cg?>"><?php echo $cg?></a></span>
        <?php } ?>
        <span><a href="index.php?c=me">关于我</a></span>
    </div>
    <div class="nav">
        <h3><?php echo $category?></h3>
        <ul>
            <?php foreach ($files as $dir => $file) { ?>
            <?php if (is_array($file)) { ?>
                <li><?php echo $dir;?></li>
                <ul>
                    <?php foreach ($file as $cf) { ?>
                    <li><a href="index.php?c=<?php echo $category?>&f=<?php echo $category . '/' . $dir . '/' . $cf?>"><?php echo $cf?></a>
                        <?php } ?>
                </ul>
            <?php } else { ?>
            <li><a href="index.php?f=<?php echo $category . '/' . $file?>&c=<?php echo $category?>"><?php echo $file?></a>
                <?php }?>
                <?php } ?>
        </ul>
    </div>
    <div class="content">
        <div>
            <?php echo $html;?>
        </div>
    </div>
</div>
</body>
</html>
