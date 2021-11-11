<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "<?php the_title() ?>",
      "startDate": "<?php echo \Carbon\Carbon::parse(get_field('field_5ed527e9c2279'))->format('c') ?>",
      "endDate": "<?php echo \Carbon\Carbon::parse(get_field('field_5ed527e9c2279'))->addHour()->format('c') ?>",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
      "location": {
        "@type": "VirtualLocation",
        "url": "<?php echo home_url('diskutieren') ?>"
        },
      "image": [
        "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>",
        "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium') ?>",
        "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?>"
       ],
      "description": "<?php echo wp_strip_all_tags(get_the_content()) ?>",
      "offers": {
        "@type": "Offer",
        "url": "<?php echo get_field('field_5ed52801c227a') ?>",
        "price": "0",
        "priceCurrency": "EUR",
        "availability": "https://schema.org/InStock",
        "validFrom": "<?php echo the_time('c') ?>"
      },
      "organizer": {
        "@type": "Organization",
        "name": "Die unabhängige Immoblilien Redaktion",
        "url": "<?php echo home_url() ?>"
      }
    }
</script>
