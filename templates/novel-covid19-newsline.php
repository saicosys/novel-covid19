<?php
wp_enqueue_style('novelcovid');
$last_updated = get_option('novel_covid19_last_updated');
?>
<div class="st-content-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="covid-line">
                <div class="covid-line-wrap">
                    <div class="covid-line-colour covid-slideup covid-animated"></div>
                    <div class="covid-line-title covid-slidein">
                        <?php echo esc_html(isset($params['title']) ? $params['title'] : ''); ?>
                    </div>
                    <div class="covid-line-headline covid-fadein covid-marquee">
                        <span class="line--confirmed">
                            <?php echo esc_html($params['label_confirmed']); ?>
                            <span class="line--value">
                                <?php echo number_format($data->cases); ?>
                                <span class="line--today">
                                    <?php
                                    if (isset($data->todayCases) && $data->todayCases > 0) {
                                        echo '<span class="line--new">+' . number_format($data->todayCases) . esc_html($params['label_new']) . '</span>';
                                    }
                                    ?>
                                </span>
                            </span>
                        </span>

                        <span class="line--deaths">
                            <?php echo esc_html($params['label_deaths']); ?>
                            <span class="line--value">
                                <?php echo number_format($data->deaths); ?>
                                <span class="line--today">
                                    <?php
                                    if (isset($data->todayDeaths) && $data->todayDeaths > 0) {
                                        echo '<span class="line--new">+' . number_format($data->todayDeaths) . esc_html($params['label_new']) . '</span>';
                                    }
                                    ?>
                                </span>
                            </span>
                        </span>

                        <span class="line--recovered">
                            <?php echo esc_html($params['label_recovered']); ?>
                            <span class="line--value">
                                <?php echo number_format($data->recovered); ?>
                            </span>
                        </span>

                        <span class="line--active">
                            <?php echo esc_html($params['label_active']); ?>
                            <span class="line--value">
                                <?php echo number_format($data->active); ?>
                            </span>
                        </span>

                        <?php if ($last_updated && $params['label_updated']) : ?>
                            <span class="line--updated">
                                <?php
                                echo esc_html($params['label_updated']);
                                echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated);
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>