<?php
$site_url = site_url();

//Random ID
$privacyID = substr(md5(microtime()),rand(0,26),5);
$site_lang = 'en';
$privacy_notice = "de602d3aeaffa369c3d5fe30ce8ff37501940b7499b1900e321779add40543a7";
$checkbox_hash = "7db5ae6d34be3f29828fe45ac7a6c4889eb5bb68cb2e248e1bcf57e2a0f11fdd";
$privacy_optin = "77d096de41ea771b2630b93c5a8c4006adac5d2243f36b7f872a470a6b4567bb";
$privacy_optout = "d5e5b5a689d99c03733b8cbe9763e6f8ccef1a2da763f25f20fd68230ef28bd5";
?>
<div role="region" aria-live="polite">
    <div class="ak-privacy" id="privacy<?php echo $privacyID; ?>">

        <div id="ak-fieldbox-privacy_notice">
            <input type="hidden" id="privacy_notice" name="privacy" value="<?php echo $privacy_notice; ?>">
            <?php
            $privacyUrl = '<a href="https://www.one.org/privacy" rel="external">' .  __('privacy policy', 'oneorg') . '</a>';
            printf(
            esc_html__( 'By signing you agree to ONE’s %s, including to the transfer of your information to ONE’s servers in the United States.', 'oneorg' ),
            $privacyUrl
            );
            ?>
        </div>

        <p class="ak-opt__title"><?php esc_attr_e('Do you want to stay informed about how you can help fight against extreme poverty?', 'oneorg') ?></p>
        <input type="hidden" name="require_opt_in" value="1">

        <div id="ak-fieldbox-privacy_radio" class="ak-opt" >
            <input type="hidden" id="privacy_checkbox" name="privacy" value="<?php echo $checkbox_hash; ?>">
            <?php
            $unsubscribeUrl = '<a href="https://www.one.org/unsubscribe" rel="external">' .  __('unsubscribe', 'oneorg') . '</a>';
            printf(
            esc_html__( 'Sign up to receive emails from ONE and join millions of people around the world taking action to end extreme poverty and preventable disease. We’ll only ever ask for your voice, not your money. You can %s at any time.', 'oneorg' ),
            $unsubscribeUrl
            );
            ?>

            <div role="group" aria-labelledby="privacy_options">
            <div id="privacy_options" class="visually-hidden">Privacy options</div>
            <div class="ak-opt__in ak-err-below">
                <input class="ak-opt__radio" type="radio" id="privacy_optin" name="privacy" value="<?php echo $privacy_optin; ?>" required="">
                <label for="privacy_optin">
                    <?php esc_attr_e('Yes, sign me up', 'oneorg') ?>
                </label>
            </div>

            <div class="ak-opt__out ak-err-below">
                <input class="ak-opt__radio" type="radio" id="privacy_optout" name="privacy" value="<?php echo $privacy_optout; ?>" >
                <label for="privacy_optout">
                    <?php esc_attr_e('No, I\'m already signed up or I don\'t want to be kept informed in future', 'oneorg') ?>
                </label>
            </div>

            <div id="really-opt-out-<?php echo $privacyID; ?>" class="ak-opt__sure hidden">
                <?php
                $sureUrl = '<a href="https://www.one.org/unsubscribe" rel="external">' .  __('unsubscribe', 'oneorg') . '</a>';
                printf(
                esc_html__( 'Are you sure? If you select \'Yes\' we can let you know how you can make a difference. You can %s at any time.', 'oneorg' ),
                $sureUrl
                );
                ?>
            </div><!--/really-opt-out-->
            </div><!--group-->

        </div><!--/ak-radio-wrapper-->
    </div>
</div>
