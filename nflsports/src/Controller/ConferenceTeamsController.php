<?php
namespace Drupal\nflsports\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;

class ConferenceTeamsController extends ControllerBase {
    public function conferenceTeams($conference = null,$division = null) {
    	$request = \Drupal::request();
      $config = \Drupal::config('nflsports.settings');
    	$session = $request->getSession();
    	$teams = $session->get('nflteams');
    	$divisions = $session->get('divisions');
    	$index = 0;
      //Populating Teams List Data
    	foreach ($teams as $key => $value) {
    		if($value->conference == $conference && $division == null) {
    			$teamsData[$index]['name'] = $value->name;
    			$teamsData[$index]['nickname'] = $value->nickname;
    			$teamsData[$index]['display_name'] = $value->display_name;
    			$teamsData[$index]['conference'] = $value->conference;
    			$teamsData[$index]['division'] = $value->division;
    			$index = $index + 1;
    		}
    		elseif ($value->conference == $conference && $value->division == $division) {
    			$teamsData[$index]['name'] = $value->name;
    			$teamsData[$index]['nickname'] = $value->nickname;
    			$teamsData[$index]['display_name'] = $value->display_name;
    			$teamsData[$index]['conference'] = $value->conference;
    			$teamsData[$index]['division'] = $value->division;
    			$index = $index + 1;
    		}
    	}
      //Retrieving the National and american images from config
      if(!empty($config->get('national_football_image')[0])) {
          $file = \Drupal\file\Entity\File::load($config->get('national_football_image')[0]);
          $path = $file->getFileUri();
          $config_array['national_image'] = explode('public:/', $path)[1];
      }
      if(!empty($config->get('american_football_image')[0])) {
          $file = \Drupal\file\Entity\File::load($config->get('american_football_image')[0]);
          $path = $file->getFileUri();
          $config_array['american_image'] = explode('public:/', $path)[1];
      }
    	return [
      	  '#theme' => 'conference_teams_page',
          '#attached' => [
             'library' => ['nflsports/nflsports_library'],
           ],
          '#content' => $teamsData,
          '#conference' => $conference,
          '#selecteddivision' => $division,
          '#divisions' => $divisions,
          '#config' => $config_array
    	];
    }
}