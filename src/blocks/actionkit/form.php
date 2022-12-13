<?php 
//Vars
$source = 'one.org';
//Defaults
if(empty($akID) || empty($actionkit_page_redirect)) {
    // use the global option for join actiom here
    $site_url = site_url();
    $akID = 'joinone_int';
    $actionkit_page_redirect = $site_url . '/take-action/thanks-for-joining-one/';
}
?>

<form id="ak-form" class="ak-form" name="act" method="POST" action="//act.one.org/act/" accept-charset="utf-8"
    novalidate>

    <input name="utf8" type="hidden" value="✓">
    <input type="hidden" name="source" value="<?php echo esc_attr($source)?>">
    <input type="hidden" id="redirect" name="redirect" value="<?php echo esc_attr($actionkit_page_redirect)?>">
    <input type="hidden" name="page" value="<?php echo esc_attr($akID)?>">
    <input type="hidden" name="required" value="action_personcheck" />
    <input type="hidden" name="error_action_personcheck:missing"
        value="Sorry, you need to have javascript enabled in your browser to complete this action." />

    <input type="hidden" name="action_used_form" value="1" />
    <input type="hidden" name="action_utm_source" value="">
    <input type="hidden" name="action_utm_campaign" value="">
    <input type="hidden" name="action_utm_medium" value="">
    <input type="hidden" name="action_utm_term" value="">
    <input type="hidden" name="action_utm_content" value="">
    <input type="hidden" value="<?php echo esc_attr($postID)?>" name="action_wp_id">

    <ul class="compact" id="ak-errors"></ul>

    <div id="known_user">
        Not <span id="known_user_name"></span>? <a href="?" onclick="return actionkit.forms.logOut()">Click here.</a>
        <hr>
    </div>

    <div class="petition-form ak-labels-overlaid">

        <div class="ak-fieldset">

            <div class="ak-field">
                <label for="id_name" class="ak-userfield-label"><?php _e('Name', 'oneorg') ?></label>
                <input type="text" name="name" id="id_name" class="ak-userfield-input ak-input-name"
                    autocomplete="name">
            </div>

            <div class="ak-field">
                <label for="id_email" class="ak-userfield-label"><?php _e('Email', 'oneorg') ?></label>
                <input type="email" name="email" id="id_email" class="ak-userfield-input ak-input-email"
                    autocomplete="home email">
            </div>

            <div id="ak-fieldbox-postal" class="ak-field">
                <label for="id_postal" class="ak-userfield-label"><?php _e('Post/Zip code', 'oneorg') ?></label>
                <input type="text" name="postal" id="id_postal" class="ak-userfield-input ak-input-zip"
                    autocomplete="shipping postal-code">
            </div>

            <div id="ak-fieldbox-zip" class="ak-field">
                <label for="id_zip" class="ak-userfield-label"><?php _e('Zip code', 'oneorg') ?></label>
                <input type="text" name="zip" id="id_zip" class="ak-userfield-input ak-input-zip"
                    autocomplete="shipping postal-code">
            </div>

            <div id="ak-fieldbox-phone" class="ak-field">
                <label for="id_phone" class="ak-userfield-label"><?php _e('Phone', 'oneorg') ?></label>
                <input type="text" name="phone" id="id_phone" class="ak-userfield-input ak-input-phone">
            </div>


            <div id="ak-fieldbox-country" class="ak-field">
                <label for="country" class="ak-userfield-label"><?php _e('Country', 'oneorg') ?></label>
                <select name="country" id="country" class="ak-userfield-input ak-input-country">
                <?php require_once plugin_dir_path( __FILE__ ) . 'country-list-en.php'; ?>
                </select>
            </div>

        </div>

        <?php require_once plugin_dir_path( __FILE__ ) . 'privacy.php'; ?>

        <button role="button" type="submit" class="ak-userfield-submit" id="akSubmitButton"><?php _e('Sign', 'oneorg') ?></button>
        

    </div>
    <!--petition-form-->

    <?php require_once plugin_dir_path( __FILE__ ) . 'disclaimer.php'; ?>

</form>