<?php
/**
 * @var string $config
 */
?>
<!doctype html>
<html
    lang="en">
<head>
    <meta
        charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>Magento API test</title>
</head>
<body>
    <div id="app"></div>
    <script>
        window.config = JSON.parse('<?= $config ?>');
    </script>
    <script src="/js/app.js"></script>
</body>
</html>
