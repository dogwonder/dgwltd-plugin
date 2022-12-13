<div role="region" aria-live="polite">
<div class="ak-field ak-disclaimer disclaimer-intl">
    <p><?php
    $privacyUrl = '<a href="https://www.one.org/privacy" rel="external">' .  __('privacy policy', 'oneorg') . '</a>';
    printf(
    esc_html__( 'By signing you agree to ONE\'s %s, including to the transfer of your information to ONE.org\'s servers in the United States.', 'oneorg' ),
    $privacyUrl
    );
    ?></p>
    <p><?php
    $unsubscribeUrl = '<a href="https://www.one.org/unsubscribe" rel="external">' .  __('unsubscribe', 'oneorg') . '</a>';
    printf(
    esc_html__( 'You agree to receive occasional updates about ONE’s campaigns. You can %s at any time.', 'oneorg' ),
    $unsubscribeUrl
    );
    ?></p>
</div>

<div class="ak-field ak-field--mobile ak-disclaimer disclaimer-us has-checkbox" style="display:none;">
    <p><?php
    $privacyUrl = '<a href="https://www.one.org/privacy" rel="external">' .  __('privacy policy', 'oneorg') . '</a>';
    $unsubscribeUrl = '<a href="https://www.one.org/unsubscribe" rel="external">' .  __('unsubscribe', 'oneorg') . '</a>';
    printf(
    esc_html__( 'When you submit your details, you accept ONE\'s %1$s and will receive occasional updates about ONE\'s campaigns. You can %2$s at any time.', 'oneorg' ),
    $privacyUrl,
    $unsubscribeUrl
    );
    ?></p>
</div>

<div class="ak-field ak-disclaimer disclaimer-optin has-checkbox" style="display:none;">
    <p><?php
    $privacyUrl = '<a href="https://www.one.org/privacy" rel="external">' .  __('privacy policy', 'oneorg') . '</a>';
    printf(
    esc_html__( 'By signing you agree to ONE\'s %s, including to the transfer of your information to ONE.org\'s servers in the United States.', 'oneorg' ),
    $privacyUrl
    );
    ?></p>
  <input type="hidden" name="opt_in" value="true">
  <div class="ak-checkbox-wrapper">
    <input type="checkbox" id="lists_checkbox" name="lists_checkbox" checked="">
    <label for="lists_checkbox" id="lists_checkbox_label">
    <?php
    $unsubscribeUrl = '<a href="https://www.one.org/unsubscribe" rel="external">' .  __('unsubscribe', 'oneorg') . '</a>';
    printf(
    esc_html__( 'You agree to receive occasional updates about ONE\'s campaigns. You can %s at any time.', 'oneorg' ),
    $unsubscribeUrl
    );
    ?>
    </label>
  </div>

</div>
</div>
