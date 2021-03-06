<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 添加商品 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
        <link href="/Public/ext/ztree/zTreeStyle.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="/Public/ext/uploadify/common.css" />
        <style type="text/css">
            .upload-pre-item-gallery img{
                /*width:150px;*/
                max-height: 113px;
            }

            .upload-pre-item-gallery{
                display:inline-block;
            }

            .upload-pre-item-gallery a{
                position:relative;
                top:5px;
                right:15px;
                float:right;
                color:red;
                font-size:16px;
                text-decoration:none;
            }
            .upload-img-box{
                margin-bottom: 10px;;
            }
        </style>
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('index');?>">商品列表</a></span>
            <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 添加商品 </span>
        </h1>
        <div style="clear:both"></div>
        <div class="main-div">
            <form method="post" action="<?php echo U();?>" enctype="multipart/form-data" >
                <table cellspacing="1" cellpadding="3" width="100%">
                    <tr>
                        <td class="label">商品名称：</td>
                        <td><input type="text" name="name" value="<?php echo ($row["name"]); ?>" size="30" />
                            <span class="require-field">*</span></td>
                    </tr>
                    <tr>
                        <td class="label">LOGO</td>
                        <td>
                            <input type="file" id="logo" size="45"/>
                            <input type="hidden" name='logo' value="<?php echo ($row["logo"]); ?>" id='logo-url'/>
                            <img src='<?php echo ($row["logo"]); ?>' id='logo-preview' style='max-width: 80px;max-height: 60px;margin-top:10px'/>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品货号： </td>
                        <td>
                            <?php if(isset($row)): ?><input type="text" name="sn" disabled='disabled' value="<?php echo ($row["sn"]); ?>" size="20"/>
                                <?php else: ?>
                                <input type="text" name="sn" value="" size="20"/><?php endif; ?>
                            <span id="goods_sn_notice"></span><br />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品分类：</td>
                        <td>
                            <input type="hidden" name="goods_category_id" id='goods_category_id'/>
                            <input type='text' disabled='disabled' id='goods_category_name' style="padding-left:1em;"/>
                            <ul id='goods_categories' class='ztree'></ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品品牌：</td>
                        <td>
                            <?php echo arr2select($brands,'name','id','brand_id',$row['brand_id']);?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">供货商：</td>
                        <td>
                            <?php echo arr2select($suppliers,'name','id','supplier_id',$row['supplier_id']);?>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">本店售价：</td>
                        <td>
                            <input type="text" name="shop_price" value="<?php echo ($row["shop_price"]); ?>" size="20"/>
                            <span class="require-field">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">市场售价：</td>
                        <td>
                            <input type="text" name="market_price" value="<?php echo ($row["market_price"]); ?>" size="20" />
                            <span class="require-field">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品数量：</td>
                        <td>
                            <input type="text" name="stock" size="8" value="<?php echo ((isset($row["stock"]) && ($row["stock"] !== ""))?($row["stock"]):100); ?>"/>
                        </td>
                    </tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="1" class="is_on_sale"/> 是
                        <input type="radio" name="is_on_sale" value="0" class="is_on_sale"/> 否
                    </td>
                    </tr>
                    <tr>
                        <td class="label">加入推荐：</td>
                        <td>
                            <input type="checkbox" name="goods_status[]" value="1" class="goods_status"/> 精品
                            <input type="checkbox" name="goods_status[]" value="2" class="goods_status"/> 新品
                            <input type="checkbox" name="goods_status[]" value="4" class="goods_status"/> 热销
                        </td>
                    </tr>
                    <tr>
                        <td class="label">推荐排序：</td>
                        <td>
                            <input type="text" name="sort" size="5" value="<?php echo ((isset($row["sort"]) && ($row["sort"] !== ""))?($row["sort"]):50); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品详细描述：</td>
                        <td>
                            <textarea name="content" cols="40" rows="3" id='editor'><?php echo ($row["content"]); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">商品相册：</td>
                        <td>
                            <div class="upload-img-box">
                                <?php if(is_array($row["galleries"])): foreach($row["galleries"] as $key=>$gallery): ?><div class="upload-pre-item-gallery">
                                        <img src="<?php echo ($gallery["path"]); ?>"/>
                                        <a href="#" data='<?php echo ($gallery["id"]); ?>'>×</a>
                                    </div><?php endforeach; endif; ?>
                            </div>
                            <div>
                                <input type="file" id='goods-gallery-upload'/>
                            </div>
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
            共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。
        </div>

        <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
        <script type="text/javascript" src="/Public/ext/ztree/jquery.ztree.core.min.js"></script>
        <script type="text/javascript" src="/Public/ext/uploadify/jquery.uploadify.min.js"></script>
        <script type="text/javascript" src="/Public/ext/layer/layer.js"></script>
        <script type="text/javascript" charset="utf-8" src="/Public/ext/ueditor/my.config.js"></script>
        <script type="text/javascript" charset="utf-8" src="/Public/ext/ueditor/ueditor.all.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="/Public/ext/ueditor/lang/zh-cn/zh-cn.js"></script>
        <script type="text/javascript">
            //-------------------------回显商品上架状态和促销状态-------------------------//
            $('.is_on_sale').val([<?php echo ((isset($row["is_on_sale"]) && ($row["is_on_sale"] !== ""))?($row["is_on_sale"]):1); ?>]);
            $('.goods_status').val(<?php echo ((isset($row["goods_status"]) && ($row["goods_status"] !== ""))?($row["goods_status"]):'[1]'); ?>);

            //-----------------------------ueditor开始---------------------------------//
            var ue = UE.getEditor('editor',{serverUrl: '<?php echo U('Editor/ueditor');?>'});
            //------------------------------ueditor结束------------------------------//

            //-------------------------------ztree开始-----------------------------//
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        pIdKey: 'parent_id',
                    },
                },
                callback:{
                    onClick:function(event,node,item){
                        //取出点击节点的数据，放到表单节点中
                        $('#goods_category_id').val(item.id);
                        $('#goods_category_name').val(item.name);
                    },
                },
            };

            var goods_categories = <?php echo ($goods_categories); ?>;
            $(function () {
                //初始化ztree插件
                var goods_category_ztree = $.fn.zTree.init($("#goods_categories"), setting, goods_categories);
                //展开所有的节点
                goods_category_ztree.expandAll(true);
                //编辑页面回显父级分类
                <?php if(isset($row)): ?>//获取父级分类在ztree中的节点
                    var parent_node = goods_category_ztree.getNodeByParam('id',<?php echo ($row["goods_category_id"]); ?>);
                    goods_category_ztree.selectNode(parent_node);
                    $('#goods_category_id').val(parent_node.id);
                    $('#goods_category_name').val(parent_node.name);<?php endif; ?>
            });
            //---------------------------------ztree结束---------------------------------//

            //----------------------------uploadify上传LOGO开始----------------------------//
            $('#logo').uploadify({
                swf: '/Public/ext/uploadify/uploadify.swf',
                uploader: "<?php echo U('Upload/upload');?>",
                buttonText:'选择文件',
                fileTypeDesc:'选择文件',
                onUploadSuccess:function(file,response){
                    //将响应数据转换为json对象
                    var data = $.parseJSON(response);
                    if(data.status == 0){
                        layer.msg(data.msg,{icon: 5});
                    }else{
                        layer.msg(data.msg,{icon: 6});
                        $('#logo-url').val(data.url);
                        $('#logo-preview').attr('src',data.url);
                    }
                },
            });
            //-----------------------------uploadify上传LOGO结束----------------------------//

            //-----------------------------uploadify上传相册开始---------------------------//
            $('#goods-gallery-upload').uploadify({
                swf: '/Public/ext/uploadify/uploadify.swf',
                uploader: "<?php echo U('Upload/upload');?>",
                buttonText:'选择文件',
                fileTypeDesc:'选择文件吧',
                onUploadSuccess:function(file,response){
                    //将响应数据转换为json对象
                    var data = $.parseJSON(response);
                    if(data.status){ //上传成功
                        //使用layer弹出提示
                        layer.alert(data.msg, {icon: 6});
                       //创建div,预览图片
                        var html = '<div class="upload-pre-item-gallery">\
                                        <img src="'+data.url+'"/>\
                                        <a href="#">×</a>\
                                        <input type="hidden" value="'+data.url+'" name="path[]"/>\
                                    </div>';
                        $(html).appendTo($('.upload-img-box'));
                    }else{ //上传失败
                        //提示
                        layer.alert(data.msg, {icon: 5});
                    }
                }
            });
            //-------------------------- uploadify上传相册结束 -----------------------------//

            //--------------------------- 通过ajax删除相册 ---------------------------------//
            //绑定事件,事件委托,因为js添加的节点,不使用委托,无法监听
            $('.upload-img-box').on('click','a',function(){
                var gallery_id = $(this).attr('data');
                var parent_node = $(this).parent();
                var url = '<?php echo U("GoodsGallery/remove");?>';
                //删除数据库已有的
                if(gallery_id){
                    //说明是数据库中已存的,需要ajax删除
                    var data = {
                        id:gallery_id,
                    };
                    $.getJSON(url,data,function(data){
                        if(data.status){
                            layer.alert(data.info, {icon: 6});
                            parent_node.remove();
                        }else{
                            layer.alert(data.info, {icon: 5});
                        }
                    });
                }else{ //删除刚刚上传还没有保存的
                    parent_node.remove();
                    layer.alert('删除成功', {icon: 6});
                }
            });

        </script>
    </body>
</html>