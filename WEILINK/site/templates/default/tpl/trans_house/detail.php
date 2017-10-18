<div class="infor-table">
    <div class="contact-info">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%">转运国家： <?php echo $output['info']['country'] ?></td>
                <td width="50%">中转仓名称： <?php echo $output['info']['tc_name'] ?> </td>
            </tr>
        </table>
        <div class="line-row"></div>
        <?php if ($output['channels']): ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php foreach ($output['channels'] as $row): ?>
                    <tr>
                        <td colspan="2"><?php echo $row['channel_name']?>： 首重：<?php echo $row['first_weight']?>kg，续重：<?php echo $row['continue_weight']?>kg，首重费：<?php echo $row['first_weight_fee']?>元，续重费：<?php echo $row['continue_weight_fee']?>元</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="line-row"></div>
        <?php endif; ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%">基础加固： <?php echo $output['info']['force_type']['base'] . $output['fixs'] ?></td>
                <td width="50%">特殊加固： <?php echo $output['info']['force_type']['spec'] . $output['fixs'] ?></td>
            </tr>
            <tr>
                <td width="50%">合箱： <?php echo $output['info']['combine_separate']['combine'] . $output['fixs'] ?></td>
                <td width="50%">分箱： <?php echo $output['info']['combine_separate']['separate'] . $output['fixs'] ?></td>
            </tr>
            <tr>
                <td width="50%">外箱更换： <?php echo $output['info']['box_change']['out'] . $output['fixs'] ?></td>
                <td width="50%">智能换箱： <?php echo $output['info']['box_change']['auto'] . $output['fixs'] ?></td>
            </tr>
            <tr>
                <td width="50%">包装(信封/快递袋)： <?php echo $output['info']['pack_size']['min'] . $output['fixs'] ?></td>
                <td width="50%">包装(纸箱)： <?php echo $output['info']['pack_size']['max'] . $output['fixs'] ?></td>
            </tr>
            <tr>
                <td width="50%">发票取出： <?php echo $output['info']['open_box']['Y'] . $output['fixs'] ?></td>
                <td width="50%">开箱清点： <?php echo $output['info']['box_change']['Y'] . $output['fixs'] ?></td>
            </tr>
            <tr>
                <td width="50%">保价(保￥1000.00)： <?php echo $output['info']['insured']['p20'] . $output['fixs'] ?></td>
                <td width="50%">保价(保￥2000.00)： <?php echo $output['info']['insured']['p40'] . $output['fixs'] ?></td>
            </tr>
        </table>
    </div>
</div>




