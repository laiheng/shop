<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="./jquery-1.11.2.js"></script>
</head>
<body>
<h1><?php echo ($title); ?></h1>
<form action="<?php echo U('update',array('id'=>$row['id']));?>" method="post">
    <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>">
    <p>姓名:<input type="text" name="name" value="<?php echo ($row["name"]); ?>"></p>
    <p>年龄:<input type="text" name="age" value="<?php echo ($row["age"]); ?>"></p>
    <input type="submit" value="修改">
</form>
</body>
</html>