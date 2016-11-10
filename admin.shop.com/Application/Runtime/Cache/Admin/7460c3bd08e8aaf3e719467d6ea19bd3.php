<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - 商品列表 </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="/Public/css/general.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/main.css" rel="stylesheet" type="text/css" />
        <link href="/Public/css/page.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('add');?>">添加新商品</a></span>
            <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - 商品列表 </span>
        </h1>
        <div style="clear:both"></div>
        <div class="form-div">
            <form action="<?php echo U();?>" name="searchForm">
                <img src="/Public/images/icon_search.gif" width="26" height="22" border="0" alt="search" />
                <!-- 分类 -->
                <?php echo arr2select($goods_categories,'name','id','goods_category_id',I('get.goods_category_id'));?>
                <!-- 品牌 -->
                <?php echo arr2select($brands,'name','id','brand_id',I('get.brand_id'));?>
                <!-- 推荐 -->
                <?php echo arr2select($goods_statuses,'name','id','goods_status',I('get.goods_status'));?>
                <!-- 上架 -->
                <?php echo arr2select($is_on_sales,'name','id','is_on_sale',I('get.is_on_sale'));?>
                <input type="text" name="name" size="15" value='<?php echo I("get.name");?>'/>
                <input type="submit" value=" 搜索 " class="button" />
            </form>
        </div>
        <form method="post" action="" name="listForm">
            <div class="list-div" id="listDiv">
                <table cellpadding="3" cellspacing="1">
                    <tr>
                        <th>编号</th>
                        <th>商品名称</th>
                        <th>货号</th>
                        <th>价格</th>
                        <th>上架</th>
                        <th>精品</th>
                        <th>新品</th>
                        <th>热销</th>
                        <th>推荐排序</th>
                        <th>库存</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($rows)): foreach($rows as $key=>$row): ?><tr>
                        <td align="center"><?php echo ($row["id"]); ?></td>
                        <td class="first-cell" align="center"><?php echo ($row["name"]); ?></td>
                        <td align="center"><?php echo ($row["sn"]); ?></td>
                        <td align="center"><?php echo ($row["shop_price"]); ?></td>
                        <td align="center"><img src="/Public/images/<?php echo ($row["is_on_sale"]); ?>.gif" /></td>
                        <td align="center"><img src="/Public/images/1.gif" /></td>
                        <td align="center"><img src="/Public/images/1.gif" /></td>
                        <td align="center"><img src="/Public/images/1.gif" /></td>
                        <td align="center"><?php echo ($row["sort"]); ?></td>
                        <td align="center"><?php echo ($row["stock"]); ?></td>
                        <td align="center">
                            <a href="<?php echo U('edit',['id'=>$row['id']]);?>" title="编辑">编辑</a> |
                            <a href="<?php echo U('remove',['id'=>$row['id']]);?>" title="编辑">移除</a> 
                        </td>
                    </tr><?php endforeach; endif; ?>
                    <tr>
                        <td align="right" nowrap="true" colspan="30" style="padding-right: 30px;">
                            <div class="col-xs-12 col-md-12 col-lg-12 b-page" >
                                <?php echo ($page_html); ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>

        <div id="footer">
            共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
    </body>
</html>