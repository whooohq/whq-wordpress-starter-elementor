<div class="eael-license-wrapper">
    <form method="post" action="options.php" id="eael-license-form">
        <?php settings_fields( $this->page_slug ); ?>
            <?php if ( $status == false || $status !== 'valid' ) : ?>
                <div class="eael-lockscreen">
                <div class="eael-lockscreen-icons">
                    <svg height="64px" version="1.1" viewBox="0 0 32 32" width="64px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#e74c3c" id="icon-114-lock"><path d="M16,21.9146472 L16,24.5089948 C16,24.7801695 16.2319336,25 16.5,25 C16.7761424,25 17,24.7721195 17,24.5089948 L17,21.9146472 C17.5825962,21.708729 18,21.1531095 18,20.5 C18,19.6715728 17.3284272,19 16.5,19 C15.6715728,19 15,19.6715728 15,20.5 C15,21.1531095 15.4174038,21.708729 16,21.9146472 L16,21.9146472 L16,21.9146472 Z M15,22.5001831 L15,24.4983244 C15,25.3276769 15.6657972,26 16.5,26 C17.3284271,26 18,25.3288106 18,24.4983244 L18,22.5001831 C18.6072234,22.04408 19,21.317909 19,20.5 C19,19.1192881 17.8807119,18 16.5,18 C15.1192881,18 14,19.1192881 14,20.5 C14,21.317909 14.3927766,22.04408 15,22.5001831 L15,22.5001831 L15,22.5001831 Z M9,14.0000125 L9,10.499235 C9,6.35670485 12.3578644,3 16.5,3 C20.6337072,3 24,6.35752188 24,10.499235 L24,14.0000125 C25.6591471,14.0047488 27,15.3503174 27,17.0094776 L27,26.9905224 C27,28.6633689 25.6529197,30 23.991212,30 L9.00878799,30 C7.34559019,30 6,28.652611 6,26.9905224 L6,17.0094776 C6,15.339581 7.34233349,14.0047152 9,14.0000125 L9,14.0000125 L9,14.0000125 Z M10,14 L10,10.4934269 C10,6.90817171 12.9101491,4 16.5,4 C20.0825462,4 23,6.90720623 23,10.4934269 L23,14 L22,14 L22,10.5090731 C22,7.46649603 19.5313853,5 16.5,5 C13.4624339,5 11,7.46140289 11,10.5090731 L11,14 L10,14 L10,14 Z M12,14 L12,10.5008537 C12,8.0092478 14.0147186,6 16.5,6 C18.9802243,6 21,8.01510082 21,10.5008537 L21,14 L12,14 L12,14 L12,14 Z M8.99742191,15 C7.89427625,15 7,15.8970601 7,17.0058587 L7,26.9941413 C7,28.1019465 7.89092539,29 8.99742191,29 L24.0025781,29 C25.1057238,29 26,28.1029399 26,26.9941413 L26,17.0058587 C26,15.8980535 25.1090746,15 24.0025781,15 L8.99742191,15 L8.99742191,15 Z" id="lock"/></g></g></svg>

                    <svg enable-background="new 0 0 32 32" height="64px" id="arrow-right" version="1.1" viewBox="0 0 32 32" width="64px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M1.06,29.897c0.011,0,0.023,0,0.034-0.001c0.506-0.017,0.825-0.409,0.868-0.913  c0.034-0.371,1.03-9.347,15.039-9.337l0.031,5.739c0,0.387,0.223,0.739,0.573,0.904c0.347,0.166,0.764,0.115,1.061-0.132  l12.968-10.743c0.232-0.19,0.366-0.475,0.365-0.774c-0.001-0.3-0.136-0.584-0.368-0.773L18.664,3.224  c-0.299-0.244-0.712-0.291-1.06-0.128c-0.349,0.166-0.571,0.518-0.571,0.903l-0.031,5.613c-5.812,0.185-10.312,2.054-13.23,5.468  c-4.748,5.556-3.688,13.63-3.639,13.966C0.207,29.536,0.566,29.897,1.06,29.897z M18.032,17.63c-0.001,0-0.002,0-0.002,0  C8.023,17.636,4.199,21.015,2.016,23.999c0.319-2.391,1.252-5.272,3.281-7.626c2.698-3.128,7.045-4.776,12.735-4.776  c0.553,0,1-0.447,1-1V6.104l10.389,8.542l-10.389,8.622V18.63c0-0.266-0.105-0.521-0.294-0.708  C18.551,17.735,18.297,17.63,18.032,17.63z" fill="#888" id="Arrow_Right_2_"/><g/><g/><g/><g/><g/><g/></svg>

                    <svg height="64px" version="1.1" viewBox="0 0 32 32" width="64px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#157EFB" id="icon-24-key"><path d="M18.5324038,19.4675962 L14,24 L11,24 L11,27 L8,27 L8,30 L3,30 L3,25 L13.5324038,14.4675962 C13.1881566,13.5437212 13,12.5438338 13,11.5 C13,6.80557939 16.8055794,3 21.5,3 C26.1944206,3 30,6.80557939 30,11.5 C30,16.1944206 26.1944206,20 21.5,20 C20.4561662,20 19.4562788,19.8118434 18.5324038,19.4675962 L18.5324038,19.4675962 L18.5324038,19.4675962 Z M13.9987625,15.5012375 L4,25.5 L4,29 L7,29 L7,26 L10,26 L10,23 L13.5,23 L17.4987625,19.0012375 C16.0139957,18.2075914 14.7924086,16.9860043 13.9987625,15.5012375 L13.9987625,15.5012375 L13.9987625,15.5012375 Z M29,11.5 C29,7.35786417 25.6421358,4 21.5,4 C17.3578642,4 14,7.35786417 14,11.5 C14,15.6421358 17.3578642,19 21.5,19 C25.6421358,19 29,15.6421358 29,11.5 L29,11.5 L29,11.5 Z M27,9 C27,7.34314567 25.6568543,6 24,6 C22.3431457,6 21,7.34314567 21,9 C21,10.6568543 22.3431457,12 24,12 C25.6568543,12 27,10.6568543 27,9 L27,9 L27,9 Z M26,9 C26,7.89543045 25.1045696,7 24,7 C22.8954304,7 22,7.89543045 22,9 C22,10.1045696 22.8954304,11 24,11 C25.1045696,11 26,10.1045696 26,9 L26,9 L26,9 Z" id="key"/></g></g></svg>

                    <svg enable-background="new 0 0 32 32" height="64px" id="arrow-right" version="1.1" viewBox="0 0 32 32" width="64px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M1.06,29.897c0.011,0,0.023,0,0.034-0.001c0.506-0.017,0.825-0.409,0.868-0.913  c0.034-0.371,1.03-9.347,15.039-9.337l0.031,5.739c0,0.387,0.223,0.739,0.573,0.904c0.347,0.166,0.764,0.115,1.061-0.132  l12.968-10.743c0.232-0.19,0.366-0.475,0.365-0.774c-0.001-0.3-0.136-0.584-0.368-0.773L18.664,3.224  c-0.299-0.244-0.712-0.291-1.06-0.128c-0.349,0.166-0.571,0.518-0.571,0.903l-0.031,5.613c-5.812,0.185-10.312,2.054-13.23,5.468  c-4.748,5.556-3.688,13.63-3.639,13.966C0.207,29.536,0.566,29.897,1.06,29.897z M18.032,17.63c-0.001,0-0.002,0-0.002,0  C8.023,17.636,4.199,21.015,2.016,23.999c0.319-2.391,1.252-5.272,3.281-7.626c2.698-3.128,7.045-4.776,12.735-4.776  c0.553,0,1-0.447,1-1V6.104l10.389,8.542l-10.389,8.622V18.63c0-0.266-0.105-0.521-0.294-0.708  C18.551,17.735,18.297,17.63,18.032,17.63z" fill="#888" id="Arrow_Right_2_"/><g/><g/><g/><g/><g/><g/></svg>

                    <svg height="64px" version="1.1" viewBox="0 0 32 32" width="64px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#2ecc71" id="icon-116-lock-open"><path d="M24,9.5 L24,8.499235 C24,4.35752188 20.6337072,1 16.5,1 C12.3578644,1 9,4.35670485 9,8.499235 L9,16.0000125 L9,16.0000125 C7.34233349,16.0047152 6,17.339581 6,19.0094776 L6,28.9905224 C6,30.652611 7.34559019,32 9.00878799,32 L23.991212,32 C25.6529197,32 27,30.6633689 27,28.9905224 L27,19.0094776 C27,17.3503174 25.6591471,16.0047488 24,16 L23.4863586,16 L12.0274777,16 C12.0093222,15.8360041 12,15.6693524 12,15.5005291 L12,8.49947095 C12,6.01021019 14.0147186,4 16.5,4 C18.9802243,4 21,6.01448176 21,8.49947095 L21,9.5 L21,12.4998351 C21,13.3283533 21.6657972,14 22.5,14 C23.3284271,14 24,13.3256778 24,12.4998351 L24,9.5 L24,9.5 L24,9.5 Z M23,8.49342686 C23,4.90720623 20.0825462,2 16.5,2 C12.9101491,2 10,4.90817171 10,8.49342686 L10,15.5065731 C10,15.6725774 10.0062513,15.8371266 10.0185304,16 L11,16 L11,8.50907306 C11,5.46140289 13.4624339,3 16.5,3 C19.5313853,3 22,5.46649603 22,8.50907306 L22,12.5022333 C22,12.7771423 22.2319336,13 22.5,13 L22.5,13 C22.7761424,13 23,12.7849426 23,12.5095215 L23,9 L23,8.49342686 L23,8.49342686 Z M16,23.9146472 L16,26.5089948 C16,26.7801695 16.2319336,27 16.5,27 C16.7761424,27 17,26.7721195 17,26.5089948 L17,23.9146472 C17.5825962,23.708729 18,23.1531095 18,22.5 C18,21.6715728 17.3284272,21 16.5,21 C15.6715728,21 15,21.6715728 15,22.5 C15,23.1531095 15.4174038,23.708729 16,23.9146472 L16,23.9146472 L16,23.9146472 Z M15,24.5001831 L15,26.4983244 C15,27.3276769 15.6657972,28 16.5,28 C17.3284271,28 18,27.3288106 18,26.4983244 L18,24.5001831 C18.6072234,24.04408 19,23.317909 19,22.5 C19,21.1192881 17.8807119,20 16.5,20 C15.1192881,20 14,21.1192881 14,22.5 C14,23.317909 14.3927766,24.04408 15,24.5001831 L15,24.5001831 L15,24.5001831 Z M8.99742191,17 C7.89427625,17 7,17.8970601 7,19.0058587 L7,28.9941413 C7,30.1019465 7.89092539,31 8.99742191,31 L24.0025781,31 C25.1057238,31 26,30.1029399 26,28.9941413 L26,19.0058587 C26,17.8980535 25.1090746,17 24.0025781,17 L8.99742191,17 L8.99742191,17 Z" id="-ock-open"/></g></g></svg>
                </div>
                <h1 class="eael-validation-title">Just one more step to go!</h1>
            </div>
            <div class="eael-license-instruction">
                <p><?php _e( 'Enter your license key here, to activate <strong>Essential Addons for Elementor</strong>, and get automatic updates and premium support.', $this->text_domain ); ?></p>
                <p><?php printf( __( 'Visit the <a href="%s" target="_blank">Validation Guide</a> for help.', $this->text_domain ), 'https://essential-addons.com/elementor/docs/getting-started/validating-license/' ); ?></p>

                <ol>
                    <li><?php printf( __( 'Log in to <a href="%s" target="_blank">your account</a> to get your license key.', $this->text_domain ), 'https://wpdeveloper.net/account/' ); ?></li>
                    <li><?php printf( __( 'If you don\'t yet have a license key, get <a href="%s" target="_blank">Essential Addons for Elementor now</a>.', $this->text_domain ), 'https://wpdeveloper.net/in/upgrade-essential-addons-elementor' ); ?></li>
                    <li><?php _e( __( 'Copy the license key from your account and paste it below.', $this->text_domain ) ); ?></li>
                    <li><?php _e( __( 'Click on <strong>"Activate License"</strong> button.', $this->text_domain ) ); ?></li>
                </ol>
            </div>
            <?php endif; ?>

            <?php if( $status !== false && $status == 'valid' ) { ?>
            <div class="validated-feature-list">
                <div class="validated-feature-list-item">
                    <div class="validated-feature-list-icon">
                        <img src="<?php echo EAEL_PRO_PLUGIN_URL . 'assets/admin/images/icon-auto-update.svg'; ?>" alt="essential-addons-auto-update">
                    </div>
                    <div class="validated-feature-list-content">
                        <h4>Auto Update</h4>
                        <p>Update the plugin right from your WordPress Dashboard.</p>
                    </div>
                </div><!--./feature-list-item-->
                <div class="validated-feature-list-item">
                    <div class="validated-feature-list-icon">
                        <img src="<?php echo EAEL_PRO_PLUGIN_URL . 'assets/admin/images/icon-auto-update.svg'; ?>" alt="essential-addons-auto-update">
                    </div>
                    <div class="validated-feature-list-content">
                        <h4><?php _e('Premium Support', $this->text_domain); ?></h4>
                        <p><?php _e('Supported by professional and courteous staff.', $this->text_domain); ?></p>
                    </div>
                </div><!--./feature-list-item-->
            </div><!--./feature-list-->
            <?php } ?>

                <div class="eael-license-container">
                <div class="eael-license-icon">
                    <?php if( $status == false && $status !== 'valid' ) { ?>
                        <svg height="32px" version="1.1" viewBox="0 0 32 32" width="32px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#EE3560" id="icon-114-lock"><path d="M16,21.9146472 L16,24.5089948 C16,24.7801695 16.2319336,25 16.5,25 C16.7761424,25 17,24.7721195 17,24.5089948 L17,21.9146472 C17.5825962,21.708729 18,21.1531095 18,20.5 C18,19.6715728 17.3284272,19 16.5,19 C15.6715728,19 15,19.6715728 15,20.5 C15,21.1531095 15.4174038,21.708729 16,21.9146472 L16,21.9146472 Z M9,14.0000125 L9,10.499235 C9,6.35670485 12.3578644,3 16.5,3 C20.6337072,3 24,6.35752188 24,10.499235 L24,14.0000125 C25.6591471,14.0047488 27,15.3503174 27,17.0094776 L27,26.9905224 C27,28.6633689 25.6529197,30 23.991212,30 L9.00878799,30 C7.34559019,30 6,28.652611 6,26.9905224 L6,17.0094776 C6,15.339581 7.34233349,14.0047152 9,14.0000125 L9,14.0000125 L9,14.0000125 Z M12,14 L12,10.5008537 C12,8.0092478 14.0147186,6 16.5,6 C18.9802243,6 21,8.01510082 21,10.5008537 L21,14 L12,14 L12,14 L12,14 Z" id="lock"/></g></g></svg>
                    <?php } ?>
                    <?php if( $status !== false && $status == 'valid' ) { ?>
                        <img src="<?php echo EAEL_PRO_PLUGIN_URL . 'assets/admin/images/icon-license-valid.svg'; ?>" alt="essential-addons-elementor-licnese">
                    <?php } ?>

                </div>
                <div class="eael-license-input">
                    <input <?php echo ( $status !== false && $status == 'valid' ) ? 'disabled' : ''; ?> id="<?php echo $this->product_slug; ?>-license-key" name="<?php echo $this->product_slug; ?>-license-key" type="text" class="regular-text" value="<?php echo esc_attr( self::get_hidden_license_key() ); ?>"" placeholder="Place Your License Key and Activate" />
                </div>

                <div class="eael-license-buttons">
                    <?php wp_nonce_field( $this->product_slug . '_license_nonce', $this->product_slug . '_license_nonce' ); ?>

                    <?php if( $status !== false && $status == 'valid' ) { ?>
                        <input type="hidden" name="action" value="eae_pro_deactivate_license"/>
                        <input type="hidden" name="<?php echo $this->product_slug; ?>_license_deactivate" />
                        <?php submit_button( __( 'Deactivate License', $this->text_domain ), 'eael-license-deactivation-btn', 'submit', false, array( 'class' => 'button button-primary' ) ); ?>
                    <?php } else { ?>
                        <input type="hidden" name="<?php echo $this->product_slug; ?>_license_activate" />
                        <?php submit_button( __( 'Activate License', $this->text_domain ), 'eael-license-activation-btn', 'submit', false, array( 'class' => 'button button-primary' ) ); ?>
                    <?php } ?>
                </div>
            </div>
    </form>
</div>