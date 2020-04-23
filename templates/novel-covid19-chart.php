<?php
wp_enqueue_style('novelcovid');
wp_enqueue_script('novel-covid19');
wp_enqueue_script('chartjs');
$id = 'novel_covid19_chart_' . uniqid();
$last_updated = get_option('novel_covid19_last_updated');
?>
<div class="st-content-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-inner card-bordered card-full">
                    <?php if (!empty($params['title'])) : ?>
                        <div class="card-title">
                            <h4 class="title">
                                <?php
                                echo esc_html($params['title']);
                                echo esc_html(isset($data->country) ? ' - ' . $data->country : ' - Worldwide');
                                ?>
                            </h4>
                        </div>
                    <?php endif; ?>
                    <div class="covid-chart">
                        <canvas id="<?php echo esc_attr($id); ?>" data-type="<?php esc_attr_e($params['type']); ?>" data-confirmed="<?php esc_attr_e($params['label_confirmed']); ?>" data-deaths="<?php esc_attr_e($params['label_deaths']); ?>" data-recovered="<?php esc_attr_e($params['label_recovered']); ?>" data-json="<?php esc_attr_e(json_encode($data)); ?>" data-country="<?php esc_attr_e($params['country']); ?>"></canvas>
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
</div>