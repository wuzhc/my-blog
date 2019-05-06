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
        $title = substr($file, strrpos($file, '/') + 1, -3);
        $parser = new HyperDown\Parser;
        $html = $parser->makeHtml(file_get_contents($bashPath . '/' . $file));
        $atime = fileatime($bashPath . '/' . $file);
        $mtime = filemtime($bashPath . '/' . $file);
    } else {
        $html = '';
        $title = '';
    }
}
?>

<html>
<head>
    <title><?php echo !empty($title) ? $title : $category ?></title>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/styles/an-old-hope.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=yes">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.6/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <style>
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px dotted #333333;
        }

        .header span {
            margin-right: 10px;
        }

        .nav, .content {
            display: inline-block;
            vertical-align: top;
        }

        .nav {
            width: 28%;
        }

        .content {
            width: 70%;
        }

        .copyright {
            color: #333333;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .copyright ul{
            padding: 0;
        }
        .copyright ul li{
            list-style-type: none;
        }

        @media screen and (max-width: 400px) {
            .content{
                width: 100%;
                display: block;
            }
            .nav {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div id="container">
    <div class="header">
        <?php foreach ($categories as $cg) { ?>
            <span><a href="index.php?c=<?php echo $cg ?>"><?php echo $cg ?></a></span>
        <?php } ?>
        <span><a href="index.php?c=me">关于我</a></span>
    </div>
    <div class="nav">
        <h3><?php echo $category ?></h3>
        <ul>
            <?php foreach ($files

            as $dir => $file) { ?>
            <?php if (is_array($file)) { ?>
                <li><?php echo $dir; ?></li>
                <ul>
                    <?php foreach ($file

                    as $cf) { ?>
                    <li><a href="index.php?c=<?php echo $category ?>&f=<?php echo $category . '/' . $dir . '/'
                            . $cf ?>"><?php echo $cf ?></a>
                        <?php } ?>
                </ul>
            <?php } else { ?>
            <li><a href="index.php?f=<?php echo $category . '/'
                    . $file ?>&c=<?php echo $category ?>"><?php echo $file ?></a>
                <?php } ?>
                <?php } ?>
        </ul>
    </div>
    <div class="content">
        <?php if (isset($title) && $html) { ?>
            <div>
                <h3><?php echo $title; ?></h3>
                <div class="copyright">
                    <ul>
                        <li>作者: <a href="https://github.com/wuzhc">wuzhc</a></li>
                        <li>最后修改时间: <?php echo isset($mtime) ? date('Y-m-d H:i:s', $mtime) : '无' ?></li>
                        <li>最后访问时间: <?php echo isset($atime) ? date('Y-m-d H:i:s', $atime) : '无' ?></li>
                        <li>转载请注明作者和出处</li>
                    </ul>
                </div>
                <?php echo $html ?: '暂无内容'; ?>
            </div>
        <?php } else { ?>
            <div>暂无内容</div>
        <?php } ?>
    </div>
</div>
</body>
</html>
