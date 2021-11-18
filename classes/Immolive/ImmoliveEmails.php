<?php


namespace reb_livestream_classes\Immolive;

use Carbon\Carbon;
use reb_livestream_classes\CampaignMonitor;

trait ImmoliveEmails {


	public function send_subscription_email( $name, $email, $immolive_id ) {

		$ical_url = get_field( 'field_6143982f5f5f2', $immolive_id );
		$ical     = file_get_contents( $ical_url );

		$teilnehmer = get_field( 'field_614ad5e239622', $immolive_id );

		ob_start();
		?>
        <ul>
			<?php foreach ( $teilnehmer as $item ): ?>
                <li>
					<?php echo get_field( 'field_613b8ca49b06b', $item ) . ' ' . get_field( 'field_613c53f33d6b8', $item ) ?>,&nbsp;
					<?php echo get_field( 'field_613c54063d6b9', $item ) ?>
                </li>
			<?php endforeach; ?>
        </ul>

		<?php
		$list = ob_get_clean();


		$result = wp_remote_post( sprintf( 'https://api.createsend.com/api/v3.2/transactional/smartEmail/%s/send', 'f681cc3f-299d-447c-8444-4b7fbec46082' ), [
			'headers' => CampaignMonitor::get_authorization_header(),
			'body'    => json_encode( [
				'To'                  => $email,
				"Data"                => [
					'title' => get_the_title( $immolive_id ),
					'link'  => home_url( 'live' ),
					'name'  => $name,
					'list'  => $list,
				],
				"AddRecipientsToList" => true,
				"ConsentToTrack"      => "Yes",
				'Attachments'         => [
					[
						"Type"    => "text/calendar",
						"Name"    => $_POST['post_id'] . '.ics',
						"Content" => base64_encode( $ical ),
					],
				],
			] ),
		] );

		$cm = new CampaignMonitor();

		return $cm->isSuccess( $result );

	}


	public function create_immolive_list( $immolive_id ) {


		if ( get_post_meta( $immolive_id, 'cm_list' ) ) {
			return;
		}

		$termin = get_field( 'field_5ed527e9c2279', $immolive_id );
		$termin = new Carbon( $termin );

		if ( $termin->isPast() ) {
			return;
		}

		$client_id = get_field( 'field_61938af4c1fcf', 'option' );
		$url       = sprintf( 'https://api.createsend.com/api/v3.2/lists/%s.json', $client_id );


		$result = wp_remote_post( $url, [
			'headers' => CampaignMonitor::get_authorization_header(),
			'body'    => json_encode( [
				"Title"              => get_the_title( $immolive_id ) . ' ' . get_field( 'field_5ed527e9c2279', $immolive_id ),
				"UnsubscribeSetting" => "OnlyThisList",
				"ConfirmedOptIn"     => false,
			] ),
		] );

		$cm = new CampaignMonitor();

		if ( $cm->isSuccess( $result ) ) {
			$body = wp_remote_retrieve_body( $result );
			update_post_meta( $immolive_id, 'cm_list', trim( $body, '"' ) );
			$this->add_subscriber_to_list( $immolive_id, get_user_by( 'email', 'gerhard@poppgerhard.at' ) );
		}
	}


	public function add_subscriber_to_list( $immolive_id, $user ) {


		$list_id = trim( get_post_meta( $immolive_id, 'cm_list', true ), '"' );

		wp_remote_post( sprintf( 'https://api.createsend.com/api/v3.2/subscribers/%s.json', $list_id ), [
			'headers' => CampaignMonitor::get_authorization_header(),
			'body'    => json_encode( [
				"EmailAddress"                           => $user->user_email,
				"Name"                                   => $user->display_name,
				"Resubscribe"                            => true,
				"RestartSubscriptionBasedAutoresponders" => true,
				"ConsentToTrack"                         => "Yes",
			] ),
		] );

	}


	public function create_reminder_campaign( $immolive_id, $immolive ) {

		if ( get_post_status( $immolive_id ) != 'publish' || ! has_post_thumbnail( $immolive_id ) ) {
			return;
		}

		$termin = get_field( 'field_5ed527e9c2279', $immolive_id );

		$termin = new Carbon( $termin );
		if ( $termin->isPast() ) {
			return;
		}

		$list_id = trim( get_post_meta( $immolive_id, 'cm_list', true ), '"' );

		if ( empty( $list_id ) ) {
			return;
		}


//		$templates = wp_remote_get( sprintf( 'https://api.createsend.com/api/v3.2/clients/%s/templates.json', get_field( 'field_61938af4c1fcf', 'option' ) ), [
//			'headers' => CampaignMonitor::get_authorization_header(),
//		] );
//
//        $body = wp_remote_retrieve_body($templates);
//
//        wp_die(var_dump($body));

		$teilnehmer = get_field( 'field_614ad5e239622', $immolive_id );

		ob_start();
		?>
        <h3>Unsere Expert*innen im Livestream:</h3>
        <ul>
			<?php foreach ( $teilnehmer as $item ): ?>
                <li>
					<?php echo get_field( 'field_613b8ca49b06b', $item ) . ' ' . get_field( 'field_613c53f33d6b8', $item ) ?>,&nbsp;
					<?php echo get_field( 'field_613c54063d6b9', $item ) ?>
                </li>
			<?php endforeach; ?>
        </ul>

		<?php
		$list = ob_get_clean();


		$campaign = wp_remote_post( sprintf( 'https://api.createsend.com/api/v3.2/campaigns/%s/fromtemplate.json', get_field( 'field_61938af4c1fcf', 'option' ) ), [
			'headers' => CampaignMonitor::get_authorization_header(),
			'body'    => json_encode( [
				"Name"            => __( 'Livestream Reminder: ', 'reb_domain' ) . get_the_title( $immolive_id ),
				"Subject"         => get_the_title( $immolive_id ),
				"FromName"        => "brandtalks reb institute",
				"FromEmail"       => "noreply@reb.institute",
				"ReplyTo"         => "noreply@reb.institute",
				"ListIDs"         => [
					trim( get_post_meta( $immolive_id, 'cm_list', true ), '"' ),
				],
				"TemplateID"      => "db7689496dba3f028dcb75a7e4de3a8b",
				"TemplateContent" => [
					"Multilines" => [
						[
							"Content" => "<p>Ihr ImmoLive beginnt in k&uuml;rze</p>",
						],
						[
							"Content" => "<p style='font-weight: bold'>Beginn: " . get_field( 'field_5ed527e9c2279', $immolive_id ) . "</p>",
						],
						[
							"Content" => "<p>" . get_the_title( $immolive_id ) . "</p>",
						],
						[
							"Content" => "<p><strong>" . get_the_excerpt( $immolive_id ) . '</strong><br><br>' . get_the_content( $immolive_id ) . "</p>" . $list,
						],
					],
				],
			] ),
		] );

		$cm = new CampaignMonitor();

		if ( $cm->isSuccess( $campaign ) ) {

			$campaign = trim( wp_remote_retrieve_body( $campaign ), '"' );
			$termin   = get_field( 'field_5ed527e9c2279', $immolive_id );
			$sent_at  = new Carbon( $termin );
			$sent_at->subHours( 1 );

			$sent = wp_remote_post( 'https://api.createsend.com/api/v3.2/campaigns/' . $campaign . '/send.json', [
				'headers' => CampaignMonitor::get_authorization_header(),
				'body'    => json_encode( [
					"ConfirmationEmail" => "w.senk@immobilien-redaktion.at, gerhard@poppgerhard.at",
					"SendDate"          => $sent_at->format( 'Y-m-d H:i' ),
				] ),
			] );
		}
	}
}