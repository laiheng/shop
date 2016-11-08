<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="./jquery-1.11.2.js"></script>
</head>
<body>
<table border="1px">
    <tr>
        <th>序号</th><th>姓名</th><th>年龄</th><th>修改</th><th>删除</th>
    </tr>
    <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr>
        <th><?php echo ($row["id"]); ?></th>
        <th><?php echo ($row["name"]); ?></th>
        <th><?php echo ($row["age"]); ?></th>
        <th><a href="<?php echo U('edit',array('id'=>$row['id']));?>">修改</a></th>
        <th><a href="<?php echo U('del',array('id'=>$row['id']));?>">删除</a></th>
    </tr><?php endforeach; endif; ?>
</table>
<a href="<?php echo U('add');?>">添加用户</a>
</body>
</html>