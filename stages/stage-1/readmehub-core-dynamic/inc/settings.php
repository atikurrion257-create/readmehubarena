<?php
/**
 * Global plugin settings & one-click /wp-admin/ maintenance utilities.
 * Replaces ACF Pro Options Pages cleanly within ACF Free constraints.
 *
 * @package ReadMeHubCoreDynamic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Settings -> ReadMeHub Core admin screen and settings group.
 */
function rmh_register_admin_menu(): void {
	add_options_page(
		__( 'ReadMeHub Core Settings', 'readmehub-core' ),
		__( 'ReadMeHub Core', 'readmehub-core' ),
		'manage_options',
		'readmehub-core-settings',
		'rmh_render_settings_page'
	);
}
add_action( 'admin_menu', 'rmh_register_admin_menu' );

/**
 * Register sanitized option array.
 */
function rmh_register_settings_group(): void {
	register_setting(
		'rmh_core_settings_group',
		'rmh_core_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'rmh_sanitize_core_settings',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'rmh_register_settings_group' );

/**
 * Sanitize global plugin settings.
 *
 * @param mixed $input Raw submitted form array.
 * @return array<string, string>
 */
function rmh_sanitize_core_settings( $input ): array {
	if ( ! is_array( $input ) ) {
		return array();
	}

	$schema_mode = sanitize_key( (string) ( $input['schema_ownership_mode'] ?? 'extend_rank_math' ) );
	if ( ! in_array( $schema_mode, array( 'extend_rank_math', 'standalone_fallback' ), true ) ) {
		$schema_mode = 'extend_rank_math';
	}

	return array(
		'default_disclosure'    => sanitize_textarea_field( (string) ( $input['default_disclosure'] ?? '' ) ),
		'disclosure_page_url'   => sanitize_text_field( (string) ( $input['disclosure_page_url'] ?? '/affiliate-disclosure/' ) ),
		'methodology_page_url'  => sanitize_text_field( (string) ( $input['methodology_page_url'] ?? '/editorial-methodology/' ) ),
		'default_author_name'   => sanitize_text_field( (string) ( $input['default_author_name'] ?? '' ) ),
		'default_author_role'   => sanitize_text_field( (string) ( $input['default_author_role'] ?? 'Software & Hosting Research Desk' ) ),
		'newsletter_action_url' => esc_url_raw( trim( (string) ( $input['newsletter_action_url'] ?? '' ) ) ),
		'newsletter_heading'    => sanitize_text_field( (string) ( $input['newsletter_heading'] ?? '' ) ),
		'newsletter_subcopy'    => sanitize_textarea_field( (string) ( $input['newsletter_subcopy'] ?? '' ) ),
		'schema_ownership_mode' => $schema_mode,
		'retain_faq_schema'     => ! empty( $input['retain_faq_schema'] ) ? '1' : '0',
	);
}

/**
 * Handle nonce-protected one-click maintenance actions in /wp-admin/:
 * 1) Seed/sync the 17 audit-corrected rmh_tool records & silo/cluster terms.
 * 2) Fix Site Title ("My Blog" -> "ReadMeHub") & flush permalinks.
 * 3) Run the built-in Systeme.io www-stripping unit test suite.
 */
function rmh_handle_admin_tools_actions(): void {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( empty( $_POST['rmh_admin_action'] ) ) {
		return;
	}

	check_admin_referer( 'rmh_admin_tools_nonce', 'rmh_nonce' );

	$action = sanitize_key( wp_unslash( $_POST['rmh_admin_action'] ) );

	if ( 'seed_tools' === $action ) {
		rmh_seed_default_taxonomy_terms();
		$summary = rmh_seed_all_tool_records( false );
		add_settings_error(
			'rmh_core_messages',
			'rmh_seeded',
			sprintf(
				/* translators: 1: created count, 2: updated count, 3: skipped count */
				__( 'rmh_tool seed complete: %1$d created, %2$d updated, %3$d preserved (existing owner edits kept).', 'readmehub-core' ),
				$summary['created'],
				$summary['updated'],
				$summary['skipped']
			),
			'updated'
		);
	} elseif ( 'fix_site_identity' === $action ) {
		update_option( 'blogname', 'ReadMeHub' );
		add_settings_error(
			'rmh_core_messages',
			'rmh_title_fixed',
			__( 'Site Title set to "ReadMeHub"; the tagline was not changed.', 'readmehub-core' ),
			'updated'
		);
	} elseif ( 'run_url_tests' === $action ) {
		$results = rmh_run_affiliate_url_self_tests();
		add_settings_error(
			'rmh_core_messages',
			'rmh_url_tests',
			$results['all_passed']
				? sprintf( __( 'PASS: All %d Systeme.io www-stripping & affiliate gate unit assertions succeeded.', 'readmehub-core' ), count( $results['assertions'] ) )
				: __( 'FAIL: One or more affiliate URL sanitizer assertions failed.', 'readmehub-core' ),
			$results['all_passed'] ? 'updated' : 'error'
		);
	}
}
add_action( 'admin_init', 'rmh_handle_admin_tools_actions' );

/**
 * Render Settings -> ReadMeHub Core admin screen.
 */
function rmh_render_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	settings_errors( 'rmh_core_messages' );
	$blogname = (string) get_option( 'blogname', '' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'ReadMeHub Core Dynamic — Global Settings & Registry Tools', 'readmehub-core' ); ?></h1>
		<p>
			<strong><?php echo esc_html__( 'Baseline Re-Verification Window:', 'readmehub-core' ); ?></strong>
			<?php
			echo esc_html(
				sprintf(
					'Baseline %s expires %s (%d-day freshness window).',
					RMH_BASELINE_VERIFIED_DATE,
					RMH_BASELINE_EXPIRY_DATE,
					RMH_FRESHNESS_MAX_DAYS
				)
			);
			?>
		</p>

		<hr />

		<h2><?php echo esc_html__( 'One-Click Setup & Safe Registry Actions', 'readmehub-core' ); ?></h2>
		<p><?php echo esc_html__( 'Seeding creates draft records and taxonomy terms. Setting the site title changes a live global setting and requires explicit approval.', 'readmehub-core' ); ?></p>

		<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px;">
			<form method="post">
				<?php wp_nonce_field( 'rmh_admin_tools_nonce', 'rmh_nonce' ); ?>
				<input type="hidden" name="rmh_admin_action" value="fix_site_identity" />
				<button type="submit" class="button button-secondary">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: current WordPress site title */
							__( 'Set Site Title to "ReadMeHub" (Current: "%s")', 'readmehub-core' ),
							$blogname
						)
					);
					?>
				</button>
			</form>

			<form method="post">
				<?php wp_nonce_field( 'rmh_admin_tools_nonce', 'rmh_nonce' ); ?>
				<input type="hidden" name="rmh_admin_action" value="seed_tools" />
				<button type="submit" class="button button-primary">
					<?php echo esc_html__( 'Seed Silo Taxonomies & 17 Audit-Corrected rmh_tool Records', 'readmehub-core' ); ?>
				</button>
			</form>

			<form method="post">
				<?php wp_nonce_field( 'rmh_admin_tools_nonce', 'rmh_nonce' ); ?>
				<input type="hidden" name="rmh_admin_action" value="run_url_tests" />
				<button type="submit" class="button button-secondary">
					<?php echo esc_html__( 'Run Systeme.io No-WWW & Affiliate Gate Self-Tests', 'readmehub-core' ); ?>
				</button>
			</form>
		</div>

		<hr />

		<form method="post" action="options.php">
			<?php settings_fields( 'rmh_core_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="rmh_default_disclosure"><?php echo esc_html__( 'Default FTC Disclosure Text', 'readmehub-core' ); ?></label></th>
					<td>
						<textarea id="rmh_default_disclosure" name="rmh_core_settings[default_disclosure]" rows="4" class="large-text"><?php echo esc_textarea( (string) rmh_get_setting( 'default_disclosure' ) ); ?></textarea>
						<p class="description"><?php echo esc_html__( 'Rendered by [rmh_disclosure] above the first affiliate CTA unless overridden per page via rmh_disclosure_override.', 'readmehub-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_disclosure_page_url"><?php echo esc_html__( 'Affiliate Disclosure URL', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="text" id="rmh_disclosure_page_url" name="rmh_core_settings[disclosure_page_url]" value="<?php echo esc_attr( (string) rmh_get_setting( 'disclosure_page_url' ) ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_methodology_page_url"><?php echo esc_html__( 'Editorial Methodology URL', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="text" id="rmh_methodology_page_url" name="rmh_core_settings[methodology_page_url]" value="<?php echo esc_attr( (string) rmh_get_setting( 'methodology_page_url' ) ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_default_author_name"><?php echo esc_html__( 'Default Author Name (Fallback)', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="text" id="rmh_default_author_name" name="rmh_core_settings[default_author_name]" value="<?php echo esc_attr( (string) rmh_get_setting( 'default_author_name' ) ); ?>" class="regular-text" />
						<p class="description"><?php echo esc_html__( 'Replace with your real human name once confirmed; never enter invented personas.', 'readmehub-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_default_author_role"><?php echo esc_html__( 'Default Author Role', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="text" id="rmh_default_author_role" name="rmh_core_settings[default_author_role]" value="<?php echo esc_attr( (string) rmh_get_setting( 'default_author_role' ) ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_newsletter_action_url"><?php echo esc_html__( 'Newsletter Form Action Endpoint URL', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="url" id="rmh_newsletter_action_url" name="rmh_core_settings[newsletter_action_url]" value="<?php echo esc_attr( (string) rmh_get_setting( 'newsletter_action_url' ) ); ?>" class="large-text" placeholder="https://..." />
						<p class="description"><?php echo esc_html__( 'Leave blank until your newsletter provider (e.g., Systeme.io, MailerLite, Kit, Brevo) is confirmed; [rmh_newsletter] renders an honest pending state until set.', 'readmehub-core' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_newsletter_heading"><?php echo esc_html__( 'Newsletter Heading', 'readmehub-core' ); ?></label></th>
					<td>
						<input type="text" id="rmh_newsletter_heading" name="rmh_core_settings[newsletter_heading]" value="<?php echo esc_attr( (string) rmh_get_setting( 'newsletter_heading' ) ); ?>" class="large-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_newsletter_subcopy"><?php echo esc_html__( 'Newsletter Subcopy', 'readmehub-core' ); ?></label></th>
					<td>
						<textarea id="rmh_newsletter_subcopy" name="rmh_core_settings[newsletter_subcopy]" rows="2" class="large-text"><?php echo esc_textarea( (string) rmh_get_setting( 'newsletter_subcopy' ) ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rmh_schema_ownership_mode"><?php echo esc_html__( 'JSON-LD @graph Ownership Mode', 'readmehub-core' ); ?></label></th>
					<td>
						<select id="rmh_schema_ownership_mode" name="rmh_core_settings[schema_ownership_mode]">
							<option value="extend_rank_math" <?php selected( (string) rmh_get_setting( 'schema_ownership_mode' ), 'extend_rank_math' ); ?>>
								<?php echo esc_html__( 'Preferred: use Rank Math graph writer with ReadMeHub nodes', 'readmehub-core' ); ?>
							</option>
							<option value="standalone_fallback" <?php selected( (string) rmh_get_setting( 'schema_ownership_mode' ), 'standalone_fallback' ); ?>>
								<?php echo esc_html__( 'Fallback: suppress Rank Math graph and output ReadMeHub @graph on wp_head', 'readmehub-core' ); ?>
							</option>
						</select>
					</td>
				</tr>
				<tr><th scope="row"><?php echo esc_html__( 'FAQ schema', 'readmehub-core' ); ?></th><td><?php echo esc_html__( 'Omitted; the visible FAQ remains available to readers.', 'readmehub-core' ); ?></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
