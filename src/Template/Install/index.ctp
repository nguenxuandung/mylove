<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Installation: Welcome'));

?>


    <div class="install">
        <?php
        $check = true;

        function alert_message($message = '', $type = '')
        {
            return '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';
        }

        // tmp is writable
        if (is_writable(TMP)) {
            //echo alert_message( __( 'Your tmp directory is writable.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your tmp directory is NOT writable.'), 'danger');
        }

        // logs is writable
        if (is_writable(LOGS)) {
            //echo alert_message( __( 'Your logs directory is writable.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your logs directory is NOT writable.'), 'danger');
        }

        // config is writable
        if (is_writable(CONFIG)) {
            //echo alert_message( __( 'Your config directory is writable.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your config directory is NOT writable.'), 'danger');
        }

        // php version
        $minPhpVersion = '5.6.0';
        $operator = '>=';
        if (version_compare(PHP_VERSION, $minPhpVersion, $operator)) {
            //echo alert_message( __( 'PHP version {0} {1} {2}', PHP_VERSION, $operator, $minPhpVersion ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('PHP version {0} < {1}', PHP_VERSION, $minPhpVersion), 'danger');
        }

        if (extension_loaded('mbstring')) {
            //echo alert_message( __( 'Your version of PHP has the mbstring extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the mbstring extension loaded.'), 'danger');
        }

        if (extension_loaded('openssl')) {
            //echo alert_message( __( 'Your version of PHP has the openssl extension loaded.' ), 'success' );
        } elseif (extension_loaded('mcrypt')) {
            //echo alert_message( __( 'Your version of PHP has the mcrypt extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the openssl or mcrypt extension loaded.'),
                'danger');
        }

        if (extension_loaded('intl')) {
            //echo alert_message( __( 'Your version of PHP has the intl extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the intl or mcrypt extension loaded.'), 'danger');
        }

        if (extension_loaded('bcmath')) {
            //echo alert_message( __( 'Your version of PHP has the bcmath extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the bcmath extension loaded.'), 'danger');
        }

        if (extension_loaded('curl')) {
            //echo alert_message( __( 'Your version of PHP has the curl extension loaded.' ), 'success' );
            if (!in_array('https', curl_version()['protocols'])) {
                $check = false;
                echo alert_message(__('Your PHP curl extension should support https protocol.'), 'danger');
            }
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the curl extension loaded.'), 'danger');
        }

        if (extension_loaded('dom')) {
            //echo alert_message( __( 'Your version of PHP has the dom extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the dom extension loaded.'), 'danger');
        }

        if (extension_loaded('mbstring')) {
            //echo alert_message( __( 'Your version of PHP has the mbstring extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the mbstring extension loaded.'), 'danger');
        }

        // pdo_drivers()
        if (extension_loaded('pdo_mysql')) {
            //echo alert_message( __( 'Your version of PHP has the pdo_mysql extension loaded.' ), 'success' );
        } else {
            $check = false;
            echo alert_message(__('Your version of PHP does NOT have the pdo_mysql extension loaded.'), 'danger');
        }

        ?>
    </div>
<?php
if ($check) {
    echo '<div class="alert alert-success" role="alert"><b>' . __('Installation can continue as minimum requirements are met.') . '</b></div>';
    $out = $this->Html->link(__('Install'), ['action' => 'database'], ['class' => 'btn btn-primary']);
} else {
    $out = '<div class="alert alert-danger" role="alert"><b>' . __('Installation cannot continue as minimum requirements are not met.') . '</b></div>';
}

echo $out;
