<section id="main" class="column" style="height: 1100px">
    <!-- TOP10 联网方式 -->
    <div style="height:480px;">
        <iframe src="<?php echo site_url() ?>/report/network/addnetworkreport" frameborder="0" scrolling="no"
                style="width:100%;height:100%;"></iframe>
    </div>

    <!-- 联网方式分布明细 -->
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved"><?php echo lang('v_rpt_nw_details') ?></h3>
			<span class="relative r"> <a
				href="<?php echo site_url()?>/report/network/export"
				class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
			</span>
		</header>

        <table class="tablesorter" cellspacing="0">
            <thead>
            <tr>
                <!-- 联网方式 -->
                <th><?php echo lang('m_rpt_networking') ?></th>
                <!-- 启动次数 -->
                <th><?php echo lang('t_sessions') ?></th>
                <!-- 启动次数比例 -->
                <th><?php echo lang('t_sessionsP') ?></th>
                <!-- 新增用户 -->
                <th><?php echo lang('t_newUsers') ?></th>
                <!-- 新增用户比例 -->
                <th><?php echo lang('t_percentOfNewUsers') ?></th>
            </tr>
            </thead>
            <tbody id="detailInfo">
            <div id='out'>
                <?php
                $num = count($details->result());
                $array = $details->result();
                if (count($array) < PAGE_NUMS) {
                    $nums = count($array);
                } else {
                    $nums = PAGE_NUMS;
                }
                ?>
            </div>
            </tbody>
        </table>
        <!-- 分页 -->
        <footer>
            <div id="pagination" class="submit_link"></div>
        </footer>
    </article>
</section>


<script type="text/javascript">
    $(document).ready(function () {
        initPagination();
        pageselectCallback(0, null);
    });
    var detailObj = eval(<?php echo "'".json_encode($details->result())."'"?>);
    var session_num = <?php echo $sessions ?>;
    var newuser_num = <?php echo $newusers ?>;

    function pageselectCallback(page_index, jq) {
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";
        var index = page_index *<?php echo PAGE_NUMS?>;
        var pagenum = <?php echo PAGE_NUMS?>;
        var msg = "";

        for (i = 0; i < pagenum && (index + i) < detailObj.length; i++) {
            msg = msg + "<tr><td>";
            if (detailObj[i + index].networkname.length < 1)
                detailObj[i + index].networkname = 'unknown';
            msg = msg + detailObj[i + index].networkname;
            msg = msg + "</td><td>";
            msg = msg + detailObj[i + index].sessions;
            msg = msg + "</td><td>";
            msg = msg + ((session_num > 0) ? (100 * detailObj[i + index].sessions / session_num).toFixed(2) : 0) + "%";
            msg = msg + "</td><td>";
            msg = msg + detailObj[i + index].newusers;
            msg = msg + "</td><td>";
            msg = msg + ((newuser_num > 0) ? (100 * detailObj[i + index].newusers / newuser_num).toFixed(2) : 0) + "%";
            msg = msg + "</td></tr>";
        }

        //document.getElementById('detailInfo').innerHTML = msg;
        $('#detailInfo').html(msg);
        return false;
    }

    /**
     * Callback function for the AJAX content loader.
     */
    function initPagination() {
        var num_entries =
        <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
        // Create pagination element
        $("#pagination").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo  lang('g_previousPage')?>',
            next_text: '<?php echo  lang('g_nextPage')?>',
            num_display_entries: 4,
            callback: pageselectCallback,
            items_per_page: 1
        });
    }
</script>