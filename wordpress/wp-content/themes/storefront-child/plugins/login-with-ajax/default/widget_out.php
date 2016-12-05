<?php 
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
        <?php if ($lwa_data['registration'] == 0) : ?>
            <div class="container-fluild">
                <form class="lwa-form" class="form-horizontal" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
                    <div class="login-form row">
                        <span class="lwa-status"></span>
                        <div class="col-md-5">
                             <div class="form-group">
                                <label class="col-sm-4 control-label">E-mail : </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="log" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Senha : </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" name="pwd" class="form-control">
                                </div>
                                <div class="clearfix"></div>
                                <a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found','login-with-ajax') ?>">Esqueci minha senha ...</a>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" name="wp-submit" id="lwa_wp-submit" value="<?php esc_attr_e('Log In', 'login-with-ajax'); ?>" tabindex="100" />
                            <input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
                            <input type="hidden" name="login-with-ajax" value="login" />
                            <?php if( !empty($lwa_data['redirect']) ): ?>
                            <input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($lwa_data['registration'] == 1) : ?>
            <div class="container-fluild">
                <form class="lwa-register-form" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>"  class="form-horizontal" method="post">
                    <div class="login-form row">
                        <span class="lwa-status"></span>
                        <div class="col-md-5">
                             <div class="form-group">
                                <label class="col-sm-6 control-label">Nome de usuário : </label>
                                <div class="col-sm-6">
                                  <input type="text" name="user_login" id="user_login" class="input form-control" size="25" tabindex="20" /></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                             <div class="form-group">
                                <label class="col-sm-4 control-label">E-mail : </label>
                                <div class="col-sm-8">
                                    <input type="text" name="user_email" id="user_email" class="input form-control" size="25" tabindex="20" /></label>
                                    <input type="hidden" name="login-with-ajax" value="register" />
                                </div>
                            </div>

                        </div>

                        <div class="col-md-2">
                        <input type="submit" name="wp-submit" id="lwa_wp-submit" class="button-primary" value="<?php esc_attr_e('Register', 'login-with-ajax'); ?>" tabindex="100" />
                        <input type="hidden" name="login-with-ajax" value="register" />
                        <?php do_action('register_form'); ?>
                        <?php do_action('lwa_register_form'); ?>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
