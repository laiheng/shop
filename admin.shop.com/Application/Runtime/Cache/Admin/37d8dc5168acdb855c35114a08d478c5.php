<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 商品权限 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="__TREEGRID__/css/jquery.treegrid.css" />
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('add');?>">添加权限</a></span>
            <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 商品权限 </span>
        </h1>
        <div style="clear:both"></div>
        <form method="post" action="" name="listForm">
            <div class="list-div" id="listDiv">
                <table cellpadding="3" cellspacing="1" class="tree">
                    <tr>
                        <th>权限名称</th>
                        <th>权限路径</th>
                        <th>权限描述</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr align="center" class="0">
                            <td align="left" class="first-cell" ><?php echo str_repeat('&nbsp;',($row['level']-1)*5); echo ($row["name"]); ?></td>
                            <td align="center"><?php echo ($row["path"]); ?></td>
                            <td align="center"><?php echo ($row["intro"]); ?></td>
                            <td align="center">
                                <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a> |
                                <a href="<?php echo U('remove',['id'=>$row['id']]);?>" title="删除">移除</a> 
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
            </div>
        </form>

        <div id="footer">
            共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
        </div>
    </body>
</html>