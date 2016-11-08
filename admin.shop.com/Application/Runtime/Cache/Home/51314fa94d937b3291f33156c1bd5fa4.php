<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="./jquery-1.11.2.js"></script>
</head>
<body>
<h1><?php echo ($title); ?></h1>
<form action="<?php echo U('save');?>" method="post">
    <p>姓名:<input type="text" name="name" ></p>
    <p>年龄:<input type="text" name="age" ></p>
    <input type="submit" value="提交">
</form>
</body>
</html>