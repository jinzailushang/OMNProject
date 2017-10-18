
<style>
    body{overflow: auto}
    .logList li{
        line-height:200%;
    }
    .logList li>strong{
        display:inline-block;
        width:150px;
    }
</style>

<div class="row-log">
    <div class="log-title"><fieldset><legend>物流轨迹</legend></fieldset></div>
    <div class="logList">
        <ul>    <?php if ($output['data_list']): ?>
                <?php foreach ($output['data_list'] as $k => $row): ?>
                    <li><strong><?php echo preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $row->CreatedTime) ?></strong> <?php echo $row->StatusDesc ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="text-align:center">暂无物流！</li>
                <?php endif; ?>
        </ul>
    </div>
</div>






