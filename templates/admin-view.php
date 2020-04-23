<?php
wp_enqueue_style('novelcovid');
wp_enqueue_script('novel-covid19');
$id = 'novel_covid19_chart_' . uniqid();
$last_updated = get_option('novel_covid19_last_updated');
?>
<div class="st-main">
    <div class="st-wrap">
        <div class="st-contents">
            <div class="container-fluid">
                <div class="st-content-body">
                    <div class="st-block-head st-block-head-sm">
                        <div class="st-block-between flex-wrap g-1 align-start">
                            <div class="st-block-head-content">
                                <h3 class="st-block-title heading-title"><?php echo esc_html($params['heading']); ?> <a href="https://www.saicosys.com" target="_blank">Saicosys Team</a></h3>
                            </div>
                            <div class="st-block-head-content">
                                <?php if ($last_updated && $params['label_updated']) : ?>
                                    <p class="note-text">
                                        <?php
                                        echo esc_html($params['label_updated']);
                                        echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated);
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="st-block">
                        <div class="row g-gs">
                            <div class="col-xl-4">
                                <div class="row g-gs">
                                    <div class="col-lg-6 col-xl-12">
                                        <?php echo do_shortcode('[novel-covid19 show_detail="no"]'); ?>
                                    </div>
                                    <div class="col-lg-6 col-xl-12">
                                        <?php echo do_shortcode('[novel-covid19 country="India"]'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <?php echo do_shortcode('[novel-covid19-map]'); ?>
                            </div>
                            <div class="col-xl-12">
                                <?php echo do_shortcode('[novel-covid19-table show_detail="no"]'); ?>
                            </div>
                            <div class="col-xl-12">
                                <?php echo do_shortcode('[novel-covid19-chart]'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>