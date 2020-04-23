<?php
$data = get_option('novel_covid19_countries');
?>
<?php
echo do_shortcode('[novel-covid19-adminview]');
?>
<div class="st-main mt30">
    <div class="st-wrap">
        <div class="container-fluid">
            <div class="st-content-body">
                <div class="st-block">
                    <div class="row g-gs">
                        <div class="col-xl-12">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div id="novel-covid19">
                                        <h2 align="center"><?php esc_html_e('Shortcode Documentation', 'novel-covid19'); ?><p><i><?php esc_html_e('Copy & paste this shortcode into post, page or widget.', 'novel-covid19'); ?></i></p>
                                        </h2>
                                        <h4><?php esc_html_e('Attributes', 'novel-covid19'); ?></h4>
                                        <ul class="covid-attributes">
                                            <li><strong><?php esc_html_e('country:', 'novel-covid19'); ?></strong> <?php esc_html_e('Country of box - default is "India"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('title:', 'novel-covid19'); ?></strong> <?php esc_html_e('Title of box - default is "Coronavirus cases worldwide"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_total:', 'novel-covid19'); ?></strong> <?php esc_html_e('Total text default is "Total"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_confirmed:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Confirmed - default is "Confirmed"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_confirmedtoday:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Confirmed Today - default is "Confirmed Today"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_deaths:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Deaths - default is "Deaths"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_deathstoday:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Deaths Today- default is "Deaths Today"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_recovered:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Recovered - default is "Recovered"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_active:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Active - default is "Active"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_critical:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Critical - default is "Critical"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_tests:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Tests - default is "Tests"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_testsPerOneMillion:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Tests Per One Million - default is "Tests Per One Million"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_casesPerOneMillion:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Cases Per One Million - default is "Cases Per One Million"', 'novel-covid19'); ?></li>
                                            <li><strong><?php esc_html_e('label_deathsPerOneMillion:', 'novel-covid19'); ?></strong> <?php esc_html_e('Label Deaths Per One Million - default is "Deaths Per One Million"', 'novel-covid19'); ?></li>

                                        </ul>
                                        <br>
                                        <h4><?php esc_html_e('Shortcode [novel-covid19]', 'novel-covid19'); ?></h4>
                                        <select name="covid_countries">
                                            <option value=""><?php esc_html_e('Select Country', 'novel-covid19'); ?></option>
                                            <?php
                                            foreach ($data as $item) {
                                                echo '<option value="' . $item->country . '">' . $item->country . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <strong><?php esc_html_e('Example:', 'novel-covid19'); ?></strong>
                                        <code id="covid_shortcode"><?php esc_html_e('[novel-covid19]', 'novel-covid19'); ?></code>

                                        <h5><?php esc_html_e('Shortcode [novel-covid19-table]', 'novel-covid19'); ?></h5>
                                        <p><?php esc_html_e('Use this shortcode to display worldwide coronavirus cases into a table.', 'novel-covid19'); ?></p>
                                        <strong><?php esc_html_e('Example:', 'novel-covid19'); ?></strong>
                                        <code>
                                            <?php esc_html_e('[novel-covid19-table]', 'novel-covid19'); ?>
                                        </code>

                                        <h4><?php esc_html_e('Shortcode [novel-covid19-map]', 'novel-covid19'); ?></h4>
                                        <p><?php esc_html_e('Use this shortcode to display Coronavirus map (Worldwide only)', 'novel-covid19'); ?></p>
                                        <strong><?php esc_html_e('Example:', 'novel-covid19'); ?></strong>
                                        <code>
                                            <?php esc_html_e('[novel-covid19-map]', 'novel-covid19'); ?>
                                        </code>

                                        <h4><?php esc_html_e('Shortcode [novel-covid19-newsline]', 'novel-covid19'); ?></h4>
                                        <p><?php esc_html_e('Use this shortcode to display a current updated newsline (Worldwide or Country Based)', 'novel-covid19'); ?></p>
                                        <strong><?php esc_html_e('Example:', 'novel-covid19'); ?></strong>
                                        <code>
                                            <?php esc_html_e('[novel-covid19-newsline]', 'novel-covid19'); ?>
                                        </code>

                                        <h4><?php esc_html_e('Shortcode [novel-covid19-chart]', 'novel-covid19'); ?></h4>
                                        <p><?php esc_html_e('Use this shortcode to display the chart history as per the Country or Worldwide.', 'novel-covid19'); ?></p>
                                        <strong><?php esc_html_e('Example:', 'novel-covid19'); ?></strong>
                                        <code>
                                            <?php esc_html_e('[novel-covid19-chart]', 'novel-covid19'); ?>
                                        </code>
                                    </div>
                                    <div class="credit">
                                        <p>Novel Covid19 WordPress Plugin Made By: <a href="https://www.saicosys.com">Saicosys Technologies</a> | Developers: Sandeep Kadyan, Amit Shokeen</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>