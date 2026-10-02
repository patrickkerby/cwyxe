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

          $photo_path = $this->vcardPhotoPath($photo_object);
          if ($photo_path) {
              try {
                  $vcard->addPhoto($photo_path);
              } catch (\Throwable $e) {
                  // Skip the photo rather than failing the people page.
              }
          }

          $vcard_dir = trailingslashit(WP_CONTENT_DIR) . 'uploads/vcards';
          if (! is_dir($vcard_dir)) {
              wp_mkdir_p($vcard_dir);
          }

          if (is_dir($vcard_dir)) {
              $vcard->setSavePath($vcard_dir);
              $vcard->save();
          }

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

  /**
   * Local filesystem path for a headshot, so vCard embedding does not fetch over HTTPS.
   */
  private function vcardPhotoPath($photo_object): ?string
  {
      if (! is_array($photo_object)) {
          return null;
      }

      $attachment_id = (int) ($photo_object['ID'] ?? $photo_object['id'] ?? 0);
      if ($attachment_id > 0) {
          $path = get_attached_file($attachment_id);
          if (is_string($path) && is_readable($path)) {
              return $path;
          }
      }

      $url = $photo_object['url'] ?? '';
      if (! is_string($url) || $url === '') {
          return null;
      }

      $uploads = wp_get_upload_dir();
      $baseurl = trailingslashit($uploads['baseurl'] ?? '');
      $basedir = trailingslashit($uploads['basedir'] ?? '');

      if ($baseurl !== '/' && str_starts_with($url, $baseurl)) {
          $path = $basedir . substr($url, strlen($baseurl));
          if (is_readable($path)) {
              return $path;
          }
      }

      return null;
  }
}