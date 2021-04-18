<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Equipement;

class OneDriveController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function getAllData()
  {
    $graph = $this->getGraph();

    $docGrabber = $graph->createCollectionRequest("GET", "/me/drive/root/children")
    ->setReturnType(Model\DriveItem::class)->execute();
    return response()->json($docGrabber);
  }
  public function getDataById($id)
  {
    $graph = $this->getGraph();
    $data = $graph->createCollectionRequest("GET", "/me/drive/items/".$id."/children")
    ->setReturnType(Model\DriveItem::class)
    ->execute();
    return response()->json($data);
  }
public function downloadEquipementData($id)
  {
    $graph = $this->getGraph();
    $path = public_path()."/downloads/equipement.xlsx";
    $data = $graph->createRequest("GET", "/me/drive/items/".$id."/content")
    ->setReturnType(Model\DriveItem::class)
    ->download($path);
    return response()->json($data);
  }

  public function downloadFile($id,$file)
  {
    $graph = $this->getGraph();
    $path = public_path()."/downloads/".time().".pdf";
    if($file != 'pdf'){
      $data = $graph->createRequest("GET", "/me/drive/items/".$id."/content?format=pdf")
      ->download($path);
    }
    else{
      $data = $graph->createRequest("GET", "/me/drive/items/".$id."/content")
      ->download($path);
    }
    return response()->json($path);
  }

  private function getGraph(): Graph
  {
    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    return $graph;
  }
}