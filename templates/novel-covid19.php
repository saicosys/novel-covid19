<?php
wp_enqueue_style('novelcovid');
$last_updated = get_option('novel_covid19_last_updated');
$detail = $params['show_detail'];
//Global Ratio
$active = ($data->active / $data->cases) * 100;
$recovered = ($data->recovered / $data->active) * 100;
$deaths = ($data->deaths / $data->active) * 100;
?>
<div class="st-content-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-inner card-bordered card-full">
                    <div class="st-cov-wg1">
                        <?php if (!empty($params['title'])) : ?>
                            <div class="card-title">
                                <h2 class="title">
                                    <?php
                                    echo esc_html($params['title']);
                                    echo esc_html(isset($data->country) ? ' - ' . $data->country : ' - Worldwide');
                                    ?>
                                </h2>
                            </div>
                        <?php endif; ?>
                        <div class="st-cov-data">
                            <h4 class="overline-title"><?php echo esc_html($params['label_confirmed']); ?></h4>
                            <div class="amount">
                                <?php
                                echo number_format($data->cases);
                                if (isset($data->todayCases) && $data->todayCases > 0) {
                                    echo '<small>+' . number_format($data->todayCases) . ' <span class="new-label">' . esc_html($params['label_new']) . '</span></small>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="st-cov-wg1-progress">
                            <div class="progress progress-reverse progress-md progress-pill progress-bordered">
                                <div class="progress-bar bg-danger" data-progress="<?php echo number_format($deaths, 2); ?>" data-toggle="tooltip" title="" data-original-title="Deaths : <?php echo number_format($deaths, 2) . '%'; ?>" style="width: <?php echo number_format($deaths, 2) . '%'; ?>;"></div>
                                <div class="progress-bar bg-success" data-progress="<?php echo number_format($recovered, 2); ?>" data-toggle="tooltip" title="" data-original-title="Recovered : <?php echo number_format($recovered, 2) . '%'; ?>" style="width: <?php echo number_format($recovered, 2) . '%'; ?>;"></div>
                                <div class="progress-bar bg-purple" data-progress="<?php echo number_format($active, 2); ?>" data-toggle="tooltip" title="" data-original-title="Active Cases : <?php echo number_format($active, 2) . '%'; ?>" style="width: <?php echo number_format($active, 2) . '%'; ?>;"></div>
                            </div>
                        </div>

                        <ul class="st-cov-wg1-data">
                            <li>
                                <div class="title">
                                    <div class="dot dot-lg sq bg-purple"></div>
                                    <span><?php echo esc_html($params['label_active']); ?></span>
                                </div>
                                <div class="count text-purple"><?php echo number_format($data->active); ?></div>
                            </li>
                            <li>
                                <div class="title">
                                    <div class="dot dot-lg sq bg-success"></div>
                                    <span><?php echo esc_html($params['label_recovered']); ?></span>
                                </div>
                                <div class="count text-success"><?php echo number_format($data->recovered); ?></div>
                            </li>
                            <li>
                                <div class="title">
                                    <div class="dot dot-lg sq bg-danger"></div>
                                    <span><?php echo esc_html($params['label_deaths']); ?></span>
                                </div>
                                <div class="count text-danger">
                                    <?php echo number_format($data->deaths); ?>
                                    <div class="new-today">
                                        <?php
                                        if (isset($data->todayDeaths) && $data->todayDeaths > 0) {
                                            echo '+' . number_format($data->todayDeaths) . ' <span class="new-label">' . esc_html($params['label_new']) . '</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="st-cov-wg-note">
                            <span class="text-primary">Recovery Ratio (<?php echo number_format($recovered) . '%'; ?>)</span> &amp;
                            <span class="text-primary">Deaths Ratio (<?php echo number_format($deaths) . '%'; ?>)</span>.
                        </div>
                    </div>
                    <?php if ($detail) : ?>
                        <div class="st-cov-wg2-group-bottom st-cov-wg2-group">
                            <div class="st-cov-data">
                                <h4 class="overline-title">Currently
                                    <br class="d-xxl-none"> Tests Done
                                </h4>
                                <div class="amount amount-xs"><?php echo number_format($data->tests); ?></div>
                            </div>
                            <ul class="st-cov-wg2-data">
                                <li>
                                    <div class="title">
                                        <div class="dot dot-lg sq bg-info"></div>
                                        <span><?php echo esc_html($params['label_testsPerOneMillion']); ?></span>
                                    </div>
                                    <div class="count"><?php echo number_format($data->testsPerOneMillion); ?></div>
                                </li>
                                <li>
                                    <div class="title">
                                        <div class="dot dot-lg sq bg-purple"></div>
                                        <span><?php echo esc_html($params['label_casesPerOneMillion']); ?></span>
                                    </div>
                                    <div class="count"><?php echo number_format($data->casesPerOneMillion); ?></div>
                                </li>
                                <li>
                                    <div class="title">
                                        <div class="dot dot-lg sq bg-danger"></div>
                                        <span><?php echo esc_html($params['label_deathsPerOneMillion']); ?></span>
                                    </div>
                                    <div class="count"><?php echo number_format($data->deathsPerOneMillion); ?></div>
                                </li>
                                <li>
                                    <div class="title">
                                        <div class="dot dot-lg sq bg-danger"></div>
                                        <span><?php echo esc_html($params['label_critical']); ?></span>
                                    </div>
                                    <div class="count"><?php echo number_format($data->critical); ?></div>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if ($last_updated && $params['label_updated']) : ?>
                        <div class="covid-updated">
                            <?php
                            echo esc_html($params['label_updated']);
                            echo date_i18n(get_option('date_format') . ' - ' . get_option('time_format') . ' (P)', $last_updated);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>