<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/category" method="get">
        <label for="name">顶级服务类型名称：</label>
        <input id="name" type="text" name="Search[name]" value="<?= empty($search['name']) ? '' : $search['name']?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>

    </form>
</div>