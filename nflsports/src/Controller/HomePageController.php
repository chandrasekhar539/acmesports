<?php
namespace Drupal\nflsports\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;

class HomePageController extends ControllerBase {
    public function homePage() {
    	$request = \Drupal::request();
      $config = \Drupal::config('nflsports.settings');
      $home_header_image = $config->get('homepage_header_text');
      $api_url = $config->get('api_url');
      $api_key = $config->get('api_key');
    	$session = $request->getSession();
      //storing the config data in an array
      $config_array['homepage_header_text'] = $config->get('homepage_header_text');
      $config_array['api_url'] = $config->get('api_url');
      $config_array['api_key'] = $config->get('api_key');
      if(!empty($config->get('homepage_image')[0])) {
        $file = \Drupal\file\Entity\File::load($config->get('homepage_image')[0]);
        $path = $file->getFileUri();
        $homepage_image = explode('public:/', $path)[1];
      }
      $config_array['homepage_image'] = $homepage_image;
    	$params = array('api_key' => $api_key);
    	$query = UrlHelper::buildQuery($params);
    	$url = $api_url.'?'.$query;
      if(!empty($api_url)) {
    	  $response = \Drupal::httpClient()->get($url);
    	  $response_array = json_decode($response->getBody()->getContents());
      }
    	foreach ($response_array as $key => $value) {
    		$teams_array = $value->data->team;
    		break;
    	}
    	foreach ($teams_array as $key => $value) {
    		$conferences[] = $value->conference;
    		$divisions[] = $value->division;
    	}
      //To remove duplicate entries in array
    	$conferences = array_unique($conferences);
    	$divisions = array_unique($divisions);
    	//storing necessary data in a session to reuse the data to avoid api call multiple times
    	if(!empty($teams_array)) {
    	  $session->set('nflteams',$teams_array);
     	}
     	if(!empty($divisions)) {
     		$session->set('divisions',$divisions);
     	}
      //remove the cache in controller to update the dymanic data from API
    	return [
      	  '#theme' => 'home_page',
          '#attached' => [
             'library' => ['nflsports/nflsports_library'],
           ],
          '#content' => $conferences,
          '#config' => $config_array,
          '#cache' => ['max-age' => 0,]
    	];
    }
}