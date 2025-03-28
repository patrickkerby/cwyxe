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
          // Create a new VCard instance for each agent
          $vcard = new VCard();

          // Define variables for the vCard
          $contact_details = get_field('contact_details', $post); 
          $lastname = $contact_details['last_name'] ?: '';
          $firstname = $contact_details['first_name'] ?: '';
          $additional = '';
          $prefix = '';
          $suffix = '';
          $photo_object = get_field('headshot', $post);
          $photo = $photo_object['url'];
          $vcard_filename = $firstname . '-' . $lastname . '.vcf';
          $vcard_filename = strtolower($vcard_filename);

          // Add personal data to the vCard
          $vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);

          // Add work data to the vCard
          $vcard->addCompany(get_field('company', $post->ID) ?: '');
          $vcard->addJobtitle($contact_details['title'] ?: '');
          $vcard->addEmail($contact_details['email'] ?: '');
          $vcard->addPhoneNumber($contact_details['office_phone'] ?: '', 'OFFICE');
          $vcard->addPhoneNumber($contact_details['mobile_phone'] ?: '', 'MOBILE');
          
          $vcard->addAddress(
              null,
              null,
              get_field('street', $post->ID) ?: '',
              get_field('city', $post->ID) ?: '',
              null,
              get_field('postcode', $post->ID) ?: '',
              get_field('country', $post->ID) ?: ''
          );
          $vcard->addURL(get_permalink($post->ID) ?: '');
          // $vcard->addPhoto($photo);

          // return vcard as a string
          //return $vcard->getOutput();

          // return vcard as a download
          // return $vcard->download();

          // save vcard on disk
          $upload_dir = wp_upload_dir();
          $upload_dir['baseurl']; 

          $vcard->setSavePath('app/vcards/');
          $vcard->save();


          // Return the agent data along with the vCard output
          return [
              'name' => get_the_title($post->ID),
              'slug' => $post->post_name,
              'link' => get_permalink($post->ID),
              'title' => get_field('title', $post),
              'headshot' => get_field('headshot', $post),
              'broker_attributes' => get_the_terms($post->ID, 'broker-attribute'),
              'contact_details' => get_field('contact_details', $post),
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