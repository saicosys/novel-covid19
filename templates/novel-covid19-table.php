<?php
wp_enqueue_style('novelcovid');
wp_enqueue_style('datatables');
wp_enqueue_script('novel-covid19');
wp_enqueue_script('jquery.datatables');
$detail = $params['show_detail'];
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
                                <?php echo esc_html($params['title']); ?>
                            </h4>
                        </div>
                    <?php endif; ?>
                    <div class="dataTables_wrapper">
                        <table id="worldwide" class="table table-striped table-bordered table-hover table-checkable datatables dataTable no-footer dtr-inline" data-page-length="<?php echo esc_attr($params['showing']); ?>">
                            <thead>
                                <tr>
                                    <th class="flex-fill bg-primary-dim text-primary"><?php echo esc_html($params['label_country']); ?></th>
                                    <th class="flex-fill bg-orange-dim text-orange"><?php echo esc_html($params['label_confirmed']); ?></th>
                                    <th class="flex-fill bg-warning-dim text-warning"><?php echo esc_html($params['label_confirmedtoday']); ?></th>
                                    <th class="flex-fill bg-gray-dim text-gray"><?php echo esc_html($params['label_active']); ?></th>
                                    <th class="flex-fill bg-dark-dim text-dark"><?php echo esc_html($params['label_deaths']); ?></th>
                                    <th class="flex-fill bg-danger-dim text-danger"><?php echo esc_html($params['label_deathstoday']); ?></th>
                                    <th class="flex-fill bg-success-dim text-success"><?php echo esc_html($params['label_recovered']); ?></th>
                                    <th class="flex-fill bg-danger-dim text-danger"><?php echo esc_html($params['label_critical']); ?></th>
                                    <?php if ($detail) : ?>
                                        <th class="flex-fill bg-info-dim text-info"><?php echo esc_html($params['label_tests']); ?></th>
                                        <th class="flex-fill bg-blue-dim text-blue"><?php echo esc_html($params['label_testsPerOneMillion']); ?></th>
                                        <th class="flex-fill bg-azure-dim text-azure"><?php echo esc_html($params['label_casesPerOneMillion']); ?></th>
                                        <th class="flex-fill bg-danger-dim text-danger"><?php echo esc_html($params['label_deathsPerOneMillion']); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $key => $value) : ?>
                                    <tr>
                                        <td>
                                            <?php if (isset($value->countryInfo->flag)) : ?>
                                                <img width="20" class="covid-flag" src="<?php echo esc_html($value->countryInfo->flag); ?>" />
                                            <?php endif; ?>
                                            <?php echo esc_html($value->country); ?>
                                        </td>
                                        <?php echo ($value->cases > 0)  ? '<td class="flex-fill bg-orange-dim text-orange">' . number_format($value->cases) . '</td>' : '<td>' . number_format($value->cases) . '</td>'; ?>
                                        <?php echo ($value->todayCases > 0)  ? '<td class="flex-fill bg-warning-dim text-warning">+' . number_format($value->todayCases) . '</td>' : '<td>' . number_format($value->todayCases) . '</td>'; ?>
                                        <?php echo ($value->active > 0)  ? '<td class="flex-fill bg-gray-dim text-gray">' . number_format($value->active) . '</td>' : '<td>' . number_format($value->active) . '</td>'; ?>
                                        <?php echo ($value->deaths > 0)  ? '<td class="bg-light text-dark">' . number_format($value->deaths) . '</td>' : '<td>' . number_format($value->deaths) . '</td>'; ?>
                                        <?php echo ($value->todayDeaths > 0)  ? '<td class="flex-fill bg-danger-dim text-danger">+' . number_format($value->todayDeaths) . '</td>' : '<td>' . number_format($value->todayDeaths) . '</td>'; ?>
                                        <?php echo ($value->recovered > 0)  ? '<td class="flex-fill bg-success-dim text-success">' . number_format($value->recovered) . '</td>' : '<td>' . number_format($value->recovered) . '</td>'; ?>
                                        <?php echo ($value->critical > 0)  ? '<td class="flex-fill bg-danger-dim text-danger">' . number_format($value->critical) . '</td>' : '<td>' . number_format($value->critical) . '</td>'; ?>
                                        <?php if ($detail) : echo ($value->tests > 0)  ? '<td class="flex-fill bg-info-dim text-info">' . number_format($value->tests) . '</td>' : '<td>' . number_format($value->tests) . '</td>'; ?>
                                            <?php echo ($value->testsPerOneMillion > 0)  ? '<td class="flex-fill bg-blue-dim text-blue">' . number_format($value->testsPerOneMillion) . '</td>' : '<td>' . number_format($value->testsPerOneMillion) . '</td>'; ?>
                                            <?php echo ($value->casesPerOneMillion > 0)  ? '<td class="flex-fill bg-azure-dim text-azure">' . number_format($value->casesPerOneMillion) . '</td>' : '<td>' . number_format($value->casesPerOneMillion) . '</td>'; ?>
                                        <?php echo ($value->deathsPerOneMillion > 0)  ? '<td class="flex-fill bg-danger-dim text-danger">' . number_format($value->deathsPerOneMillion) . '</td>' : '<td>' . number_format($value->deathsPerOneMillion) . '</td>';
                                        endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

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