<?php

use Results\RunsetResult as RunsetResult;

function process_post_request($app){
	
	// get arguments
	$post_data = $app->request->post();
	
	RunsetResult::setApp($app);
	
	// basic check on posted arguments
    if(sizeof($post_data) == 0){
      echo(json_encode(array("Exception" => "No parameter provided.")));
      exit();
    } elseif (!array_key_exists('runset_id', $post_data)) {
      echo(json_encode(array("Exception" => "Missing 'runset_id' argument.")));
      exit();
    }

	// create empty object in the file system
	$runset_id = $post_data['runset_id'];
    try{
      RunsetResult::create(['id' => $runset_id]);
      $return_array = array("Success" => "Reserved runset id '".$runset_id."'");
    } catch(Exception $exp) {
      $return_array = array("Exception" => $exp->getMessage());
	}
    echo(json_encode($return_array));
}

function process_get_request($app){
	
	$with_id = $app->request->params("id");
	
	RunsetResult::setApp($app);
	
	// query search
	if(sizeof($app->request->params()) == 0){
		$return_runsetresults = RunsetResult::all();
	} elseif (!is_null($with_id)) {
		$return_runsetresults = RunsetResult::where('id', $with_id);
	} else {
		$return_runsetresults = array("error"=>"unexpected parameter");
	}
	
	// show it in JSON format
	$return_array = array();
	foreach($return_runsetresults as $cur_runsetresult){
		if (is_object($cur_runsetresult)){
			array_push($return_array, $cur_runsetresult->toArray());
		} elseif(is_array($cur_runsetresult)) {
			array_push($return_array, $cur_runsetresult);
		} else {
			echo("What is '".$cur_runsetresult."'?\n");
		}
	}
	echo(json_encode($return_array));
}

function process_delete_request($app, $sc_runset_id){
	echo(json_encode(array("error" => "Function not implemented yet.")));
}

?>