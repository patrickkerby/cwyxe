<?php

namespace App\View\Composers;

use JeroenDesloovere\VCard\VCard;
use Roots\Acorn\View\Composer;

class Agents extends Composer
{
  public function agentsLoop()
  {
      $agents = get_posts([
          'post_type' => 'agent',
          'posts_per_page' => '-1',
          'orderby' => 'date',
          'order' => 'ASC'
      ]);

      return array_map(function ($post) {
          $vcard = new VCard();

          $contact_details = get_field('contact_details', $post);
          if (! is_array($contact_details)) {
              $contact_details = [];
          }

          $lastname = $contact_details['last_name'] ?? '';
          $firstname = $contact_details['first_name'] ?? '';
          $photo_object = get_field('headshot', $post);
          $photo = is_array($photo_object) ? ($photo_object['url'] ?? '') : '';
          $vcard_filename = strtolower(trim($firstname . '-' . $lastname) . '.vcf');
          $address = get_field('office_address', 'option');
          if (! is_array($address)) {
              $address = [];
          }

          $vcard->addName($lastname, $firstname, '', '', '');
          $vcard->addCompany(get_field('company', $post->ID) ?: '');
          $vcard->addJobtitle($contact_details['title'] ?? '');
          $vcard->addEmail($contact_details['email'] ?? '');
          $vcard->addPhoneNumber($contact_details['office_phone'] ?? '', 'WORK');
          $vcard->addPhoneNumber($contact_details['mobile_phone'] ?? '', 'CELL');
          $vcard->addAddress(
              null,
              null,
              $address['street'] ?? '',
              $address['city'] ?? '',
              $address['postal_code'] ?? '',
              $address['province'] ?? '',
              $address['country'] ?? ''
          );
          $vcard->addURL(get_permalink($post->ID) ?: '');
          if ($photo !== '') {
              $vcard->addPhoto($photo);
          }

          $vcard->setSavePath('app/uploads/vcards/');
          $vcard->save();

          return [
              'name' => get_the_title($post->ID),
              'slug' => $post->post_name,
              'link' => get_permalink($post->ID),
              'title' => get_field('title', $post),
              'headshot' => is_array($photo_object) ? $photo_object : null,
              'broker_attributes' => \App\Support\Terms::forPost($post->ID, 'broker-attribute'),
              'contact_details' => $contact_details,
              'vcard-filename' => $vcard_filename
          ];
      }, $agents);
  }

  public function with()
  {
      return [
        'agents' => $this->agentsLoop()          
      ];
  }
}