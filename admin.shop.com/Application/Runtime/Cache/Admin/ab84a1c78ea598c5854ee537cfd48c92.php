<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 添加角色 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/Public/ext/ztree/zTreeStyle.css" type="text/css"/>
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('index');?>">管理角色</a></span>
            <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 添加角色 </span>
        </h1>
        <div style="clear:both"></div>
        <div class="main-div">
            <form method="post" action="<?php echo U();?>" enctype="multipart/form-data" >
                <table cellspacing="1" cellpadding="3" width="100%">
                    <tr>
                        <td class="label">角色名称</td>
                        <td>
                            <?php if(isset($row)): echo ($row["name"]); ?>
                                <?php else: ?>
                                <input type="text" name="name" maxlength="60" value=""/><?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">角色描述</td>
                        <td>
                            <textarea  name="intro" cols="60" rows="4"  ><?php echo ($row["intro"]); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">权限</td>
                        <td>
                            <div id='permission_ids'>

                            </div>
                            <ul class="ztree" id='permissions'></ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><br />
                            <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>" />
                            <input type="submit" class="button" value=" 确定 " />
                            <input type="reset" class="button" value=" 重置 " />
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div id="footer">
            共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
        </div>
        <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
        <script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.core.min.js"></script>
        <script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.excheck.min.js"></script>

        <script type='text/javascript'>
            var setting = {
                check:{
                    enable:true,
                },
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey:'parent_id',
                    }
                },
                callback:{
                    onCheck:function(event,ele_id,node){
                        //获取所有被勾选的节点
                        var nodes = ztree_obj.getCheckedNodes(true);
                        var box = $('#permission_ids');
                        box.empty();
                        $(nodes).each(function(i,v){
                            var html = '<input type="hidden" name="permission_id[]" value="'+v.id+'"/>';
                            $(html).appendTo(box);
                        });
                    },
                },
            };

            var zNodes = <?php echo ($permissions); ?>;
            var ztree_obj;

            $(document).ready(function() {
                ztree_obj = $.fn.zTree.init($("#permissions"), setting, zNodes);
                ztree_obj.expandAll(true);

                //编辑页面回显关联的权限
                <?php if(isset($row)): ?>var permission_ids = <?php echo ($row["permission_ids"]); ?>;
                    $(permission_ids).each(function(i,v){
                        var node = ztree_obj.getNodeByParam('id',v);
                        ztree_obj.checkNode(node,true,false,true)
                    });<?php endif; ?>
            });
        </script>
    </body>
</html>