<?php


namespace reb_livestream_classes\Immolive;

use Carbon\Carbon;
use reb_livestream_classes\CampaignMonitor;

trait ImmoliveEmails {


	public function send_subscription_email( $email, $immolive_id ) {


		$ical_url = get_field( 'field_6143982f5f5f2', $immolive_id );
		$ical     = file_get_contents( $ical_url );

		$data = [
			'headerimage ' => get_the_post_thumbnail_url( $immolive_id, 'full' ),
			'title'        => get_the_title( $immolive_id ),
			'excerpt'      => get_the_excerpt( $immolive_id ),
			'permalink'    => get_the_permalink( $immolive_id ),
			'speakers'     => [],
		];

		$speakers = get_field( 'field_614ad5e239622', $immolive_id );
		if ( $speakers ) {
			foreach ( $speakers as $speaker ) {
				$data['speakers'][] = [
					'image'    => get_the_post_thumbnail_url( $speaker, 'thumbnail' ),
					'name'     => get_field('field_613c53f33d6b8', $speaker) . ' ' . get_field('field_613b8ca49b06b', $speaker),
					'position' => get_field('field_613c54063d6b9', $speaker),
					'company'  => get_field('field_613b8caa9b06c', $speaker),
					'excerpt'  => get_the_excerpt( $speaker)
				];
			}
		}

		$result = wp_remote_post( sprintf( 'https://api.createsend.com/api/v3.2/transactional/smartEmail/%s/send', get_field('field_61927467aa0a0', 'option') ), [
			'headers' => CampaignMonitor::get_authorization_header(),
			'body'    => json_encode( [
				'To'                  => $email,
				"Data"                => $data,
				"AddRecipientsToList" => true,
				"ConsentToTrack"      => "Yes",
				'Attachments'         => [
					[
						"Type"    => "text / calendar",
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
			$this->add_subscriber_to_list( $immolive_id, get_user_by( 'email', 'gerhard@poppgerhard . at' ) );
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

		$cm = new CampaignMonitor();

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

		$this->delete_campaign_by_post_id( $immolive_id );


		$campaign = wp_remote_post( sprintf( 'https://api.createsend.com/api/v3.2/campaigns/%s/fromtemplate.xml', get_field( 'field_61938af4c1fcf', 'option' ) ), [
			'headers' => CampaignMonitor::get_authorization_header( true ),
			'body'    => $this->content_xml( $immolive_id ),
		] );

		if ( $cm->isSuccess( $campaign ) ) {

			$campaign = trim( wp_remote_retrieve_body( $campaign ), '"' );
			$sent     = wp_remote_post( 'https://api.createsend.com/api/v3.2/campaigns/' . $campaign . '/send.json', [
				'headers' => CampaignMonitor::get_authorization_header(),
				'body'    => json_encode( [
					"ConfirmationEmail" => "ronald.goigitzer@goreeo.eu, gerhard@poppgerhard.at",
					"SendDate"          => $termin->subHour()->format( 'Y-m-d H:i' ),
				] ),
			] );
		}

	}

			function delete_campaign_by_post_id( $id ) {

				$cm = new CampaignMonitor();

				$response = wp_remote_get( sprintf( 'https://api.createsend.com/api/v3.2/clients/%s/scheduled.json', get_field( 'field_61938af4c1fcf', 'option' ) ), [
					'headers' => CampaignMonitor::get_authorization_header(),
				] );


				if ( $cm->isSuccess( $response ) ) {
					$scheduled = json_decode( wp_remote_retrieve_body( $response ) );
				} else {
					$scheduled = [];
				}

				$response = wp_remote_get( sprintf( 'https://api.createsend.com/api/v3.2/clients/%s/drafts.json', get_field( 'field_61938af4c1fcf', 'option' ) ), [
					'headers' => CampaignMonitor::get_authorization_header(),
				] );

				if ( $cm->isSuccess( $response ) ) {
					$drafts = json_decode( wp_remote_retrieve_body( $response ) );
				} else {
					$drafts = [];
				}

				$campaings = array_merge( $drafts, $scheduled );

				foreach ( $campaings as $campaing ) {
					if ( str_starts_with( $campaing->Name, $id ) ) {
						$this->delete_campaign( $campaing->CampaignID );
					}
				}
			}

			public
			function delete_campaign( $id ) {
				$response = wp_remote_request( sprintf( 'https://api.createsend.com/api/v3.2/campaigns/%s.json', $id ),
					[ 'method' => 'DELETE', 'headers' => CampaignMonitor::get_authorization_header() ] );

				$cm = new CampaignMonitor();
				$cm->isSuccess( $response );
			}


			public
			function content_xml( $livestream_id ) {

				$name      = $livestream_id . ' ' . get_the_title( $livestream_id );
				$title     = get_the_title( $livestream_id );
				$termin    = new Carbon( get_field( 'field_5ed527e9c2279', $livestream_id ) );
				$time      = 'Beginnt um ' . $termin->format( 'H:i' ) . ' Uhr';
				$list      = trim( get_post_meta( $livestream_id, 'cm_list', true ), '"' );
				$template  = get_field( 'field_61927472aa0a1', 'option' );
				$excerpt   = get_the_excerpt( $livestream_id );
				$permalink = get_the_permalink( $livestream_id );
				$image     = get_the_post_thumbnail_url( $livestream_id, 'featured' );

				$xml = <<<EOM
<?xml version="1.0" encoding="utf-8"?>
<Campaign>
    <Name>$name</Name>
    <Subject>$title</Subject>
    <FromName>brandtalks reb institute</FromName>
    <FromEmail>noreply@reb.institute</FromEmail>
    <ReplyTo>noreply@reb.institute</ReplyTo>
    <ListIDs>
        <ListID>$list</ListID>
    </ListIDs>
    <TemplateID>$template</TemplateID>
    <TemplateContent>
    <Images>
      <Image>
        <Content>$image</Content>
        <Alt>$title</Alt>
        <Href>$permalink</Href>
      </Image>
    </Images>
        <Repeaters>
      <Repeater>
        <Items>
          <RepeaterItem>
            <Layout>SIMPLE TEXT</Layout>
            <Singlelines>
              <Singleline>
                <Content><![CDATA[<a href="https://example.com" style="color:white !important; text-decoration: none !important;">Zum Livestream</a>]]></Content>
              </Singleline>
            </Singlelines>
            <Multilines>
              <Multiline>
                <Content>$title</Content>
              </Multiline>
              <Multiline>
                <Content>$time</Content>
              </Multiline>
              <Multiline>
                <Content>$excerpt</Content>
              </Multiline>
            </Multilines>
          </RepeaterItem>
          </Items>
          </Repeater>
          <Repeater>
          <Items>
       
EOM;

				$teilnehmer = get_field( 'field_614ad5e239622', $livestream_id );

				$items = [];
				foreach ( $teilnehmer as $t ) {

					$image       = get_the_post_thumbnail_url( $t, 'thumbnail' );
					$title       = get_the_title( $t );
					$postition   = get_field( 'field_613c54063d6b9', $t );
					$unternehmen = get_field( 'field_613b8caa9b06c', $t );
					$excerpt     = get_the_excerpt( $t );

					$items[] = <<<EOM
 
        <RepeaterItem>
            <Layout>SPEAKERSTABLE</Layout>
            <Images>
              <Image>
                <Content>$image</Content>
              </Image>
            </Images>
            <Multilines>
              <Multiline>
                <Content>$title</Content>
              </Multiline>
              <Multiline>
                <Content>$postition</Content>
              </Multiline>
              <Multiline>
                <Content>$unternehmen</Content>
              </Multiline>
              <Multiline>
                <Content>$excerpt</Content>
              </Multiline>
            </Multilines>
        </RepeaterItem>
   
EOM;


				}

				$speakers = implode( '', $items );

				$end = <<<EOM
 </Items>
 </Repeater>
    </Repeaters>
    </TemplateContent>
</Campaign>
EOM;

				$xml = $xml . $speakers . $end;

				return $xml;


			}

		}