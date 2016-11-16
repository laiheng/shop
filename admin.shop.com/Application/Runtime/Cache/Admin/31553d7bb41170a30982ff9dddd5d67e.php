<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: category_info.htm 16752 2009-10-20 09:59:38Z wangleisvn $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 添加菜单 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
        <link href="/Public/ext/ztree/zTreeStyle.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('index');?>">菜单列表</a></span>
            <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 添加菜单 </span>
        </h1>
        <div style="clear:both"></div>
        <div class="main-div">
            <form action="<?php echo U();?>" method="post" name="theForm" enctype="multipart/form-data">
                <table width="100%" id="general-table">
                    <tr>
                        <td class="label">菜单名称:</td>
                        <td>
                            <input type='text' name='name' value='<?php echo ($row["name"]); ?>' size='27' /> <font color="red">*</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">菜单路径:</td>
                        <td>
                            <input type='text' name='path' value='<?php echo ($row["path"]); ?>' size='27' />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">父级菜单:</td>
                        <td>
                            <input type="hidden" name="parent_id" id="parent_id" />
                            <input type='text' disabled='disabled' id='parent_name' style="padding-left:1em;"/>
                            <ul id="parent_nodes" class="ztree"></ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">关联权限:</td>
                        <td>
                            <div id='permission_ids'></div>
                            <ul id="permission_nodes" class="ztree"></ul>
                        </td>
                    </tr>
                </table>
                <div class="button-div">
                    <input type="hidden" name="id" value='<?php echo ($row["id"]); ?>'/>
                    <input type="submit" value=" 确定 " />
                    <input type="reset" value=" 重置 " />
                </div>
            </form>
        </div>

        <div id="footer">
            共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
        </div>

        <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
        <script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.core.min.js"></script>
        <script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.excheck.min.js"></script>

        <script type="text/javascript">
            //----------------------------父级菜单------------------------\\
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        idKey: "id",
                        pIdKey:'parent_id',
                        rootPId: 0
                    }
                },
                callback:{
                    onClick:function(event,ztree_obj,node){
                        //取出点击节点的数据，放到表单节点中
                        $('#parent_id').val(node.id);
                    },
                },
            };

            var zNodes = <?php echo ($menus); ?>;
            //初始化ztree插件
            var ztree_obj = $.fn.zTree.init($("#parent_nodes"), setting, zNodes);
            //展开所有的节点
            ztree_obj.expandAll(true);
            //回显父级菜单
            <?php if(isset($row)): ?>//找到父级节点
                var parent_node = ztree_obj.getNodeByParam('id',<?php echo ($row["parent_id"]); ?>);
                //选中父级节点
                ztree_obj.selectNode(parent_node);
                //将数据放到控件中
                $('#parent_id').val(parent_node.id);
                $('#parent_name').val(parent_node.name);<?php endif; ?>


            //--------------------------------权限列表------------------------------\\
            var perm_setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey:'parent_id',
                    }
                },
                check:{
                    enable:true,
                },
                callback:{
                    onCheck:function(event,ele_id,node){
                        //获取所有的被勾选权限
                        var nodes = perm_ztree_obj.getCheckedNodes(true);
                        //遍历这些节点,清空隐藏域,添加当前的所有勾选的节点
                        var box = $('#permission_ids');
                        box.empty();
                        $(nodes).each(function(i,v){
                            var html = '<input type="hidden" name="permission_id[]" value="'+v.id+'"/>';
                            $(html).appendTo(box);
                        });
                    },
                },
            };
            //所有的权限
            var perm_nodes = <?php echo ($permissions); ?>;
            var perm_ztree_obj;
            $(document).ready(function() {
                //初始化
                perm_ztree_obj = $.fn.zTree.init($("#permission_nodes"), perm_setting, perm_nodes);
                //展开所有的节点
                perm_ztree_obj.expandAll(true);
                //编辑页面回显关联的权限
                <?php if(isset($row)): ?>var permission_ids = <?php echo ($row["permission_ids"]); ?>;
                    $(permission_ids).each(function(i,v){
                        //找到节点
                        var node = perm_ztree_obj.getNodeByParam('id',v);
                        //选中节点
                        perm_ztree_obj.checkNode(node,true,false,true)
                    });<?php endif; ?>
            });
        </script>
    </body>
</html>